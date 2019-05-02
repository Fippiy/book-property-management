<?php

namespace App\Http\Controllers;

use App\User;
use App\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $property = Property::userGetBook();
        return view('property.index',['user'=>$user, 'books'=>$property]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // 所有書籍を除外して取得
        $notProperties = Property::userNothaveBook();
        $msg = '登録書籍を選択して下さい。';
        return view('property.create',['books'=>$notProperties, 'msg'=>$msg]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      // バリデーションチェック
      $createPropertyRules = Property::createPropertyRules();
      $this->validate($request, $createPropertyRules);
      // 新規レコード生成
      $property = new Property;
      // リクエストデータ受取
      $form = $request->all();
      // フォームトークン削除
      unset($form['_token']);
      // ユーザー情報追加
      $user = Auth::user()->id;
      $form = $form + array('user_id' => $user);
      // DB保存前に型変換
      // $form["bookdata_id"] = intval($form["bookdata_id"]);
      // $form["number"] = intval($form["number"]);
      // DB保存
      $property->fill($form)->save();
      // 登録完了メッセージ
      $msg = "所有書籍を登録しました。";
      // 次の登録用フォームデータ取得
      // 所有書籍を除外して取得
      $notProperties = Property::userNothaveBook();
      return view('property.create',['books'=>$notProperties, 'property'=>$property, 'msg'=>$msg]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $property = Property::find($id);
        return view('property.show', ['property' => $property]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $property = Property::find($id);
        return view('property.edit', ['form' => $property]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      // バリデーションルール設定適用がいる
      // 対象レコード取得
      $property = Property::find($id);
      // リクエストデータ受取
      $form = $request->all();
      // フォームトークン削除
      unset($form['_token']);
      // レコードアップデート
      $property->fill($form)->save();
      return redirect('/property');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      // 削除レコード取得
      $delete_book = Property::find($id);
      // レコード削除
      $delete_book->delete();
      return redirect('/property');
    }
    public function find(Request $request)
    {
      $msg = '検索ワードを入力して下さい';
      return view('property.find', ['input' => '','msg'=>$msg]);
    }

    public function search(Request $request)
    {
      // バリデーションチェック
      $this->validate($request, Property::$searchRules);
      $title = $request->find;
      $properties = Property::where('user_id', Auth::user()->id)
                        ->join('bookdata','bookdata.id','=','properties.bookdata_id')
                        ->where('title', 'like', "%{$title}%")
                        ->select('properties.id','title','picture','cover','freememo')
                        ->get();
      $count = count($properties);
      if ($count==0) {
        $msg = '書籍がみつかりませんでした。';
      } else {
        $msg = $count.'件の書籍がみつかりました。';
      }
      $param = ['input' => $title, 'books' => $properties, 'msg' => $msg];
      return view('property.find', $param);
    }


}

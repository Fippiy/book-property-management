<?php

namespace App\Http\Controllers;

use App\User;
use App\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

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
      $user = Auth::user()->id; // ユーザー情報取得
      $form = $request->all(); // リクエストデータ受取

      // 書籍情報nullチェック
      $validator = Validator::make($form, [
        'bookdata_id' => 'required',
      ])->validate();

      // ユーザーの所有書籍に登録済みか確認
      // ユーザー書籍取得
      $entry_property = Property::where('user_id', $user)
                        ->where('bookdata_id', $form['bookdata_id'])
                        ->first();
      // 書籍があればFalse
      if (count($entry_property) > 0) {
        $have_property = false;
      } else {
        $have_property = true;
      }
      // False時にエラーとして返す
      $validator = Validator::make(['bookdata_id' => $have_property], ['bookdata_id' => 'accepted'], ['書籍は登録済みです']);
      if ($validator->fails()) {
        return redirect('property/create')
                    ->withErrors($validator)
                    ->withInput();
      }

      // バリデーションエラーなしで新規登録を実施
      // 新規レコード生成
      $property = new Property;
      // フォームトークン削除
      unset($form['_token']);

      // ユーザー情報追加
      $form = $form + array('user_id' => strval($user));

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
      // 対象レコード取得
      $property = Property::find($id);
      // 本人認証の上、更新処理
      if ($property['user_id'] == Auth::user()->id){
        // リクエストデータ受取
        $form = $request->all();
        // フォームトークン削除
        unset($form['_token']);
        // bookdataは変更しないので、送信されても削除
        unset($form['bookdata_id']);
        // レコードアップデート
        $property->fill($form)->save();
      }
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
      // 本人の場合のみ削除実施
      if ($delete_book['user_id'] == Auth::user()->id){
        // レコード削除
        $delete_book->delete();
      }
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

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
      if ($entry_property == null) {
        $have_property = true;
      } else {
        $have_property = false;
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
                        ->paginate(20);
      $count = count($properties);
      if ($count==0) {
        $msg = '書籍がみつかりませんでした。';
      } else {
        $msg = $properties->total().'件の書籍がみつかりました。';
      }
      $param = ['input' => $title, 'books' => $properties, 'msg' => $msg];
      return view('property.find', $param);
    }
    public function somedelete(Request $request){
      // フォームデータ取得
      unset($request['_token']); // トークン削除
      $datas = $request->input('select_books'); // 削除書籍情報をフォームから取得
      $count = count($datas); // 取得件数

      // 取得データなければ処理中止
      if ($count == 0) {
        // 削除情報が1件もないときはバリデーションエラーにする
        $validator = Validator::make(['deleteproperty' => false], ['deleteproperty' => 'accepted'], ['所有書籍から解除する本が選択されていません']);
        if ($validator->fails()) {
          return redirect('property')
                      ->withErrors($validator)
                      ->withInput();
        }
      }

      // 複数登録同様に結果配列をつくる
      // 処理用配列へ追加
      $i = 1; // 結果出力番号
      foreach($datas as $data){
        // 一度に削除できる上限数で処理を停止
        if ($i > 20){
          break;
        }
        // 配列格納
        $deleteproperties[] = array(
          'process' => 'processing', // 処理中ステータス
          'number' => $i, // 番号
          'property_id' => $data, // 所有書籍から解除するid
          'msg' => null, // 処理テキスト
        );
        $i++;
      }

      // 削除の実行
      for ($i = 0; $i < $count; $i++){
        if ($deleteproperties[$i]['process'] == 'processing'){
          // 削除レコード取得
          $delete_property = Property::find($deleteproperties[$i]['property_id']);

          // 本人の場合のみ削除実施
          if ($delete_property['user_id'] == Auth::user()->id){
            // レコード削除
            $delete_property->delete();
            data_set($deleteproperties[$i], 'title', $delete_property->bookdata->title); // 表示タイトル名を追加
            data_set($deleteproperties[$i], 'msg', "所有書籍から解除しました"); // メッセージを追加
            data_set($deleteproperties[$i], 'process', 'completion'); // 処理ステータス変更
          }
        }
      }
      return view('property.delete_result',['answers' => $deleteproperties]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Bookdata;
use Illuminate\Http\Request;
use Aws\S3\S3Client;
use Validator;
use App\Property;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $books = Bookdata::all();
        return view('book.index', ['books' => $books]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $msg = '登録する本の情報を入力して下さい。';
      return view('book.create',['msg'=>$msg]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $this->validate($request, Bookdata::$rules);
      // 新規投稿レコード
      $book = new Bookdata;
      // リクエストデータ受取
      $form = $request->all();
      // フォームトークン削除
      unset($form['_token']);
      // 画像データ有無判定
      if (isset($form['picture'])) {
        // 画像保存ディレクトリ設定
        $save_directory = "book_images";

        // 画像情報
        $picture_name = $_FILES['picture']['name']; //ファイル名
        $picture_ext = substr($picture_name, strrpos($picture_name, '.') + 1); //拡張子
        $picture_tmp_name = $_FILES['picture']['tmp_name']; //tmp_name
        $picture_error = $_FILES['picture']['error']; //errorコード

        // 画像ファイルの判定
        picture_check($picture_ext,$picture_error);

        // 開発環境で画像保存先を変更
        if ( app()->isLocal() || app()->runningUnitTests() ) {
          // ローカル保存処理
          $request->picture->storeAs('public/'.$save_directory, $picture_name); // 画像ファイルをstorage保存
          $picture_upload = "/storage/".$save_directory."/".$picture_name; //画像保存パス

        } else {
          // 本番環境保存処理
          // S3インスタンス生成
          $s3settings = s3settings();

          // S3アップロード処理
          $picture_upload = picture_upload($save_directory,$picture_name,$picture_tmp_name,$picture_ext,$s3settings);
        }
        $form['picture'] = $picture_upload; //画像パスをDB保存値に設定
      }
      // DB保存
      $book->fill($form)->save();
      $msg = '本を登録しました。続けて登録できます。';
      return view('book.create',['msg'=>$msg, 'book'=>$book]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      $book = Bookdata::find($id);
      return view('book.show', ['book' => $book]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $book = Bookdata::find($id);
      return view('book.edit', ['form' => $book]);
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
      $this->validate($request, Bookdata::$rules);
      // 画像保存ディレクトリ設定
      $save_directory = "book_images";
      // 対象レコード取得
      $book = Bookdata::find($id);
      // リクエストデータ受取
      $form = $request->all();
      // フォームトークン削除
      unset($form['_token']);

      // 写真変更がある場合
      if (isset($form['picture'])) {
        // 削除処理実施
        // 既存画像がない時は削除処理不要
        if ($book['picture'] != null) {
          // 開発環境で画像保存先を変更
          if ( app()->isLocal() || app()->runningUnitTests() ) {
            // 写真削除情報取得
            $deletename = str_replace('/storage/'.$save_directory.'/','',$book->picture);
            $pathdel = storage_path() . '/app/public/book_images/' . $deletename;
            // 写真削除
            \File::delete($pathdel);
          } else {
            // 本番環境削除処理
            // S3インスタンス生成
            $s3settings = s3settings();
            // S3削除処理
            $picture_delete = picture_delete($save_directory,$book,$s3settings);
          }
        }
        // 削除のみか判定
        if ($form['picture'] == "no-picture") {
          // レコードをnull化
          $picture_upload = null;
        // 画像変更時
        } else {
          // 写真追加処理
          // 変更画像情報取得
          $picture_name = $_FILES['picture']['name']; //ファイル名
          $picture_ext = substr($picture_name, strrpos($picture_name, '.') + 1); //拡張子
          $picture_tmp_name = $_FILES['picture']['tmp_name']; //tmp_name
          $picture_error = $_FILES['picture']['error']; //errorコード

          // 画像ファイルの判定
          picture_check($picture_ext,$picture_error);
          // 開発環境で画像保存先を変更
          if ( app()->isLocal() || app()->runningUnitTests() ) {
            // ローカル保存処理
            $request->picture->storeAs('public/'.$save_directory, $picture_name); // 画像ファイルをstorage保存
            $picture_upload = "/storage/".$save_directory."/".$picture_name; //画像保存パス
          } else {
            // 本番環境画像追加処理
            // S3インスタンス生成
            $s3settings = s3settings();
            // S3アップロード処理
            $picture_upload = picture_upload($save_directory,$picture_name,$picture_tmp_name,$picture_ext,$s3settings);
          }
        }
        $form['picture'] = $picture_upload; //画像パスをDB保存値に設定
      }

      // レコードアップデート
      $book->fill($form)->save();
      return redirect('/book');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $have_property = Property::where('bookdata_id', $id)->first();
      if (count($have_property) > 0) {
        $have_book = false;
      } else {
        $have_book = true;
      }
      $validator = Validator::make(['bookdata_id' => $have_book], ['bookdata_id' => 'accepted'], ['所有者がいるため削除できません']);
      if ($validator->fails()) {
        return redirect('book/'.$id)
                    ->withErrors($validator)
                    ->withInput();
      }
      // 画像保存ディレクトリ設定
      $save_directory = "book_images";
      // 削除レコード取得
      $delete_book = Bookdata::find($id);

      // 写真削除がある場合
      if (isset($delete_book['picture'])) {
        // 開発環境で画像保存先を変更
        if ( app()->isLocal() || app()->runningUnitTests() ) {
          // 写真削除情報取得
          $deletename = str_replace('/storage/'.$save_directory.'/','',$delete_book->picture);

          $pathdel = storage_path() . '/app/public/book_images/' . $deletename;
          // 写真削除
          \File::delete($pathdel);
        } else {
          // 本番環境削除処理
          // S3インスタンス生成
          $s3settings = s3settings();
          // S3削除処理
          $picture_delete = picture_delete($save_directory,$delete_book,$s3settings);
        }
      }
      // レコード削除
      $delete_book->delete();
      return redirect('/book');
    }

    public function find(Request $request)
    {
      $msg = '検索ワードを入力して下さい';
      return view('book.find', ['input' => '','msg'=>$msg]);
    }

    public function search(Request $request)
    {
      // バリデーションチェック
      $this->validate($request, Bookdata::$searchRules);
      $title = $request->find;
      $books = Bookdata::where('title', 'like', "%{$title}%")->get();
      $count = count($books);
      if ($count==0) {
        $msg = '書籍がみつかりませんでした。';
      } else {
        $msg = $count.'件の書籍がみつかりました。';
      }
      $param = ['input' => $title, 'books' => $books, 'msg' => $msg];
      return view('book.find', $param);
    }

    public function getIsbn(){
      $msg = 'ISBNコードを入力して下さい。';
      return view('book.isbn',['msg'=>$msg]);
    }
    public function getIsbnSome(){
      $msg = 'ISBNコードを入力して下さい。';
      return view('book.isbn_some',['msg'=>$msg]);
    }
    public function getIsbnSomeInput(){
      $msg = 'ISBNコードを入力して下さい。';
      return view('book.isbn_some_input',['msg'=>$msg]);
    }
    public function postIsbn(Request $request){
        // バリデーションチェック
        $this->validate($request, Bookdata::$isbnEntryRules);

        unset($request['_token']);
        $value = $request['isbn'];

        // ISBNコードから本情報を取得
        $isbn_url = 'https://api.openbd.jp/v1/get?isbn=';
        $response = file_get_contents(
                          $isbn_url.$value
                    );
        $result = json_decode($response, true);


        // ISBNレコード結果を確認
        // result[0]の情報有り無しで判定
        if($result[0] == null)
        {
          $isbndata = false;
        } else {
          $isbndata = true;
        }

        // isbnレコード結果チェック
        // false時は検索結果なしで未登録とし、バリデーションエラーを返す
        $validator = Validator::make(['isbn' => $isbndata], ['isbn' => 'accepted'], ['該当するISBNコードは見つかりませんでした。']);
        if ($validator->fails()) {
          return redirect('book/isbn')
                      ->withErrors($validator)
                      ->withInput();
        }

        // ISBNレコードがあれば追加処理
        $savedata = new Bookdata;

        // summaryData取得
        $getdata = $result[0]["summary"];

        // 要素毎にレコードに追加
        foreach($getdata as $key => $value){
          if(strlen($value) == 0){
            $savedata->$key = null;
          } else {
            $savedata->$key = $value;
          }
        }

        // detail取得
        $detail_datacheck = empty($result[0]["onix"]["DescriptiveDetail"]["Contributor"][0]["BiographicalNote"]);
        if(strlen($detail_datacheck) == true){
          $savedata->detail = null;
        } else {
          $savedata->detail = $result[0]["onix"]["DescriptiveDetail"]["Contributor"][0]["BiographicalNote"];
        }
        // 保存
        $savedata->save();
        // 保存完了メッセージ
        $msg = 'データを新規作成しました。続けてISBNコードを登録できます。';
        // ビューに出力
        return view('book.isbn',['msg'=>$msg,'book'=>$savedata]);
    }

    public function postIsbnSome(Request $request){

        // フォームデータ取得
        unset($request['_token']); // トークン削除
        $isbns = $request->all(); // isbnコードをフォームから取得
        // isbnsキーが設定されている場合は指定キーで区切る
        if(array_key_exists('isbns', $isbns)){
          $data = $isbns['isbns'];
          $isbns = preg_split("/[\s,]+/", $data); // カンマと空文字列で区切る
        }
        $count = count($isbns); // 取得件数
        $isbnrecords = []; // データ格納用配列
        // 処理用配列へ追加
        $i = 1; // 結果出力番号
        foreach($isbns as $isbn){
          if ($isbn != null){ 
            if ($i > 20){ // 一度に登録できる上限
              break;
            }
            // isbn設定
            $setisbn = str_replace('-', '', $isbn); // ハイフンを含んでいる場合は取り除く
            // 配列格納
            $isbnrecords[] = array(
              'process' => 'processing',
              'number' => $i,
              'isbn' => $setisbn,
              'msg' => null,
            );
          }
          $i++;
        }

        // ISBN登録がない場合はバリデーションエラー
        if ($isbnrecords == null) {
          $isbnvalid = false;
        } else {
          $isbnvalid = true;
        }
        $validator = Validator::make(['isbnrecords' => $isbnvalid], ['isbnrecords' => 'accepted'], ['ISBNコードが入力されていません']);
        if ($validator->fails()) {
          return redirect('book/isbn_some')
                      ->withErrors($validator)
                      ->withInput();
        }
        $count = count($isbnrecords);

        // フォームデータ評価
        for ($i = 0; $i < $count; $i++){
          if ($isbnrecords[$i]['process'] == 'processing'){ // 処理変数が処理中の場合は処理を実施する
            if (ctype_digit($isbnrecords[$i]['isbn']) == false) {
              data_set($isbnrecords[$i], 'msg', '数値ではありません'); // 数値でない場合はメッセージ格納
              data_set($isbnrecords[$i], 'process', 'error'); // 処理ステータスエラー
            } elseif (mb_strlen($isbnrecords[$i]['isbn']) != 13){
              data_set($isbnrecords[$i], 'msg', '桁数が13桁ではありません。'); // 13桁でない場合はメッセージ格納
              data_set($isbnrecords[$i], 'process', 'error'); // 処理ステータスエラー
            } else {
              for ($j = $i+1; $j < $count; $j++){
                if ($isbnrecords[$i]['isbn'] == $isbnrecords[$j]['isbn']) {
                  data_set($isbnrecords[$j], 'msg', ($i+1)."件目のデータと同じです。"); // フォームデータ重複チェック
                  data_set($isbnrecords[$j], 'process', 'error'); // 処理ステータスエラー
                }
              }
            }
            // DBユニークチェック
            if (Bookdata::where('isbn', $isbnrecords[$i]['isbn'])->first() != null){
              data_set($isbnrecords[$i], 'msg', '既に登録されています。'); // DB存在時はメッセージ格納
              data_set($isbnrecords[$i], 'process', 'error'); // 処理ステータスエラー
            }
          }
        }

        // ISBNコードから本情報を取得
        $isbn_url = 'https://api.openbd.jp/v1/get?isbn='; // API設定
        // 検索該当レコードのみデータ取得を実施
        for ($i = 0; $i < $count; $i++){
          if ($isbnrecords[$i]['process'] == 'processing'){ // 処理変数が処理中の場合は処理を実施する
            $isbn = $isbnrecords[$i]['isbn'];
            $response = file_get_contents($isbn_url.$isbn);
            $result = json_decode($response, true);
            // ISBNレコード結果を確認
            // result[0]の情報有り無しで判定
            if($result[0] == null) {
              data_set($isbnrecords[$i], 'msg', '該当するISBNコードは見つかりませんでした。'); // 未登録時はメッセージ格納
              data_set($isbnrecords[$i], 'process', 'error'); // 処理ステータスエラー
            } else {
              data_set($isbnrecords[$i], 'result', $result); // 結果を格納
            }
          }
        }

        for ($i = 0; $i < $count; $i++){
          if ($isbnrecords[$i]['process'] == 'processing'){ // 処理変数が処理中の場合は処理を実施する
            // ISBNレコードがあれば追加処理
            $savedata = new Bookdata;

            // summaryData取得
            $getdata = $isbnrecords[$i]['result'][0]["summary"];
            // 要素毎にレコードに追加
            foreach($getdata as $key => $value){
              if(strlen($value) == 0){
                $savedata->$key = null;
              } else {
                $savedata->$key = $value;
              }
            }
            // detail取得
            $detail_datacheck = empty($isbnrecords[$i]['result'][0]["onix"]["DescriptiveDetail"]["Contributor"][0]["BiographicalNote"]);
            if(strlen($detail_datacheck) == true){
              $savedata->detail = null;
            } else {
              $savedata->detail = $isbnrecords[$i]['result'][0]["onix"]["DescriptiveDetail"]["Contributor"][0]["BiographicalNote"];
            }
            // 保存
            $savedata->save();
            data_set($isbnrecords[$i], 'result', $savedata); // 未登録時はメッセージ格納
            // 保存完了メッセージ
            data_set($isbnrecords[$i], 'msg', '登録しました'); // 未登録時はメッセージ格納
            data_set($isbnrecords[$i], 'process', 'completion'); // 処理ステータス完了
          }
        }
        // ビューに出力
        return view('book.isbn_result',['answers' => $isbnrecords]);
    }
}

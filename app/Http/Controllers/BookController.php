<?php

namespace App\Http\Controllers;

use App\Bookdata;
use Illuminate\Http\Request;
use Aws\S3\S3Client;

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
      return view('book.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
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
        // if ( app()->isLocal() || app()->runningUnitTests() ) {
        if ( app()->runningUnitTests() ) {
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
      return redirect('/book');
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
      $book = Bookdata::find($id);
      $form = $request->all();
      unset($form['_token']);

      // 写真削除処理
      if (isset($form['picture'])) {
        // 削除写真名取得
        $deletename = $book->picture;
        // 写真削除
        $pathdel = storage_path() . '/app/public/book_images/' . $deletename;
        \File::delete($pathdel);
        // 削除のみ時
        if ($form['picture'] == "no-picture") {
          // レコード更新処理
          $form['picture'] = null;
        // 変更後画像がある時
        } else {
          // 写真追加処理
          // 画像情報取得
          $file = $request->file('picture');
          // 拡張子取得
          $ext = $file->getClientOriginalExtension();
          // ファイル保存用トークン発行
          $file_token = str_random(32);
          // 画像ファイル名作成
          $pictureFile = $file_token . "." . $ext;
          // 画像ファイル名指定
          $form['picture'] = $pictureFile;
          // 画像ファイルをstorage保存
          $request->picture->storeAs('public/book_images', $pictureFile);
        }
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
      // 画像保存ディレクトリ設定
      $save_directory = "book_images";
      // 削除レコード取得
      $delete_book = Bookdata::find($id);
      // 開発環境で画像保存先を変更
      // if ( app()->isLocal() || app()->runningUnitTests() ) {
      if ( app()->runningUnitTests() ) {

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
      // eval(\Psy\sh());
      // レコード削除
      $delete_book->delete();
      return redirect('/book');
    }

    public function find(Request $request)
    {
      return view('book.find', ['input' => '']);
    }

    public function search(Request $request)
    {
      $title = $request->input;
      $books = Bookdata::where('title', 'like', "%{$title}%")->get();
      $param = ['input' => $title, 'books' => $books];
      return view('book.find', $param);
    }
}

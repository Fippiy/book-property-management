<?php

namespace App\Http\Controllers;

use App\User;
use App\Bookdata;
use App\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $property = User::userBook();
        return view('user.index',['user'=>$user, 'books'=>$property]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // 所有書籍を除外して取得
        $notProperties = User::userNothaveBook();
        $msg = '登録書籍を選択して下さい。';
        return view('user.create',['books'=>$notProperties, 'msg'=>$msg]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      // 新規レコード生成
      $property = new Property;
      // リクエストデータ受取
      $form = $request->all();
      // フォームトークン削除
      unset($form['_token']);
      // ユーザー情報追加
      $user = Auth::user()->id;
      $form = $form + array('user_id' => $user);
      // DB保存
      $property->fill($form)->save();
      // 登録完了メッセージ
      $msg = "所有書籍を登録しました。";
      // 次の登録用フォームデータ取得
      // 所有書籍を除外して取得
      $notProperties = User::userNothaveBook();
      return view('user.create',['books'=>$notProperties, 'property'=>$property, 'msg'=>$msg]);
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
        return view('user.show', ['book' => $book]);
      }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function find(Request $request)
    {
      return view('user.find', ['input' => '']);
    }

    public function search(Request $request)
    {
      $title = $request->input;
      $properties = Property::where('user_id', Auth::user()->id)
                        ->join('bookdata','bookdata.id','=','properties.bookdata_id')
                        ->where('title', 'like', "%{$title}%")
                        ->get();
      $param = ['input' => $title, 'books' => $properties];
      return view('user.find', $param);
    }

    public function getLogin(Request $requet)
    {
        $param = ['message' => 'ログインして下さい。'];
        return view('user.login', $param);
    }

    public function postLogin(Request $request)
    {
        $email = $request->email;
        $password = $request->password;
        if (Auth::attempt(['email' => $email,
            'password' => $password])) {
            return redirect()->route('book.index');
        } else {
            $msg = 'ログインに失敗しました。';
        }
        return view('user.login', ['message' => $msg]);
    }
    public function getSignup()
    {
        $param = ['message' => '登録して下さい。'];
        return view('user.signup', $param);
    }
    public function postSignup(Request $data)
    {
        $this->validate($data,[
            'name'=>'required',
            'email'=>'email|required|unique:users',
            'password' => 'required|min:8'
        ]);
        if (User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ])) {
            $msg = '新規登録しました。ログインページよりログインして下さい。';
        } else {
            $msg = '新規登録に失敗しました。';
        }
        return view('user.signup', ['message' => $msg]);
    }
}

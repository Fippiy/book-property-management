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
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        return view('user.edit', ['form' => $property]);
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
      return redirect('/user');
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
      return redirect('/user');
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
                        ->select('properties.id','title','picture','cover')
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

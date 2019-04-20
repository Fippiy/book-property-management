<?php

namespace App\Http\Controllers;

use App\User;
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
      $auth = Auth::user();
      return view('user.index',[ 'auth' => $auth ]);
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
      // 選択ページ情報取得
      $page = $request->page;
      // 選択ページでバリデーションを選ぶ
      if ($page == 'name'){
        $rule = User::$editNameRules;
      } elseif ($page == 'email'){
        $rule = User::$editEmailRules;
      } elseif ($page == 'password'){
        $rule = User::$editPasswordRules;
      }
      // バリデーションチェック
      $this->validate($request, $rule);
      // 対象レコード取得
      $auth = User::find($id);
      // リクエストデータ受取
      $form = $request->all();
      // フォームトークン削除
      unset($form['_token']);
      // ページ情報削除
      unset($form['page']);
      // パスワードハッシュ化
      if (isset($form['password'])) {
        $form['password'] = Hash::make($form['password']);
      }
      // レコードアップデート
      $auth->fill($form)->save();
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
      //
    }
    public function useredit($page)
    {
      $auth = Auth::user();
      return view('user.edit',[ 'auth' => $auth, 'page' => $page ]);
    }
}

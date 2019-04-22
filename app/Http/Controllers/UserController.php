<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Validator;
use Mail;
use DB;

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
      // パスワード照合
      if ($page == 'password') {
        // 旧パスワードチェック
        $passcheck = Hash::check($form['old_password'], $auth->password);
        // old_passwordにチェック結果をいれて、バリデーションチェックする
        $validator = Validator::make(['old_password' => $passcheck], ['old_password' => 'accepted'], ['現在のパスワードが一致しません']);
        // NG時にエラーとして処理をかえす
        if ($validator->fails()) {
          return redirect('user/password')
                      ->withErrors($validator)
                      ->withInput();
        }
        // 新パスワードハッシュ化
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
    public function userEmailEdit()
    {
      $auth = Auth::user();
      return view('user.email',[ 'auth' => $auth ]);
    }
    public function userEmailChange(Request $request)
    {
      // バリデーションチェック
      $this->validate($request, User::$editEmailRules);
      // 対象レコード取得
      $auth = Auth::user();
      // リクエストデータ受取
      $new_email = $request->input('email');
      // 同じメールアドレスで変更中ステータスがないか確認
      // あれば、古い変更中データは削除
      //
      // メール照合用トークン生成
      $update_token = hash_hmac(
        'sha256',
        str_random(40).$new_email,
        env('APP_KEY')
      );
      // $domain = env('APP_DOMAIN');
      //
      // 変更データ一時保存DBへレコード保存
      DB::table('change_email')->insert([
        [
            'user_id' => $auth->id,
            'new_email' => $new_email,
            'update_token' => $update_token
        ]
      ]);
      //
      // eval(\Psy\sh());
      // メール送付
      // !!!!一時保存DBのデータを引き渡してメールをおくる
      $user = Auth::user();
      $user['token'] = $update_token;
      // eval(\Psy\sh());
      // resources/views/vendor/notifications/email.blade.php
      Mail::send('index', ['user' => $user], function ($message) use ($user, $new_email, $update_token) {
          // $message->priority($level);
          $message->from('hello@app.com', 'Your Application');
          $message->to($new_email)->subject('Your Reminder!');
      });
      // Mail::raw('test mail',function($message) {$message->to('fippiy04@gmail.com')->subject('test');});
      return redirect('user');
    // return [$auth, $form];
    }
    public function userEmailUpdate(Request $request)
    {
      // メールからのアクセス
      // トークン受け取り
      // トークン照合
      // 照合一致で一時保存DBのメールアドレスをDBメールアドレスに上書
      // 一時保存DBレコード削除
      // 変更完了通知
    }
}

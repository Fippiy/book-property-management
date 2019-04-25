<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;
use Illuminate\Support\Facades\Auth;
use Notification;
use App\Notifications\CustomResetPassword;
use Illuminate\Support\Facades\Hash;

class UserTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic feature test example.
     *
     * @return void
     */

     // ログイン時のルート確認
    public function test_loginRoute()
    {
        // トップページにアクセス
        $response = $this->get('/'); // ルートにアクセス
        $response->assertStatus(200); // 200ステータスであること

        // ログイン
        // ログインせずに/userにアクセス
        $response = $this->get('/user'); // ~/userにアクセス
        $response->assertStatus(302);  // 302ステータスであること

        // ログインして/userにアクセス
        $user = factory(User::class)->create(); // User作成
        $response = $this->actingAs($user)->get('/user'); // 作成ユーザーでログインして~/userにアクセス
        $response->assertStatus(200); // 200ステータスであること

        // ページなしアドレス
        $response = $this->get('/no_route'); // ページのないアドレスへアクセス
        $response->assertStatus(404); // 404ステータスであること
    }

    // DBが扱えることの確認
    public function test_databaseFirstCheck()
    {
        factory(User::class)->create([
            'name' => 'AAA',
            'email' => 'BBB@CCC.COM',
            'password' => 'ABCABC',
        ]); // DBに配列で指定したユーザーを生成
        factory(User::class, 10)->create(); // UserFactory指定のユーザーを10レコード作成

        $this->assertDatabaseHas('users', [
            'name' => 'AAA',
            'email' => 'BBB@CCC.COM',
            'password' => 'ABCABC',
        ]); //DBに配列で指定したユーザーがいること
    }

    // ログイン成功
    public function test_login_ok()
    {
      $user = factory(User::class)->create(); // ユーザーを作成

      $this->assertFalse(Auth::check()); // Auth認証前であることを確認

      $response = $this->post('login', [
          'email'    => $user->email,
          'password' => '12345678'
      ]); // ログイン実施 正しい情報のユーザー

      $this->assertTrue(Auth::check()); // Auth認証後であることを確認
      $response->assertSessionHasNoErrors(); // エラーメッセージがないこと

      $response->assertRedirect('book'); // ログイン後にリダイレクトされるのを確認
    }

    // ログイン失敗パスワード未入力
    public function test_login_ng_password_notInput()
    {
      $user = factory(User::class)->create(); // ユーザーを作成

      $this->assertFalse(Auth::check()); // Auth認証前であることを確認
  
      $response = $this->post('login', [
          'email'    => $user->email,
          'password' => ''
      ]); // ログイン実施 パスワード未入力
  
      $this->assertFalse(Auth::check()); // Auth認証されていないことを確認
      $response->assertSessionHasErrors(['password']); // passwordエラーを確認
      $this->assertEquals('パスワードは必須です。',
      session('errors')->first('password')); // エラメッセージを確認
    }

    // ログイン失敗パスワードアンマッチ
    public function test_login_ng_password_unMatch()
    {
      $user = factory(User::class)->create(); // ユーザーを作成

      $this->assertFalse(Auth::check()); // Auth認証前であることを確認
  
      $response = $this->post('login', [
          'email'    => $user->email,
          'password' => '123456789'
      ]); // ログイン実施 パスワードが異なるユーザー
  
      $this->assertFalse(Auth::check()); // Auth認証されていないことを確認
      $test = $response->assertSessionHasErrors(['email']); // emailエラーを確認
      $this->assertEquals('認証情報が記録と一致しません。',
      session('errors')->first('email')); // エラメッセージを確認
    }

    // ログイン失敗email未入力
    public function test_login_ng_email_notInput()
    {
      $user = factory(User::class)->create(); // ユーザーを作成

      $this->assertFalse(Auth::check()); // Auth認証前であることを確認
  
      $response = $this->post('login', [
          'email'    => '',
          'password' => '12345678'
      ]); // ログイン実施 名前が異なるユーザー
  
      $this->assertFalse(Auth::check()); // Auth認証されていないことを確認
      $response->assertSessionHasErrors(['email']); // emailエラーを確認

      $this->assertEquals('メールアドレスは必須です。',
      session('errors')->first('email')); // エラメッセージを確認
    }

    // ログイン失敗emailアンマッチ
    public function test_login_ng_email_unMatch()
    {
      $user = factory(User::class)->create(); // ユーザーを作成

      $this->assertFalse(Auth::check()); // Auth認証前であることを確認
  
      $response = $this->post('login', [
          'email'    => 'test@test.com',
          'password' => '12345678'
      ]); // ログイン実施 名前が異なるユーザー
  
      $this->assertFalse(Auth::check()); // Auth認証されていないことを確認
      $response->assertSessionHasErrors(['email']); // emailエラーを確認

      $this->assertEquals('認証情報が記録と一致しません。',
      session('errors')->first('email')); // エラメッセージを確認
    }

    // リセットパスワード成功
    public function test_resetPassword_ok()
    {
      Notification::fake(); // 通知機能使用、実際には通知しない

      $response = $this->get('password/reset'); // パスワードリセットページにアクセス    
      $this->assertFalse(Auth::check()); // Auth認証されていないことを確認
      $response->assertStatus(200); // 正常表示されること

      $user = factory(User::class)->create(); // テストユーザー準備
 
      $response = $this->from('password/email')->post('password/email', [
          'email' => $user->email,
      ]); // リセットをリクエスト

      $response->assertStatus(302); // リダイレクトされること
      $response->assertRedirect('password/email'); // 同画面にリダイレクト

      $response->assertSessionHas('status',
         'パスワード再設定用のURLをメールで送りました。'); // 成功のメッセージ

      $token = '';
      Notification::assertSentTo(
          $user,
          CustomResetPassword::class,
          function ($notification, $channels) use ($user, &$token) {
              $token = $notification->token;
              return true;
          }
      ); // メール通知を実施(fackにより実際にメールはしない)

      $response = $this->get('password/reset/'.$token); // リセットURLページへアクセス
      $response->assertStatus(200); // アクセスできること
 
      $new = '87654321'; // 新しいパスワード
      $response = $this->post('password/reset', [
         'email'                 => $user->email,
         'token'                 => $token,
         'password'              => $new,
         'password_confirmation' => $new
       ]); // 新パスワードで登録

     $response->assertStatus(302); // リダイレクト
     $response->assertRedirect('/login');  // ログインページに遷移

     $this->assertTrue(Auth::check()); // 認証されていることを確認
     $response->assertSessionHasNoErrors(); // エラーメッセージがないこと

     $this->assertTrue(Hash::check($new, $user->fresh()->password)); // 変更されたパスワードが保存されていることを確認
    }

    // リセットパスワード、リクエストフォーム、登録メールなし
    public function test_resetPassword_ng_email_notWhere()
    {
      Notification::fake(); // 通知機能使用、実際には通知しない

      $response = $this->get('password/reset'); // パスワードリセットページにアクセス    
      $this->assertFalse(Auth::check()); // Auth認証されていないことを確認
      $response->assertStatus(200); // 正常表示されること

      $user = factory(User::class)->create(); // テストユーザー準備

      $response = $this->from('password/email')->post('password/email', [
          'email' => 'test@test.com',
      ]); // リセットをリクエスト

      $response->assertStatus(302); // リダイレクトされること
      $response->assertRedirect('password/email'); // 同画面にリダイレクト

      $response->assertSessionHasErrors(['email']); // emailエラーを確認

      $this->assertEquals('メールアドレスに一致するユーザーが存在しません。',
      session('errors')->first('email')); // エラメッセージを確認
    }

    // リセットパスワード、再登録、トークンアンマッチ
    public function test_resetPassword_ng_token_unMatch()
    {
      Notification::fake(); // 通知機能使用、実際には通知しない

      $response = $this->get('password/reset'); // パスワードリセットページにアクセス    
      $this->assertFalse(Auth::check()); // Auth認証されていないことを確認
      $response->assertStatus(200); // 正常表示されること

      $user = factory(User::class)->create(); // テストユーザー準備
 
      $response = $this->from('password/email')->post('password/email', [
          'email' => $user->email,
      ]); // リセットをリクエスト

      $response->assertStatus(302); // リダイレクトされること
      $response->assertRedirect('password/email'); // 同画面にリダイレクト

      $response->assertSessionHas('status',
         'パスワード再設定用のURLをメールで送りました。'); // 成功のメッセージ

      $token = '';
      Notification::assertSentTo(
          $user,
          CustomResetPassword::class,
          function ($notification, $channels) use ($user, &$token) {
              $token = $notification->token;
              return true;
          }
      ); // メール通知を実施(fackにより実際にメールはしない)

      $token = 'testtoken'; // 誤ったトークンで処理
      $response = $this->get('password/reset/'.$token); // リセットURLページへアクセス
      $response->assertStatus(200); // アクセスできること
 
      $new = 'reset1111'; // 新しいパスワード
      $response = $this->post('password/reset', [
         'email'                 => $user->email,
         'token'                 => $token,
         'password'              => $new,
         'password_confirmation' => $new
       ]); // 新パスワードで登録

     $response->assertStatus(302); // リダイレクト
     $response->assertRedirect('/password/email');  // 登録ページに遷移

     $this->assertFalse(Auth::check()); // 認証されていないこと確認

     $response->assertSessionHasErrors(['email']); // emailエラーを確認
     $this->assertEquals('パスワード再設定用のトークンが不正です。',
     session('errors')->first('email')); // エラメッセージを確認

     $this->assertFalse(Hash::check($new, $user->fresh()->password)); // 新しいパスワードが保存されていないことを確認

     $this->assertDatabaseHas('users', [
      'name' => $user->name,
      'email' => $user->email,
      'password' => $user->password,
     ]); // ユーザーが変更されていないことを確認
    } 

    // リセットパスワード、再登録、メール未入力
    public function test_resetPassword_ng_email_notInput()
    {
      Notification::fake(); // 通知機能使用、実際には通知しない

      $response = $this->get('password/reset'); // パスワードリセットページにアクセス    
      $this->assertFalse(Auth::check()); // Auth認証されていないことを確認
      $response->assertStatus(200); // 正常表示されること

      $user = factory(User::class)->create(); // テストユーザー準備
 
      $response = $this->from('password/email')->post('password/email', [
          'email' => $user->email,
      ]); // リセットをリクエスト

      $response->assertStatus(302); // リダイレクトされること
      $response->assertRedirect('password/email'); // 同画面にリダイレクト

      $response->assertSessionHas('status',
         'パスワード再設定用のURLをメールで送りました。'); // 成功のメッセージ

      $token = '';
      Notification::assertSentTo(
          $user,
          CustomResetPassword::class,
          function ($notification, $channels) use ($user, &$token) {
              $token = $notification->token;
              return true;
          }
      ); // メール通知を実施(fackにより実際にメールはしない)

      $response = $this->get('password/reset/'.$token); // リセットURLページへアクセス
      $response->assertStatus(200); // アクセスできること

      $email = ''; // メール未入力
      $new = '87654321'; // 新しいパスワード
      $response = $this->post('password/reset', [
         'email'                 => $email,
         'token'                 => $token,
         'password'              => $new,
         'password_confirmation' => $new
       ]); // 新パスワードで登録

      $response->assertStatus(302); // リダイレクト
      $response->assertRedirect('/password/email');  // 登録ページに遷移

      $this->assertFalse(Auth::check()); // 認証されていないこと確認

      $response->assertSessionHasErrors(['email']); // emailエラーを確認
      $this->assertEquals('メールアドレスは必須です。',
      session('errors')->first('email')); // エラメッセージを確認

      $this->assertFalse(Hash::check($new, $user->fresh()->password)); // 新しいパスワードが保存されていないことを確認

      $this->assertDatabaseHas('users', [
        'name' => $user->name,
        'email' => $user->email,
        'password' => $user->password,
      ]); // ユーザーが変更されていないことを確認
    } 

    // リセットパスワード、再登録、メールアンマッチ
    public function test_resetPassword_ng_email_unMatch()
    {
      Notification::fake(); // 通知機能使用、実際には通知しない

      $response = $this->get('password/reset'); // パスワードリセットページにアクセス    
      $this->assertFalse(Auth::check()); // Auth認証されていないことを確認
      $response->assertStatus(200); // 正常表示されること

      $user = factory(User::class)->create(); // テストユーザー準備
 
      $response = $this->from('password/email')->post('password/email', [
          'email' => $user->email,
      ]); // リセットをリクエスト

      $response->assertStatus(302); // リダイレクトされること
      $response->assertRedirect('password/email'); // 同画面にリダイレクト

      $response->assertSessionHas('status',
         'パスワード再設定用のURLをメールで送りました。'); // 成功のメッセージ

      $token = '';
      Notification::assertSentTo(
          $user,
          CustomResetPassword::class,
          function ($notification, $channels) use ($user, &$token) {
              $token = $notification->token;
              return true;
          }
      ); // メール通知を実施(fackにより実際にメールはしない)

      $response = $this->get('password/reset/'.$token); // リセットURLページへアクセス
      $response->assertStatus(200); // アクセスできること

      $email = 'test@test.com'; // 別のメールで入力
      $new = 'reset1111'; // 新しいパスワード
      $response = $this->post('password/reset', [
         'email'                 => $email,
         'token'                 => $token,
         'password'              => $new,
         'password_confirmation' => $new
       ]); // 新パスワードで登録

      $response->assertStatus(302); // リダイレクト
      $response->assertRedirect('/password/email');  // 登録ページに遷移

      $this->assertFalse(Auth::check()); // 認証されていないこと確認

      $response->assertSessionHasErrors(['email']); // emailエラーを確認
      $this->assertEquals('メールアドレスに一致するユーザーが存在しません。',
      session('errors')->first('email')); // エラメッセージを確認

      $this->assertFalse(Hash::check($new, $user->fresh()->password)); // 新しいパスワードが保存されていないことを確認

      $this->assertDatabaseHas('users', [
        'name' => $user->name,
        'email' => $user->email,
        'password' => $user->password,
      ]); // ユーザーが変更されていないことを確認
    } 

    // リセットパスワード、再登録、新パスワード未入力
    public function test_resetPassword_ng_newPassword_null()
    {
      Notification::fake(); // 通知機能使用、実際には通知しない

      $response = $this->get('password/reset'); // パスワードリセットページにアクセス    
      $this->assertFalse(Auth::check()); // Auth認証されていないことを確認
      $response->assertStatus(200); // 正常表示されること

      $user = factory(User::class)->create(); // テストユーザー準備
 
      $response = $this->from('password/email')->post('password/email', [
          'email' => $user->email,
      ]); // リセットをリクエスト

      $response->assertStatus(302); // リダイレクトされること
      $response->assertRedirect('password/email'); // 同画面にリダイレクト

      $response->assertSessionHas('status',
         'パスワード再設定用のURLをメールで送りました。'); // 成功のメッセージ

      $token = '';
      Notification::assertSentTo(
          $user,
          CustomResetPassword::class,
          function ($notification, $channels) use ($user, &$token) {
              $token = $notification->token;
              return true;
          }
      ); // メール通知を実施(fackにより実際にメールはしない)

      $response = $this->get('password/reset/'.$token); // リセットURLページへアクセス
      $response->assertStatus(200); // アクセスできること
 
      $new = ''; // 新しい空パスワード
      $response = $this->post('password/reset', [
         'email'                 => $user->email,
         'token'                 => $token,
         'password'              => $new,
         'password_confirmation' => $new
       ]); // 新パスワードで登録

      $response->assertStatus(302); // リダイレクト
      $response->assertRedirect('/password/email');  // 登録ページに遷移

      $this->assertFalse(Auth::check()); // 認証されていないこと確認

      $response->assertSessionHasErrors(['password']); // emailエラーを確認
      $this->assertEquals('パスワードは必須です。',
      session('errors')->first('password')); // エラメッセージを確認

      $this->assertFalse(Hash::check($new, $user->fresh()->password)); // 新しいパスワードが保存されていないことを確認

      $this->assertDatabaseHas('users', [
        'name' => $user->name,
        'email' => $user->email,
        'password' => $user->password,
       ]); // ユーザーが変更されていないことを確認
    }

    // リセットパスワード、再登録、パスワード7文字以下
    public function test_resetPassword_ng_newPassword_short()
    {
      Notification::fake(); // 通知機能使用、実際には通知しない

      $response = $this->get('password/reset'); // パスワードリセットページにアクセス    
      $this->assertFalse(Auth::check()); // Auth認証されていないことを確認
      $response->assertStatus(200); // 正常表示されること

      $user = factory(User::class)->create(); // テストユーザー準備
 
      $response = $this->from('password/email')->post('password/email', [
          'email' => $user->email,
      ]); // リセットをリクエスト

      $response->assertStatus(302); // リダイレクトされること
      $response->assertRedirect('password/email'); // 同画面にリダイレクト

      $response->assertSessionHas('status',
         'パスワード再設定用のURLをメールで送りました。'); // 成功のメッセージ

      $token = '';
      Notification::assertSentTo(
          $user,
          CustomResetPassword::class,
          function ($notification, $channels) use ($user, &$token) {
              $token = $notification->token;
              return true;
          }
      ); // メール通知を実施(fackにより実際にメールはしない)

      $response = $this->get('password/reset/'.$token); // リセットURLページへアクセス
      $response->assertStatus(200); // アクセスできること
 
      $new = '7654321'; // 新しい7文字パスワード
      $response = $this->post('password/reset', [
         'email'                 => $user->email,
         'token'                 => $token,
         'password'              => $new,
         'password_confirmation' => $new
       ]); // 新パスワードで登録

      $response->assertStatus(302); // リダイレクト
      $response->assertRedirect('/password/email');  // 登録ページに遷移

      $this->assertFalse(Auth::check()); // 認証されていないこと確認

      $response->assertSessionHasErrors(['password']); // emailエラーを確認
      $this->assertEquals('パスワードは8文字以上にしてください。',
      session('errors')->first('password')); // エラメッセージを確認

      $this->assertFalse(Hash::check($new, $user->fresh()->password)); // 新しいパスワードが保存されていないことを確認

      $this->assertDatabaseHas('users', [
        'name' => $user->name,
        'email' => $user->email,
        'password' => $user->password,
       ]); // ユーザーが変更されていないことを確認
    }

    // リセットパスワード、再登録、パスワード（確認）不一致
    public function test_resetPassword_ng_confirmationPassword_unMatch()
    {
      Notification::fake(); // 通知機能使用、実際には通知しない

      $response = $this->get('password/reset'); // パスワードリセットページにアクセス    
      $this->assertFalse(Auth::check()); // Auth認証されていないことを確認
      $response->assertStatus(200); // 正常表示されること

      $user = factory(User::class)->create(); // テストユーザー準備
 
      $response = $this->from('password/email')->post('password/email', [
          'email' => $user->email,
      ]); // リセットをリクエスト

      $response->assertStatus(302); // リダイレクトされること
      $response->assertRedirect('password/email'); // 同画面にリダイレクト

      $response->assertSessionHas('status',
         'パスワード再設定用のURLをメールで送りました。'); // 成功のメッセージ

      $token = '';
      Notification::assertSentTo(
          $user,
          CustomResetPassword::class,
          function ($notification, $channels) use ($user, &$token) {
              $token = $notification->token;
              return true;
          }
      ); // メール通知を実施(fackにより実際にメールはしない)

      $response = $this->get('password/reset/'.$token); // リセットURLページへアクセス
      $response->assertStatus(200); // アクセスできること
 
      $new = '87654321'; // 新しいパスワード
      $newconf = '987654321'; // 確認用パスワード
      $response = $this->post('password/reset', [
         'email'                 => $user->email,
         'token'                 => $token,
         'password'              => $new,
         'password_confirmation' => $newconf
       ]); // 新パスワードで登録（確認パスワード不一致）

      $response->assertStatus(302); // リダイレクト
      $response->assertRedirect('/password/email');  // 登録ページに遷移

      $this->assertFalse(Auth::check()); // 認証されていないこと確認

      $response->assertSessionHasErrors(['password']); // emailエラーを確認
      $this->assertEquals('パスワードは確認用項目と一致していません。',
      session('errors')->first('password')); // エラメッセージを確認

      $this->assertFalse(Hash::check($new, $user->fresh()->password)); // 新しいパスワードが保存されていないことを確認

      $this->assertDatabaseHas('users', [
        'name' => $user->name,
        'email' => $user->email,
        'password' => $user->password,
       ]); // ユーザーが変更されていないことを確認
    }

}

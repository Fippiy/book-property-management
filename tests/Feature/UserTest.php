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
    public function testLoginRoute()
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
    public function testDatabaseFirstCheck()
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
    public function testLoginOK()
    {
    $user = factory(User::class)->create(); // ユーザーを作成

    $this->assertFalse(Auth::check()); // Auth認証前であることを確認

    $response = $this->post('login', [
        'email'    => $user->email,
        'password' => '12345678'
    ]); // ログイン実施 正しい情報のユーザー

    $this->assertTrue(Auth::check()); // Auth認証後であることを確認

    $response->assertRedirect('book'); // ログイン後にリダイレクトされるのを確認
    }

    // ログイン失敗パスワードNG
    public function testLoginNotPassNg()
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

    // ログイン失敗emailNG
    public function testLoginNotNameNg()
    {
        $user = factory(User::class)->create(); // ユーザーを作成

        $this->assertFalse(Auth::check()); // Auth認証前であることを確認
    
        $response = $this->post('login', [
            'email'    => 'testuser',
            'password' => '12345678'
        ]); // ログイン実施 名前が異なるユーザー
    
        $this->assertFalse(Auth::check()); // Auth認証されていないことを確認
        $response->assertSessionHasErrors(['email']); // emailエラーを確認

        $this->assertEquals('認証情報が記録と一致しません。',
        session('errors')->first('email')); // エラメッセージを確認
    }

    // リセットパスワード成功
    public function testResetPasswordOk()
    {
      Notification::fake(); // 通知機能使用、実際には通知しない

      $response = $this->get('password/reset'); // パスワードリセットページにアクセス    
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
 
      $new = 'reset1111'; // 新しいパスワード
      $response = $this->post('password/reset', [
         'email'                 => $user->email,
         'token'                 => $token,
         'password'              => $new,
         'password_confirmation' => $new
       ]); // 新パスワードで登録

     $response->assertStatus(302); // リダイレクト
     $response->assertRedirect('/login');  // ログインページに遷移

     $this->assertTrue(Auth::check()); // 認証されていることを確認

     $this->assertTrue(Hash::check($new, $user->fresh()->password)); // 変更されたパスワードが保存されていることを確認
    }
}

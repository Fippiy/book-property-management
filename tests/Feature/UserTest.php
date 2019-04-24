<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;
use Illuminate\Support\Facades\Auth;

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

    // ログイン成功テスト
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

    // ログイン失敗テスト
    public function testLoginNgNotPass()
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
    public function testLoginNgNotName()
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
}

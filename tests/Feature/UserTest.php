<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;

class UserTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAccess()
    {
        $this->assertTrue(true);

        $response = $this->get('/'); // ルートにアクセス
        $response->assertStatus(200); // 200ステータスであること

        $response = $this->get('/user'); // ~/userにアクセス
        $response->assertStatus(302);  // 302ステータスであること

        $user = factory(User::class)->create(); // User作成
        $response = $this->actingAs($user)->get('/user'); // 作成ユーザーでログインして~/userにアクセス
        $response->assertStatus(200); // 200ステータスであること

        $response = $this->get('/no_route'); // ページのないアドレスへアクセス
        $response->assertStatus(404); // 404ステータスであること
    }
    public function testDatabase()
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
}

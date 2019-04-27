<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;
use App\Bookdata;
use Illuminate\Support\Facades\Auth;

class BookdataTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic feature test example.
     *
     * @return void
     */

     // トップページアクセス確認
    public function test_indexAccess_ok()
    {
        $user = factory(User::class)->create(); // ユーザーを作成
        $this->actingAs($user); // ログイン済み
        $this->assertTrue(Auth::check()); // Auth認証済であることを確認

        $response = $this->get('/book'); // bookへアクセス
        $response->assertStatus(200); // 200ステータスであること
    }

     // 手動登録都確認
     // 写真考慮必要
     public function test_bookCreate_ok()
    {
        $user = factory(User::class)->create(); // ユーザーを作成
        $this->actingAs($user); // ログイン済み
        $this->assertTrue(Auth::check()); // Auth認証済であることを確認

        $response = $this->get('/book/create'); // bookへアクセス
        $response->assertStatus(200); // 200ステータスであること

        // アップロード処理

        // テキストとアップロードデータを保存
        $bookdata = [
            'title' => '本タイトル1',
            // 写真は実際にあるのをアップロードしないとNG
            // 'picture' => '/public/image/no-entry.jpg',
            'detail' => '詳細',
        ];
        $response = $this->from('book/create')->post('book', $bookdata); // 本情報保存

        // $check = Bookdata::all();
        // eval(\Psy\sh());
        // 写真NG時の確認
        // $response->assertSessionHasErrors(['picture']); // エラーを確認
        // $this->assertEquals('validation.file',
        // session('errors')->first('picture')); // エラメッセージを確認
        // $response->assertStatus(302); // リダイレクト
        // $response->assertRedirect('/book/create');  // 
  
        $response->assertStatus(302); // リダイレクト
        $response->assertRedirect('/book');  // 

        $response = $this->get('book'); // bookへアクセス
        $response->assertViewIs('book.index'); // book.indexビューであること
        $response->assertSeeText($bookdata['title']); // bookdataタイトルが表示されていること
    }
    // isbn登録と表示確認
    public function test_isbnCreate_ok()
    {
        $user = factory(User::class)->create(); // ユーザーを作成
        $this->actingAs($user); // ログイン済み
        $this->assertTrue(Auth::check()); // Auth認証済であることを確認

        $response = $this->get('/book/isbn'); // bookへアクセス
        $response->assertStatus(200); // 200ステータスであること

        $isbn = ['isbn' => 9784798052588];
        $response = $this->from('book/isbn')->post('book/isbn', $isbn); // isbn情報保存
        $response->assertSessionHasNoErrors(); // エラーメッセージがないこと
        $response->assertStatus(200); // 200ステータスであること
        $response->assertSeeText('データを新規作成しました');
        
        $savebook = Bookdata::all()->first();

        $response = $this->get('book'); // bookへアクセス
        $response->assertViewIs('book.index'); // book.indexビューであること
        $response->assertSeeText($savebook['title']); // bookdataタイトルが表示されていること

        $response = $this->get('book/'.$savebook['id']); // 指定bookへアクセス
        $response->assertViewIs('book.show'); // book.showビューであること
        foreach ($savebook as $value)
        {
            $response->assertSeeText($value);
        }; // savebookデータが表示されていること
    }
    // 編集できること

    // 削除できること
    // 写真考慮必要

    // 検索できること
}

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

     // 手動登録確認
     // 写真なしパターン
     public function test_bookControll_ok()
    {
        //// ユーザー生成
        $user = factory(User::class)->create(); // ユーザーを作成
        $this->actingAs($user); // ログイン済み
        $this->assertTrue(Auth::check()); // Auth認証済であることを確認

        //// createページアクセス
        $response = $this->get('/book/create'); // createへアクセス
        $response->assertStatus(200); // 200ステータスであること
        $response->assertViewIs('book.create'); // book.createビューであること

        //// 登録
        $bookdata = [
            'title' => 'テストブック',
            'detail' => '詳細はこちら',
        ];
        $response = $this->from('book/create')->post('book', $bookdata); // 本情報保存
        $response->assertSessionHasNoErrors(); // エラーメッセージがないこと
        $response->assertStatus(302); // リダイレクト
        $response->assertRedirect('/book');  // トップページ表示

        // 登録されていることの確認(indexページ)
        $response = $this->get('book'); // bookへアクセス
        $response->assertStatus(200); // 200ステータスであること
        $response->assertViewIs('book.index'); // book.indexビューであること
        $response->assertSeeText($bookdata['title']); // 登録タイトルが表示されていること

        // 詳細ページで表示されること
        $savebook = Bookdata::all()->first(); // 保存情報確認
        $response = $this->get('book/'.$savebook['id']); // 指定bookへアクセス
        $response->assertStatus(200); // 200ステータスであること
        $response->assertViewIs('book.show'); // book.showビューであること
        foreach ($savebook as $value)
        {
            $response->assertSeeText($value);
        }; // savebookデータが表示されていること

        //// 編集
        // 写真なしパターン
        $edit_post = 'book/'.$savebook['id']; // 編集パス
        $response = $this->get($edit_post.'/edit'); // 編集ページへアクセス
        $response->assertStatus(200); // 200ステータスであること
        $response->assertViewIs('book.edit'); // book.editビューであること

        // 編集内容
        $editbookdata = [
            'title' => 'テストブック編集',
            'detail' => 'テストとして変更した詳細',
            '_method' => 'PUT',
        ];
        $response = $this->from($edit_post.'/edit')->post($edit_post, $editbookdata); // 編集実施
        $response->assertSessionHasNoErrors(); // エラーメッセージがないこと
        $response->assertStatus(302); // リダイレクト
        $response->assertRedirect('/book');  // トップページ表示

        // 編集されていることの確認(indexページ)
        $response = $this->get('book'); // bookへアクセス
        $response->assertStatus(200); // 200ステータスであること
        $response->assertViewIs('book.index'); // book.indexビューであること
        $response->assertSeeText($editbookdata['title']); // 編集タイトルが表示されていること

        //// 削除
        // 写真なしパターン
        $response = $this->from('book/'.$savebook['id'])->post('book/'.$savebook['id'], [
            '_method' => 'DELETE',
            ]); // 削除実施
        $response->assertSessionHasNoErrors(); // エラーメッセージがないこと
        $response->assertStatus(302); // リダイレクト
        $response->assertRedirect('/book');  // トップページ表示

        // 削除されていることの確認(indexページ)
        $response = $this->get('book'); // bookへアクセス
        $response->assertStatus(200); // 200ステータスであること
        $response->assertViewIs('book.index'); // book.indexビューであること
        $response->assertDontSeeText($editbookdata['title']); // 削除タイトルが表示されていないこと
    }
    // isbn登録と表示確認
    public function test_isbnCreate_ok()
    {
        //// ユーザー生成
        $user = factory(User::class)->create(); // ユーザーを作成
        $this->actingAs($user); // ログイン済み
        $this->assertTrue(Auth::check()); // Auth認証済であることを確認

        // isbnページアクセス
        $response = $this->get('/book/isbn'); // isbnへアクセス
        $response->assertStatus(200); // 200ステータスであること
        $response->assertViewIs('book.isbn'); // book.isbnビューであること

        // 登録
        $isbn = ['isbn' => 9784798052588]; // 新規登録コード
        $response = $this->from('book/isbn')->post('book/isbn', $isbn); // isbn情報保存
        $response->assertSessionHasNoErrors(); // エラーメッセージがないこと
        $response->assertStatus(200); // 200ステータスであること
        $response->assertSeeText('データを新規作成しました'); // メッセージが出力されていること
        
        // 登録されていることの確認(indexページ)
        $savebook = Bookdata::all()->first(); // 保存されたデータを取得
        $response = $this->get('book'); // bookへアクセス
        $response->assertStatus(200); // 200ステータスであること
        $response->assertViewIs('book.index'); // book.indexビューであること
        $response->assertSeeText($savebook['title']); // bookdataタイトルが表示されていること

        // 詳細ページで表示されること
        $response = $this->get('book/'.$savebook['id']); // 指定bookへアクセス
        $response->assertStatus(200); // 200ステータスであること
        $response->assertViewIs('book.show'); // book.showビューであること
        foreach ($savebook as $value)
        {
            $response->assertSeeText($value);
        }; // savebookデータが表示されていること
    }
}

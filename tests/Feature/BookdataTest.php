<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;
use App\Bookdata;
use App\Property;
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
        $response->assertStatus(200); // 200ステータスであること

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
    // 検索
    public function test_findTitle_ok_yesMatchFindTitle()
    {
        //// ユーザー生成
        $user = factory(User::class)->create(); // ユーザーを作成
        $this->actingAs($user); // ログイン済み
        $this->assertTrue(Auth::check()); // Auth認証済であることを確認

        // faker book自動生成
        $bookdata = factory(Bookdata::class)->create();

        //// 検索
        // 検索の実施(findページ)
        $find_post = 'book/find'; // 検索パス
        $savebook = Bookdata::all()->first(); // 保存されたデータを取得
        $response = $this->get($find_post); // 検索ページへアクセス
        $response->assertStatus(200); // 200ステータスであること
        $response->assertViewIs('book.find'); // book.findビューであること
        $response = $this->from($find_post)->post($find_post, ['find' => $bookdata->title]); // 検索実施
        $response->assertSessionHasNoErrors(); // エラーメッセージがないこと
        $response->assertStatus(200); // 200ステータスであること
        $response->assertSeeText($bookdata->title); // bookdataタイトルが表示されていること
    }
    public function test_findTitle_ok_noMatchFindTitle()
    {
        //// ユーザー生成
        $user = factory(User::class)->create(); // ユーザーを作成
        $this->actingAs($user); // ログイン済み
        $this->assertTrue(Auth::check()); // Auth認証済であることを確認

        // faker book自動生成
        $bookdata = factory(Bookdata::class)->create([
            'title' => 'a'
        ]); // タイトル名aで作成

        //// 検索
        // 検索の実施(findページ)
        $find_post = 'book/find'; // 検索パス
        $savebook = Bookdata::all()->first(); // 保存されたデータを取得
        $response = $this->get($find_post); // 検索ページへアクセス
        $response->assertStatus(200); // 200ステータスであること
        $response->assertViewIs('book.find'); // book.findビューであること
        $response = $this->from($find_post)->post($find_post, ['find' => 'b']); // bで検索実施
        $response->assertSessionHasNoErrors(); // エラーメッセージがないこと
        $response->assertStatus(200); // 200 ステータスであること
        $response->assertViewIs('book.find'); // book.findビューであること
        $response->assertSeeText('書籍がみつかりませんでした。'); // タイトルなしメッセージが表示されていること
    }


    //// NGパターン調査
    // 手動登録タイトル未入力
    public function test_bookControll_ng_notNameEntry()
    {
        //// ユーザー生成
        $user = factory(User::class)->create(); // ユーザーを作成
        $this->actingAs($user); // ログイン済み
        $this->assertTrue(Auth::check()); // Auth認証済であることを確認

        //// 登録
        $bookdata = [
            'title' => '',
            'detail' => '詳細はこちら',
        ];
        $response = $this->from('book/create')->post('book', $bookdata); // 本情報保存
        $response->assertSessionHasErrors(['title']); // エラーメッセージがあること
        $response->assertStatus(302); // リダイレクト
        $response->assertRedirect('book/create');  // 同ページへリダイレクト
        $this->assertEquals('titleは必須です。',
        session('errors')->first('title')); // エラメッセージを確認
    }
    // ISBN登録未入力
    public function test_bookControll_ng_notIsbnEntry()
    {
        //// ユーザー生成
        $user = factory(User::class)->create(); // ユーザーを作成
        $this->actingAs($user); // ログイン済み
        $this->assertTrue(Auth::check()); // Auth認証済であることを確認

        //// 登録
        $bookdata = [
            'isbn' => ''
        ]; // コード未入力
        $response = $this->from('book/isbn')->post('book/isbn', $bookdata); // isbn登録
        $response->assertSessionHasErrors(['isbn']); // エラーメッセージがあること
        $response->assertStatus(302); // リダイレクト
        $response->assertRedirect('book/isbn');  // 同ページ表示
        $this->assertEquals('isbnは必須です。',
        session('errors')->first('isbn')); // エラメッセージを確認
    }
    // ISBN登録桁数誤り小
    public function test_bookControll_ng_IsbnSmallEntry()
    {
        //// ユーザー生成
        $user = factory(User::class)->create(); // ユーザーを作成
        $this->actingAs($user); // ログイン済み
        $this->assertTrue(Auth::check()); // Auth認証済であることを確認

        //// 登録
        $bookdata = [
            'isbn' => '123456789012'
        ]; // 12桁コード
        $response = $this->from('book/isbn')->post('book/isbn', $bookdata); // isbn登録
        $response->assertSessionHasErrors(['isbn']); // エラーメッセージがあること
        $response->assertStatus(302); // リダイレクト
        $response->assertRedirect('book/isbn');  // 同ページ表示
        $this->assertEquals('isbnは13桁にしてください',
        session('errors')->first('isbn')); // エラメッセージを確認
    }
    // ISBN登録桁数誤り大
    public function test_bookControll_ng_IsbnBigEntry()
    {
        //// ユーザー生成
        $user = factory(User::class)->create(); // ユーザーを作成
        $this->actingAs($user); // ログイン済み
        $this->assertTrue(Auth::check()); // Auth認証済であることを確認

        //// 登録
        $bookdata = [
            'isbn' => '12345678901234'
        ]; // 14桁コード
        $response = $this->from('book/isbn')->post('book/isbn', $bookdata); // isbn登録
        $response->assertSessionHasErrors(['isbn']); // エラーメッセージがあること
        $response->assertStatus(302); // リダイレクト
        $response->assertRedirect('book/isbn');  // 同ページ表示
        $this->assertEquals('isbnは13桁にしてください',
        session('errors')->first('isbn')); // エラメッセージを確認
    }
    public function test_bookControll_ng_notIsbnData()
    {
        //// ユーザー生成
        $user = factory(User::class)->create(); // ユーザーを作成
        $this->actingAs($user); // ログイン済み
        $this->assertTrue(Auth::check()); // Auth認証済であることを確認

        //// 登録
        $newbookdata = [
            'isbn' => '1234567890123'
        ]; // 13桁コード
        $response = $this->from('book/isbn')->post('book/isbn', $newbookdata); // isbn登録
        $response->assertSessionHasErrors(['isbn']); // エラーメッセージがあること
        $response->assertStatus(302); // リダイレクト
        $response->assertRedirect('book/isbn');  // 同ページ表示
        $this->assertEquals('該当するISBNコードは見つかりませんでした。',
        session('errors')->first('isbn')); // エラメッセージを確認
    }

    // 書籍情報編集NG、タイトルなし
    public function test_bookControll_ng_notTitleEdit()
    {
        //// ユーザー生成
        $user = factory(User::class)->create(); // ユーザーを作成
        $this->actingAs($user); // ログイン済み
        $this->assertTrue(Auth::check()); // Auth認証済であることを確認

        //// 仮本新規登録
        $bookdata = factory(Bookdata::class)->create(); // 書籍を作成
        $bookpath = 'book/'.$bookdata->id.'/edit'; // 書籍編集パス
        //// 登録
        $editbookdata = [
            'title' => '',
        ]; // タイトルなしに編集
        $response = $this->from($bookpath)->post('book', $editbookdata); // 本情報保存
        $response->assertSessionHasErrors(['title']); // エラーメッセージがあること
        $response->assertStatus(302); // リダイレクト
        $response->assertRedirect($bookpath);  // トップページ表示
        $this->assertEquals('titleは必須です。',
        session('errors')->first('title')); // エラメッセージを確認
    }
    // 書籍情報編集NG、未登録id
    public function test_bookControll_ng_notIdEdit()
    {
        //// ユーザー生成
        $user = factory(User::class)->create(); // ユーザーを作成
        $this->actingAs($user); // ログイン済み
        $this->assertTrue(Auth::check()); // Auth認証済であることを確認

        //// 仮本新規登録
        $bookdata = factory(Bookdata::class)->create(); // 書籍を作成
        $bookpath = 'book/2/edit'; // 書籍編集パス(存在しないID)

        $response = $this->get($bookpath); // ページにアクセス
        $response->assertStatus(500);  // 500ステータスであること
    }
    public function test_bookControll_ng_notIdDelete()
    {
        //// ユーザー生成
        $user = factory(User::class)->create(); // ユーザーを作成
        $this->actingAs($user); // ログイン済み
        $this->assertTrue(Auth::check()); // Auth認証済であることを確認

        //// 仮本新規登録
        $bookdata = factory(Bookdata::class)->create(); // 書籍を作成
        $bookpath = 'book/2'; // 書籍編集パス(存在しないID)

        // アクセス不可
        $response = $this->get($bookpath); // ページにアクセス
        $response->assertStatus(500);  // 500ステータスであること

        //// 削除
        $response = $this->from($bookpath)->post($bookpath, [
            '_method' => 'DELETE',
            ]); // 削除実施
        $response->assertStatus(500);  // 500ステータスであること
    }
    public function test_bookControll_ng_haveProrpertyDelete()
    {
        //// ユーザー生成
        $user = factory(User::class)->create(); // ユーザーを作成
        $this->actingAs($user); // ログイン済み
        $this->assertTrue(Auth::check()); // Auth認証済であることを確認

        //// 仮本新規登録
        $bookdata = factory(Bookdata::class)->create(); // 書籍を作成
        $bookpath = 'book/'.$bookdata->id; // 書籍削除パス

        //// 所有情報登録
        $havebookdata = factory(Property::class)->create(); // 書籍を作成
        //// 削除
        $response = $this->from($bookpath)->post($bookpath, [
            '_method' => 'DELETE',
            ]); // 削除実施
        $response->assertSessionHasErrors(['bookdata_id']); // エラーメッセージがあること
        $response->assertStatus(302); // リダイレクト
        $response->assertRedirect($bookpath);  // 編集ページ表示
        $this->assertEquals('所有者がいるため削除できません',
        session('errors')->first('bookdata_id')); // エラメッセージを確認
    }
    public function test_findTitle_ng_noTitle()
    {
        //// ユーザー生成
        $user = factory(User::class)->create(); // ユーザーを作成
        $this->actingAs($user); // ログイン済み
        $this->assertTrue(Auth::check()); // Auth認証済であることを確認

        //// 検索
        // 検索の実施(findページ)
        $find_post = 'book/find'; // 検索パス
        $savebook = Bookdata::all()->first(); // 保存されたデータを取得
        $response = $this->get($find_post); // 検索ページへアクセス
        $response->assertStatus(200); // 200ステータスであること
        $response->assertViewIs('book.find'); // book.findビューであること
        $response = $this->from($find_post)->post($find_post, ['find' => '']); // 検索実施
        $response->assertSessionHasErrors('find'); // エラーメッセージがあること
        $response->assertStatus(302); // リダイレクト
        $response->assertRedirect('book/find');  // 同ページへリダイレクト
        $this->assertEquals('検索ワードは必須です。',
        session('errors')->first('find')); // エラメッセージを確認
    }
}

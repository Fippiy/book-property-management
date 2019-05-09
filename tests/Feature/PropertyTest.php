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
use Faker\Generator as Faker;

class PropertyTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_indexAccess_ok()
    {
        $user = factory(User::class)->create(); // ユーザーを作成
        $this->actingAs($user); // ログイン済み
        $this->assertTrue(Auth::check()); // Auth認証済であることを確認

        $response = $this->get('/property'); // propertyへアクセス
        $response->assertStatus(200); // 200ステータスであること
    }
    public function test_propertyControll_ok()
    {
        $user = factory(User::class)->create(); // ユーザーを作成
        $this->actingAs($user); // ログイン済み
        $this->assertTrue(Auth::check()); // Auth認証済であることを確認

        //// 書籍情報登録
        factory(Bookdata::class)->create(); // 書籍を作成

        //// createページアクセス
        $response = $this->get('/property/create'); // createへアクセス
        $response->assertStatus(200); // 200ステータスであること
        $response->assertViewIs('property.create'); // book.createビューであること

        //// 所有書籍登録
        $propertydata = [
            'user_id' => 1,
            'bookdata_id' => 1,
            'number' => 1,
            'getdate' => '2000-01-01',
            'freememo' => 'testmemo',
        ];
        $response = $this->from('property/create')->post('property', $propertydata); // 所有書籍保存
        $response->assertSessionHasNoErrors(); // エラーメッセージがないこと
        $response->assertStatus(200); // 200ステータスであること

        $savebook = Property::all()->first(); // 保存されたデータを取得

        // 登録されていることの確認(indexページ)
        $response = $this->get('property'); // bookへアクセス
        $response->assertStatus(200); // 200ステータスであること
        $response->assertViewIs('property.index'); // book.indexビューであること
        $response->assertSeeText($savebook->bookdata->title); // 登録タイトルが表示されていること

        // 詳細ページで表示されること
        // $savebook = Bookdata::all()->first(); // 保存情報確認
        $response = $this->get('property/'.$savebook['id']); // 指定bookへアクセス
        $response->assertStatus(200); // 200ステータスであること
        $response->assertViewIs('property.show'); // property.showビューであること
        foreach ($savebook as $value)
        {
            $response->assertSeeText($value);
        }; // savebookデータが表示されていること

        //// 編集
        $edit_post = 'property/'.$savebook['id']; // 編集パス
        $response = $this->get($edit_post.'/edit'); // 編集ページへアクセス
        $response->assertStatus(200); // 200ステータスであること
        $response->assertViewIs('property.edit'); // property.editビューであること

        // 編集内容
        $editpropertydata = [
            'number' => 2,
            'getdate' => '1900-01-01',
            'freememo' => 'testmemotest',
            '_method' => 'PUT',
        ];
        $response = $this->from($edit_post.'/edit')->post($edit_post, $editpropertydata); // 編集実施
        $response->assertSessionHasNoErrors(); // エラーメッセージがないこと
        $response->assertStatus(302); // リダイレクト
        $response->assertRedirect('/property');  // トップページ表示

        $editproperty = Property::all()->first(); // 編集されたデータを取得

        // 編集されていることの確認(indexページ)
        $response = $this->get('property'); // propertyへアクセス
        $response->assertStatus(200); // 200ステータスであること
        $response->assertViewIs('property.index'); // property.indexビューであること
        $response->assertSeeText($editproperty->bookdata->title); // 編集タイトルが表示されていること

        //// 削除
        $response = $this->from('property/'.$savebook['id'])->post('property/'.$savebook['id'], [
            '_method' => 'DELETE',
            ]); // 削除実施
        $response->assertSessionHasNoErrors(); // エラーメッセージがないこと
        $response->assertStatus(302); // リダイレクト
        $response->assertRedirect('/property');  // トップページ表示

        // 削除されていることの確認(indexページ)
        $response = $this->get('property'); // propertyへアクセス
        $response->assertStatus(200); // 200ステータスであること
        $response->assertViewIs('property.index'); // property.indexビューであること
        $response->assertDontSeeText($editproperty->bookdata->title); // 削除タイトルが表示されていないこと
    }
    // 検索
    public function test_findTitle_ok_yesMatchFindTitle()
    {
        //// ユーザー生成
        $user = factory(User::class)->create(); // ユーザーを作成
        $this->actingAs($user); // ログイン済み
        $this->assertTrue(Auth::check()); // Auth認証済であることを確認

        // faker 自動生成
        $bookdata = factory(Bookdata::class)->create();
        $propertydata = factory(Property::class)->make([
            'user_id' => 1,
            'bookdata_id' => 1,
        ]);
        $propertydata->save();
        // eval(\Psy\sh());
        //// 検索
        // 検索の実施(findページ)
        $find_post = 'property/find'; // 検索パス
        $response = $this->get($find_post); // 検索ページへアクセス
        $response->assertStatus(200); // 200ステータスであること
        $response->assertViewIs('property.find'); // property.findビューであること
        $response = $this->from($find_post)->post($find_post, ['find' => $propertydata->bookdata->title]); // 検索実施
        $response->assertSessionHasNoErrors(); // エラーメッセージがないこと
        $response->assertStatus(200); // 200ステータスであること
        $response->assertSeeText($propertydata->bookdata->title); // bookdataタイトルが表示されていること
    }
    public function test_findTitle_ok_noMatchFindTitle()
    {
        //// ユーザー生成
        $user = factory(User::class)->create(); // ユーザーを作成
        $this->actingAs($user); // ログイン済み
        $this->assertTrue(Auth::check()); // Auth認証済であることを確認

        // faker 自動生成
        $bookdata = factory(Bookdata::class)->create([
            'title' => 'a'
        ]); // タイトル名aで作成
        $propertydata = factory(Property::class)->create();

        //// 検索
        // 検索の実施(findページ)
        $find_post = 'property/find'; // 検索パス
        $response = $this->get($find_post); // 検索ページへアクセス
        $response->assertStatus(200); // 200ステータスであること
        $response->assertViewIs('property.find'); // property.findビューであること
        $response = $this->from($find_post)->post($find_post, ['find' => 'b']); // bで検索実施
        $response->assertSessionHasNoErrors(); // エラーメッセージがないこと
        $response->assertStatus(200); // 200 ステータスであること
        $response->assertViewIs('property.find'); // property.findビューであること
        $response->assertSeeText('書籍がみつかりませんでした。'); // タイトルなしメッセージが表示されていること
    }
    // 複数ユーザーによる複数書籍登録時のデータ表示確認
    public function test_propertySomeControll_ok()
    {
        // 設定
        $usernumber = 10;// ユーザー数
        $booknumber = 10;// 所有書籍数

        // ユーザー情報
        factory(User::class, $usernumber)->create(); // 複数ユーザー作成
        $user = User::find(mt_rand(1,$usernumber)); // ランダムでユーザー情報取得
        $this->actingAs($user); // 選択ユーザーでログイン
        $this->assertTrue(Auth::check()); // Auth認証済であることを確認

        // 所有書籍情報登録
        // 複数ユーザー分の所有書籍情報
        for ($usercount = 1; $usercount <= $usernumber; $usercount++) {
            // 各ユーザーに対して複数書籍所持情報を作成
            for ($i = 1; $i <= $booknumber; $i++) {
                $propertydata = factory(Property::class)->create([
                    'user_id' => $usercount,
                ]);
            }
        }

        // 選択ユーザーの所有書籍情報を取得
        $savebooks = Property::where('user_id', $user->id)->get();

        // 選択ユーザーの所有書籍が登録されていることの確認
        $response = $this->get('property'); // bookへアクセス
        $response->assertStatus(200); // 200ステータスであること
        $response->assertViewIs('property.index'); // book.indexビューであること
        
        foreach ($savebooks as $savebook) {
            $response->assertSeeText($savebook->bookdata->title);
        } // 登録タイトルが表示されていること
    }
}

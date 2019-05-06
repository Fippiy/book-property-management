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
}

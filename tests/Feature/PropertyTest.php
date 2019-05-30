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
        $response->assertViewIs('property.create'); // createビューであること

        //// 所有書籍登録
        $propertydata = [
            'user_id' => 1,
            'bookdata_id' => 1,
            'number' => 1,
            'getdate' => '2000-01-01',
            'freememo' => 'testmemo',
        ];
        $response = $this->from('property/create')->post('property', $propertydata); // post送信
        $response->assertSessionHasNoErrors(); // エラーメッセージがないこと
        $response->assertStatus(200); // 200ステータスであること

        $savebook = Property::all()->first(); // 保存されたデータを取得

        // 登録されていることの確認(indexページ)
        $response = $this->get('property'); // indexへアクセス
        $response->assertStatus(200); // 200ステータスであること
        $response->assertViewIs('property.index'); // indexビューであること
        $response->assertSeeText($savebook->bookdata->title); // 登録タイトルが表示されていること

        // 詳細ページで表示されること
        $response = $this->get('property/'.$savebook['id']); // 指定ページへアクセス
        $response->assertStatus(200); // 200ステータスであること
        $response->assertViewIs('property.show'); // showビューであること
        foreach ($savebook as $value)
        {
            $response->assertSeeText($value);
        }; // savebookデータが表示されていること

        //// 編集
        $edit_post = 'property/'.$savebook['id']; // 編集パス
        $response = $this->get($edit_post.'/edit'); // editページへアクセス
        $response->assertStatus(200); // 200ステータスであること
        $response->assertViewIs('property.edit'); // editビューであること

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

        $editproperty = Property::all()->first(); // editデータを取得

        // 編集されていることの確認(indexページ)
        $response = $this->get('property'); // indexへアクセス
        $response->assertStatus(200); // 200ステータスであること
        $response->assertViewIs('property.index'); // indexビューであること
        $response->assertSeeText($editproperty->bookdata->title); // 編集タイトルが表示されていること

        //// 削除
        $response = $this->from('property/'.$savebook['id'])->post('property/'.$savebook['id'], [
            '_method' => 'DELETE',
            ]); // 削除実施
        $response->assertSessionHasNoErrors(); // エラーメッセージがないこと
        $response->assertStatus(302); // リダイレクト
        $response->assertRedirect('/property');  // トップページ表示

        // 削除されていることの確認(indexページ)
        $response = $this->get('property'); // indexへアクセス
        $response->assertStatus(200); // 200ステータスであること
        $response->assertViewIs('property.index'); // indexビューであること
        $response->assertDontSeeText($editproperty->bookdata->title); // deleteタイトルが表示されていないこと
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
        $response = $this->get($find_post); // findページへアクセス
        $response->assertStatus(200); // 200ステータスであること
        $response->assertViewIs('property.find'); // findビューであること
        $response = $this->call('GET', 'property/search', ['find' => $bookdata->title]); // 検索実施
        $response->assertSessionHasNoErrors(); // エラーメッセージがないこと
        $response->assertStatus(200); // 200ステータスであること
        $response->assertSeeText($propertydata->bookdata->title); // findタイトルが表示されていること
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
        ]); // 指定タイトルで作成
        $propertydata = factory(Property::class)->create();

        //// 検索
        // 検索の実施(findページ)
        $find_post = 'property/find'; // findパス
        $response = $this->get($find_post); // findページへアクセス
        $response->assertStatus(200); // 200ステータスであること
        $response->assertViewIs('property.find'); // findビューであること
        $response = $this->call('GET', 'property/search', ['find' => 'b']); // 検索実施
        $response->assertSessionHasNoErrors(); // エラーメッセージがないこと
        $response->assertStatus(200); // 200 ステータスであること
        $response->assertViewIs('property.find'); // findビューであること
        $response->assertSeeText('書籍がみつかりませんでした。'); // エラーメッセージが表示されていること
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
        $response = $this->get('property'); // indexへアクセス
        $response->assertStatus(200); // 200ステータスであること
        $response->assertViewIs('property.index'); // indexビューであること
        
        foreach ($savebooks as $savebook) {
            $response->assertSeeText($savebook->bookdata->title);
        } // 登録タイトルが表示されていること
    }
     // 複数削除
     public function test_propertySomeControll_ok_someDelete()
    {
        //// ユーザー生成
        $user = factory(User::class)->create(); // ユーザーを作成
        $this->actingAs($user); // ログイン済み
        $this->assertTrue(Auth::check()); // Auth認証済であることを確認

        // faker 自動生成
        $delete_propertydatas = factory(Property::class, 20)->create([
            'user_id' => $user->id,
        ]);

        // 送信フォームパス
        $formpath = 'property/somedelete';
        // 削除データ
        $deletedatas = ['select_books' => range(1, 20)];

        //// 削除
        $response = $this->from($formpath)->post($formpath, $deletedatas); // 削除実施
        $response->assertSessionHasNoErrors(); // エラーメッセージがないこと
        $response->assertStatus(200); // 200ステータスであること
        $response->assertViewIs('property.delete_result'); // property.delete_resultビューであること

        // 削除されていることの確認(resultページ)
        $response->assertSeeText('所有書籍情報解除結果'); // 削除結果ページが出力されていること
        $response->assertStatus(200); // 200ステータスであること
        foreach($delete_propertydatas as $deleteproperty){
            $response->assertSeeText($deleteproperty->bookdata->title); // 削除タイトルが結果に反映されていること
        }
    }
    // ページネーション表示テスト
    public function test_bookControll_ok_paginationView()
    {
        //// ユーザー生成
        $user = factory(User::class)->create(); // ユーザーを作成
        $this->actingAs($user); // ログイン済み
        $this->assertTrue(Auth::check()); // Auth認証済であることを確認

        // 件数設定
        $datarecords = 21; // テストレコード数
        $paginate = 20; // 1ページ表示数
        $totalpage = ceil($datarecords/$paginate); // 表示ページ総数

        // faker 自動生成
        $properties = factory(Property::class, $datarecords)->create([
            'user_id' => $user->id,
        ]);

        // index表示パス
        $viewpath = 'property?page=';

        for ($i = 1; $i <= $totalpage; $i++){ // ページ数分繰り返し
            $response = $this->get($viewpath.$i); // 各ページへアクセス
            $response->assertStatus(200); // 200ステータスであること

            // 表示ページに対してデータが確認できること
            // レコード件数/総数で対象ページを算出
            $j = 0;
            foreach($properties as $property){
                $j++;
                if ($i == ceil($j/$paginate)) { // 表示ページデータの場合
                    $response->assertSeeText($property->bookdata->title); // 対象ページに表示されていること
                } else { // 表示ページデータでない場合
                    $response->assertDontSeeText($property->bookdata->title); // 対象ページに表示されていないこと
                }
            }
        }
    }

    //// NGパターン調査
    // タイトル未入力
    public function test_propertyControll_ng_notNameEntry()
    {
        //// ユーザー生成
        $user = factory(User::class)->create(); // ユーザーを作成
        $this->actingAs($user); // ログイン済み
        $this->assertTrue(Auth::check()); // Auth認証済であることを確認

        //// タイトル情報なしで登録
        $propertydata = [
            'bookdata_id' => null,
        ];
        $propertydatapath = 'property/create';
        $response = $this->from($propertydatapath)->post('property', $propertydata); // 本情報保存
        $response->assertSessionHasErrors(['bookdata_id']); // エラーメッセージがあること
        $response->assertStatus(302); // リダイレクト
        $response->assertRedirect($propertydatapath);  // 同ページへリダイレクト
        $this->assertEquals('bookdata idは必須です。',
        session('errors')->first('bookdata_id')); // エラメッセージを確認
    }
    // 未登録書籍
    public function test_propertyControll_ng_notBookEntryToProperty()
    {
        // property 自動生成 // 関連 user,bookdataも作成
        $propertydata = factory(Property::class)->create();
        
        // ユーザーログイン
        $user = User::first(); // 作成済みユーザー情報取得
        $this->actingAs($user); // 選択ユーザーでログイン
        $this->assertTrue(Auth::check()); // Auth認証済であることを確認

        //// 未登録書籍を登録
        $propertydata = [
            'bookdata_id' => 2,
        ];
        $propertydatapath = 'property/create';
        $response = $this->from($propertydatapath)->post('property', $propertydata); // 本情報保存
        $response->assertStatus(500); // 500エラーであること
    }
    // タイトルユニーク
    public function test_propertyControll_ng_uniqueNameEntry()
    {
        // property 自動生成 // 関連 user,bookdataも作成
        $propertydata = factory(Property::class)->create();
        
        // ユーザーログイン
        $user = User::first(); // 作成済みユーザー情報取得
        $this->actingAs($user); // 選択ユーザーでログイン
        $this->assertTrue(Auth::check()); // Auth認証済であることを確認

        //// 重複登録
        $propertydata = [
            'bookdata_id' => $propertydata->bookdata_id,
        ];
        $savepropertypath = 'property/create'; // 新規作成パス
        $response = $this->from($savepropertypath)->post('property', $propertydata); // 保存
        $response->assertSessionHasErrors(['bookdata_id']); // エラーメッセージがあること
        $response->assertStatus(302); // リダイレクト
        $response->assertRedirect($savepropertypath);  // 同ページへリダイレクト
        $this->assertEquals('書籍は登録済みです',
        session('errors')->first('bookdata_id')); // エラーメッセージを確認
    }
    // 別のユーザーで登録
    public function test_propertyControll_ng_unMatchUserEntry()
    {
        // 書籍情報作成
        $bookdata = factory(Bookdata::class)->create();

        //// ユーザー生成
        $user = factory(User::class)->create(); // ユーザーを作成
        $this->actingAs($user); // ログイン済み
        $this->assertTrue(Auth::check()); // Auth認証済であることを確認

        $otheruser = factory(User::class)->create(); // 別のユーザーを作成

        //// 登録
        $propertydata = [
            'bookdata_id' => $bookdata->id,
            'user_id' => $otheruser->id,
        ]; // ログインユーザー以外で強制登録
        $propertydatapath = 'property/create';
        $response = $this->from($propertydatapath)->post('property', $propertydata); // 本情報保存
        $response->assertStatus(500); // 500エラーであること
    }
    // 所有書籍情報編集、bookdata_idを送信しても反映させない(他のカラムは反映)
    public function test_propertyControll_ng_notTitleEdit()
    {
        // property 自動生成 // 関連 user,bookdataも作成
        $propertydata = factory(Property::class)->create();
        
        // ユーザーログイン
        $user = User::first(); // 作成済みユーザー情報取得
        $this->actingAs($user); // 選択ユーザーでログイン
        $this->assertTrue(Auth::check()); // Auth認証済であることを確認

        //// 編集パス
        $propertypath = 'property/'.$propertydata->id.'/edit'; // 書籍編集パス
        //// 編集
        $editpropertydata = [
            'bookdata_id' => '',
            'number' => '100',
            '_method' => 'PUT',
        ]; // タイトルなしに編集
        $response = $this->from($propertypath)->post('property/'.$propertydata->id, $editpropertydata); // 本情報保存
        $response->assertStatus(302); // リダイレクト
        $response->assertRedirect('property');  // index表示

        $response = $this->get('property'); // indexへアクセス
        $response->assertStatus(200); // 200ステータスであること
        $response->assertViewIs('property.index'); // indexビューであること
        $savebook = Property::find(1);
        $response->assertSeeText($savebook->bookdata->title); // 登録タイトルが表示されていること
        
        $response = $this->get('property/1'); // showへアクセス
        $response->assertStatus(200); // 200ステータスであること
        $response->assertViewIs('property.show'); // showビューであること
        $response->assertSeeText('100'); // numberが反映されていること

    }
    // 所有書籍情報編集NG、未登録id
    public function test_propertyControll_ng_notIdEdit()
    {
        // property 自動生成 // 関連 user,bookdataも作成
        $propertydata = factory(Property::class)->create();
        
        // ユーザーログイン
        $user = User::first(); // 作成済みユーザー情報取得
        $this->actingAs($user); // 選択ユーザーでログイン
        $this->assertTrue(Auth::check()); // Auth認証済であることを確認

        // 編集パス
        $propertypath = 'property/2/edit'; // 書籍編集パス(存在しないID)

        // アクセス不可
        $response = $this->get($propertypath); // ページにアクセス
        $response->assertStatus(500);  // 500ステータスであること
    }
    // 複数ユーザーデータ有りで他人データ編集
    public function test_propertySomeControll_ng_otherUserDataEdit()
    {
        // 設定
        $usernumber = 2;// ユーザー数
        $booknumber = 10;// 所有書籍数

        // ユーザー情報
        factory(User::class, $usernumber)->create(); // 複数ユーザー作成
        $user = User::find(1); // ユーザー情報取得
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
        //// 編集パス
        $propertypath = 'property/11/edit'; // 書籍編集パス
        //// 登録
        $editpropertydata = [
            'number' => 11,
            '_method' => 'PUT',
        ]; // タイトルを編集
        $response = $this->from($propertypath)->post('property/11', $editpropertydata); // 本情報保存
        $response->assertStatus(302); // リダイレクト
        $response->assertRedirect('property');  // indexへリダイレクト

        // DBが変更されていないこと
        $savebook = Property::find(11);
        $this->assertDatabaseHas('properties', [
            'id' => $savebook->id,
            'number' => $savebook->number,
        ]); //DBに配列で指定した情報が残っていること
    }
    // 未登録id削除
    public function test_propertyControll_ng_notIdDelete()
    {
        // property 自動生成 // 関連 user,bookdataも作成
        factory(Property::class)->create();
        
        // ユーザーログイン
        $user = User::first(); // 作成済みユーザー情報取得
        $this->actingAs($user); // 選択ユーザーでログイン
        $this->assertTrue(Auth::check()); // Auth認証済であることを確認

        // 編集パス
        $propertypath = 'property/2'; // 書籍編集パス(存在しないID)

        //// 削除
        $response = $this->from($propertypath)->post($propertypath, [
            '_method' => 'DELETE',
            ]); // 削除実施
        $response->assertStatus(302);  // 302ステータスであること
    }
    //他人の所有書籍を削除する
    public function test_propertySomeControll_ng_otherUserDataDelete()
    {
        // 設定
        $usernumber = 2;// ユーザー数
        $booknumber = 10;// 所有書籍数

        // ユーザー情報
        factory(User::class, $usernumber)->create(); // 複数ユーザー作成
        $user = User::find(1); // ユーザー情報取得
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
        //// 編集パス
        $propertypath = 'property/11'; // 書籍編集パス

        //// 登録
        $deletepropertydata = [
            'id' => 11,
            '_method' => 'DELETE',
        ]; // タイトルを編集
        $response = $this->from($propertypath)->post($propertypath, $deletepropertydata); // 本情報削除
        $response->assertStatus(302); // リダイレクト
        $response->assertRedirect('property');  // index表示

        // 登録されていることの確認(indexページ)
        $response = $this->get('property'); // indexへアクセス
        $response->assertStatus(200); // 200ステータスであること
        $response->assertViewIs('property.index'); // indexビューであること
        $savebook = Property::find(11);
        $response->assertDontSeeText($savebook->bookdata->title); // 登録タイトルが表示されていないこと
    }
    // 複数削除エラー、postデータなし
    public function test_propertyControll_ng_someDeleteNotEntry()
    {
        $user = factory(User::class)->create(); // ユーザーを作成
        $this->actingAs($user); // ログイン済み
        $this->assertTrue(Auth::check()); // Auth認証済であることを確認

        // 送信フォームパス
        $formpath = 'property/somedelete';
        // 削除データ
        $deletedata = [];

        //// 削除
        $response = $this->from($formpath)->post($formpath, $deletedata); // 削除実施
        $response->assertSessionHasErrors(); // エラーメッセージがあること
        $response->assertStatus(302); // リダイレクト
        $response->assertRedirect('property'); // propertyビューであること
        $this->assertEquals('所有書籍から解除する本が選択されていません',
        session('errors')->first('deleteproperty')); // エラメッセージを確認
    }
     // 複数削除エラー、上限超過処理
     public function test_propertyControll_ng_someDeleteLimitNumber()
    {
        //// ユーザー生成
        $user = factory(User::class)->create(); // ユーザーを作成
        $this->actingAs($user); // ログイン済み
        $this->assertTrue(Auth::check()); // Auth認証済であることを確認

        $count = 20;// 上限設定
        // faker 自動生成
        $delete_propertydatas = factory(Property::class, $count+1)->create([
            'user_id' => $user->id,
        ]);

        // 送信フォームパス
        $formpath = 'property/somedelete';
        // 削除データ
        $deletedata = ['select_books' => range(1, 20)];

        //// 削除
        $response = $this->from($formpath)->post($formpath, $deletedata); // 削除実施
        $response->assertSessionHasNoErrors(); // エラーメッセージがないこと
        $response->assertStatus(200); // 200ステータスであること

        $response->assertSeeText('所有書籍情報解除結果'); // 登録結果ページが出力されていること
        // 編集ISBN番号結果確認
        for ($i = 0; $i < $count+1; $i++){
            if ($count > $i){ // 上限数まで結果表示されること
                $response->assertSeeText($delete_propertydatas[$i]->bookdata->title); // タイトルが反映されていること
            } else { // 上限超過データは扱われていないこと
                $response->assertDontSeeText($delete_propertydatas[$i]->bookdata->title); // タイトルが反映されていないこと
            }
        }
    }
     // 複数削除エラー、他者データ削除
     public function test_bookControll_ng_someDeleteOtherHaveProperty()
    {
        //// ユーザー生成
        $user = factory(User::class)->create(); // ユーザーを作成
        $this->actingAs($user); // ログイン済み
        $this->assertTrue(Auth::check()); // Auth認証済であることを確認

        // faker 自動生成
        $delete_bookdata = factory(Property::class)->create();
        // 送信フォームパス
        $formpath = 'property/somedelete';
        // 削除データ
        $deletedata = ['select_books' => [$delete_bookdata->id]];

        //// 削除
        $response = $this->from($formpath)->post($formpath, $deletedata); // 削除実施
        $response->assertSessionHasNoErrors(); // エラーメッセージがないこと
        $response->assertStatus(500); // 500ステータスであること
    }
    // ページネーション表示、ページ超過
    public function test_bookControll_ok_paginationViewOverPage()
    {
        //// ユーザー生成
        $user = factory(User::class)->create(); // ユーザーを作成
        $this->actingAs($user); // ログイン済み
        $this->assertTrue(Auth::check()); // Auth認証済であることを確認

        // 件数設定
        $datarecords = 21; // テストレコード数

        // faker 自動生成
        $properties = factory(Property::class, $datarecords)->create([
            'user_id' => $user->id,
        ]);

        // index表示パス
        $viewpath = 'property?page=';
        // 表示ページ
        $page = 3;

        $response = $this->get($viewpath.$page); // 各ページへアクセス
        $response->assertStatus(200); // 200ステータスであること

        // 表示ページにデータが出力されていないこと
        foreach($properties as $property){
            $response->assertDontSeeText($property->bookdata->title); // 対象ページに表示されていないこと
        }
    }
}

<?php

// トップページ
Breadcrumbs::for('toppage', function ($trail) {
    $trail->push('トップページ', url(''));
});

// トップページ / 書籍トップ
Breadcrumbs::for('book.index', function ($trail) {
    $trail->parent('toppage');
    $trail->push('書籍', url('book'));
});

// トップページ / 書籍 / 新規登録
Breadcrumbs::for('book.create', function ($trail) {
    $trail->parent('book.index');
    $trail->push('新規登録', url('book.create'));
});

// トップページ / 書籍 / ISBN登録
Breadcrumbs::for('book.isbn', function ($trail) {
    $trail->parent('book.index');
    $trail->push('ISBN登録', url('book.isbn'));
});

// トップページ / 書籍 / ISBN登録(複数)
Breadcrumbs::for('book.isbn_some', function ($trail) {
    $trail->parent('book.isbn');
    $trail->push('複数登録', url('book.isbn_some'));
});

// トップページ / 書籍 / ISBN登録(一括)
Breadcrumbs::for('book.isbn_some_input', function ($trail) {
    $trail->parent('book.isbn');
    $trail->push('一括登録', url('book.isbn_some_input'));
});

// トップページ / 書籍 / 詳細
Breadcrumbs::for('book.show', function ($trail, $book) {
    $trail->parent('book.index');
    $trail->push($book->title, url('book.show'));
});

// トップページ / 書籍 / 編集
Breadcrumbs::for('book.edit', function ($trail, $book) {
    $trail->parent('book.index');
    $trail->push('編集:'.$book->title, url('book.edit'));
});

// トップページ / 書籍 / 検索
Breadcrumbs::for('book.find', function ($trail) {
    $trail->parent('book.index');
    $trail->push('書籍検索', url('book.find'));
});

// トップページ / 所有書籍
Breadcrumbs::for('property.index', function ($trail) {
    $trail->parent('toppage');
    $trail->push('所有書籍', url('property.index'));
});

// トップページ / 所有書籍 / 所有書籍詳細
Breadcrumbs::for('property.show', function ($trail, $property) {
    $trail->parent('property.index');
    $trail->push($property->bookdata->title, url('property.show'));
});

// トップページ / 所有書籍 / 所有書籍登録
Breadcrumbs::for('property.create', function ($trail) {
    $trail->parent('property.index');
    $trail->push('所有書籍登録', url('property.create'));
});

// トップページ / 所有書籍 / 所有書籍検索
Breadcrumbs::for('property.find', function ($trail) {
    $trail->parent('property.index');
    $trail->push('所有書籍検索', url('property.find'));
});

// トップページ / 所有書籍 / 所有書籍編集
Breadcrumbs::for('property.edit', function ($trail, $property) {
    $trail->parent('property.index');
    $trail->push('所有書籍編集:'.$property->bookdata->title, url('property.edit'));
});

// トップページ / マイページトップ
Breadcrumbs::for('user.index', function ($trail) {
    $trail->parent('toppage');
    $trail->push('マイページ', url('user'));
});

// トップページ / マイページ / 編集：ユーザ
Breadcrumbs::for('user.edit', function ($trail) {
    $trail->parent('user.index');
    $trail->push('編集', url('user.edit'));
});

// トップページ / マイページ / ユーザ削除
Breadcrumbs::for('user.delete', function ($trail) {
    $trail->parent('user.index');
    $trail->push('削除', url('user.delete'));
});

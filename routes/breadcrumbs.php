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

// トップページ / 書籍 / 新規登録(ISBN)
Breadcrumbs::for('book.isbn', function ($trail) {
    $trail->parent('book.index');
    $trail->push('新規登録(ISBN)', url('book.isbn'));
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

// トップページ / マイページ
Breadcrumbs::for('user.index', function ($trail) {
    $trail->parent('toppage');
    $trail->push('マイページ', url('user.index'));
});

// トップページ / マイページ / 所有書籍登録
Breadcrumbs::for('user.create', function ($trail) {
    $trail->parent('user.index');
    $trail->push('所有書籍登録', url('user.create'));
});

// トップページ / マイページ / 所有書籍検索
Breadcrumbs::for('user.find', function ($trail) {
    $trail->parent('user.index');
    $trail->push('所有書籍検索', url('user.find'));
});

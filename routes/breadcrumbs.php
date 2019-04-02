<?php

// トップページ
Breadcrumbs::for('toppage', function ($trail) {
    $trail->push('トップページ', url(''));
});

// トップページ > 書籍トップ
Breadcrumbs::for('book', function ($trail) {
    $trail->parent('toppage');
    $trail->push('書籍トップ', url('book'));
});

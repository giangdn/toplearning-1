<?php

Route::group(['prefix' => '/news-outside/{cate_id}'], function() {
    Route::get('/{parent_id}/{type}', 'FrontendController@index')->name('module.frontend.news_outside')->where('cate_id','[0-9]+')->where('parent_id','[0-9]+')->where('type','[0-9]+');

    Route::get('/detail/{id}', 'FrontendController@detail')->name('module.frontend.news_outside.detail')->where('id', '[0-9]+');
});

Route::group(['prefix' => '/admin-cp/news-outside', 'middleware' => 'auth'], function() {
    Route::get('/', 'NewsOutsideController@index')->name('module.news_outside.manager');

    Route::get('/getdata', 'NewsOutsideController@getData')->name('module.news_outside.getdata');

    Route::get('/edit/{id}', 'NewsOutsideController@form')->name('module.news_outside.edit')->where('id', '[0-9]+');

    Route::get('/create', 'NewsOutsideController@form')->name('module.news_outside.create');

    Route::post('/save', 'NewsOutsideController@save')->name('module.news_outside.save');

    Route::post('/remove', 'NewsOutsideController@remove')->name('module.news_outside.remove');

    Route::post('/ajax-isopen-publish', 'NewsOutsideController@ajaxIsopenPublish')->name('module.news_outside.ajax_isopen_publish');
});

Route::group(['prefix' => '/admin-cp/category-news-outside', 'middleware' => 'auth'], function() {
    Route::get('/', 'CategoryController@index')->name('module.news_outside.category');

    Route::get('/getdata', 'CategoryController@getData')->name('module.news_outside.category.getdata');

    Route::post('/edit', 'CategoryController@form')->name('module.news_outside.category.edit')->where('id', '[0-9]+');

    Route::post('/save', 'CategoryController@save')->name('module.news_outside.category.save');

    Route::post('/remove', 'CategoryController@remove')->name('module.news_outside.category.remove');
});

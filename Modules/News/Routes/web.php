<?php

Route::group(['prefix' => '/news', 'middleware' => 'auth'], function() {
    Route::get('/', 'Frontend\FrontendController@index')->name('module.news');

    Route::post('/like-new', 'Frontend\FrontendController@likeNew')->name('module.new.like');

    Route::get('/detail/{id}', 'Frontend\FrontendController@detail')->name('module.news.detail')->where('id', '[0-9]+');

    Route::get('/cate-new/{parent_id}/{id}/{type}', 'Frontend\FrontendController@cateNew')
        ->name('module.news.cate_new')
        ->where('parent_id', '[0-9]+')
        ->where('id', '[0-9]+')
        ->where('type', '[0-9]+');

    Route::post('ajax-get-related-news', 'Frontend\FrontendController@ajaxGetRelatedNews')->name('module_ajax_get_related_news');

    Route::get('/view-pdf', 'Frontend\FrontendController@viewPDF')->name('module.news.view_pdf');
});

Route::group(['prefix' => '/admin-cp/news', 'middleware' => 'auth'], function() {
    Route::get('/', 'BackendController@index')->name('module.news.manager')->middleware('permission:news-list');

    Route::get('/getdata', 'BackendController@getData')->name('module.news.getdata')->middleware('permission:news-list');

    Route::get('/edit/{id}', 'BackendController@form')->name('module.news.edit')->where('id', '[0-9]+')->middleware('permission:news-list-edit');

    Route::get('/create', 'BackendController@form')->name('module.news.create')->middleware('permission:news-list-create');

    Route::post('/save', 'BackendController@save')->name('module.news.save')->middleware('permission:news-list-edit|news-list-create');

    Route::post('/remove', 'BackendController@remove')->name('module.news.remove')->middleware('permission:news-list-delete');

    Route::get('/preview-new/{id}', 'BackendController@previewNew')->name('module.news.preview_new')
        ->middleware('permission:news-list-edit')
        ->where('id', '[0-9]+');

    Route::post('/ajax-isopen-publish', 'BackendController@ajaxIsopenPublish')
        ->name('module.news.ajax_isopen_publish')->middleware('permission:news-list-status');

    Route::post('/edit/{id}/save-object', 'BackendController@saveObject')
    ->name('module.news.save_object')
    ->where('id', '[0-9]+');

    Route::get('/edit/{id}/get-object', 'BackendController@getObject')
        ->name('module.news.get_object')
        ->where('id', '[0-9]+');

    Route::get('/edit/{id}/get-user-object', 'BackendController@getUserObject')
        ->name('module.news.get_user_object')
        ->where('id', '[0-9]+');

    Route::post('/edit/{id}/remove-object', 'BackendController@removeObject')
        ->name('module.news.remove_object')
        ->where('id', '[0-9]+');

    Route::post('/edit/{id}/import-object', 'BackendController@importObject')
        ->name('module.news.import_object')
        ->where('id', '[0-9]+');

    Route::post('/remove-item-new-link', 'BackendController@removeItemNewLink')
        ->name('module.news.remove_item_new_link')
        ->middleware('permission:news-list-delete');
});

Route::group(['prefix' => '/admin-cp/category-news', 'middleware' => 'auth'], function() {
    Route::get('/', 'CategoryController@index')->name('module.news.category')->middleware('permission:news-category');

    Route::get('/getdata', 'CategoryController@getData')->name('module.news.category.getdata')->middleware('permission:news-category');

    Route::post('/edit', 'CategoryController@form')->name('module.news.category.edit')->where('id', '[0-9]+')->middleware('permission:news-category-edit');

    Route::post('/save', 'CategoryController@save')->name('module.news.category.save')->middleware('permission:news-category-create|news-category-edit');

    Route::post('/remove', 'CategoryController@remove')->name('module.news.category.remove')->middleware('permission:news-category-delete');

});

<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => '/forums', 'middleware' => 'auth'], function() {
    Route::get('/', 'FrontendController@index')->name('module.frontend.forums');

    Route::get('/topic/{id}', 'FrontendController@forum')->name('module.frontend.forums.topic');

    Route::post('/topic/{id}/delete', 'FrontendController@forum_delete')
        ->name('module.frontend.forums.deleteforum')
        ->where('id','[0-9]+');

    Route::get('/topic/form/{id}', 'FrontendController@form')->name('module.frontend.forums.form');

    Route::get('/topic/thread/{id}', 'FrontendController@thread')->name('module.frontend.forums.thread');

    Route::post('/topic/thread/{id}/cmt','FrontendController@thread_cmt')
        ->name('module.frontend.forums.comment')
        ->where('id', '[0-9]+');

    Route::post('/topic/thread/{id}/delete','FrontendController@comment_delete')
        ->name('module.frontend.forums.delete')
        ->where('id', '[0-9]+');

    Route::post('/topic/form/{id}/save', 'FrontendController@saveTopic')
        ->name('module.frontend.forums.form.save')
        ->where('id', '[0-9]+');

    Route::get('/topic/edit/{id}', 'FrontendController@edit')->name('module.frontend.forums.edit');

    Route::post('/topic/edit/{id}', 'FrontendController@update')->name('module.frontend.forums.update');

    Route::get('/topic/thread/comment/edit/{id}','FrontendController@edit_cmt')
        ->name('module.frontend.forums.comment.edit')
        ->where('id', '[0-9]+');

    Route::post('/topic/thread/comment/update/{id}','FrontendController@update_cmt')
        ->name('module.frontend.forums.comment.update')
        ->where('id', '[0-9]+');

    Route::post('/topic/thread/comment/like-dislike/{id}','FrontendController@like_dislike_cmt')
        ->name('module.frontend.forums.comment.like_dislike')
        ->where('id', '[0-9]+');
});

Route::group(['prefix' => '/admin-cp/forums', 'middleware' => 'auth'], function() {
    //danh mục
    Route::get('/', 'CategoryController@index')->name('module.forum.category')->middleware('permission:forum');
    Route::get('/category/getdata', 'CategoryController@getData')->name('module.forum.category.getdata')->middleware('permission:forum');
    Route::post('/category/edit', 'CategoryController@form')->name('module.forum.category.edit')->where('id', '[0-9]+')->middleware('permission:forum-edit');
    Route::post('/category/save', 'CategoryController@save')->name('module.forum.category.save')->middleware('permission:forum-create|forum-edit');
    Route::post('/category/remove', 'CategoryController@remove')->name('module.forum.category.remove')->middleware('permission:forum-delete');
    Route::post('/category/ajax-isopen-publish', 'CategoryController@ajaxIsopenPublish')->name('module.forum.category.ajax_isopen_publish')->middleware('permission:forum-status');

    // filter_word
    Route::get('/filter-word', 'CategoryController@filter')->name('module.forum.category.filter');
    Route::get('/filter-word/getword', 'CategoryController@getword')->name('module.forum.category.filter.getword');
    Route::post('/filter-word/edit', 'CategoryController@filter_word')
    ->name('module.forum.category.filter_word.edit')
    ->where('id', '[0-9]+');
    Route::post('/filter-word/save', 'CategoryController@filter_save')->name('module.forum.category.filter_save');
    Route::post('/filter-word/remove', 'CategoryController@filter_remove')->name('module.forum.category.filter_save.remove');
    Route::post('/filter-word/ajax-isopen-publish', 'CategoryController@filterAjaxIsopenPublish')
    ->name('module.forum.category.filter_word.ajax_isopen_publish');

});

Route::group(['prefix' => '/admin-cp/forums/{cate_id}', 'middleware' => 'auth'], function() {
    //forum
    Route::get('/forum', 'CategoryController@forum')
        ->name('module.forum')
        ->where('cate_id', '[0-9]+');

    Route::get('/forum/getdata', 'CategoryController@getDataForum')
        ->name('module.forum.getdata')
        ->where('cate_id', '[0-9]+');

    Route::post('/forum/edit', 'CategoryController@formForum')
        ->name('module.forum.edit')
        ->where('cate_id', '[0-9]+')
        ->where('id', '[0-9]+');

    Route::post('/forum/save', 'CategoryController@saveForum')
        ->name('module.forum.save')
        ->where('cate_id', '[0-9]+');

    Route::post('/forum/remove', 'CategoryController@removeForum')
        ->name('module.forum.remove')
        ->where('cate_id', '[0-9]+');

    Route::post('/forum/ajax-isopen-publish', 'CategoryController@ajaxIsopenPublishForum')
        ->name('module.forum.ajax_isopen_publish');
});

Route::group(['prefix' => '/admin-cp/forums/{cate_id}/forum/{forum_id}', 'middleware' => 'auth'], function() {
    //thread
    Route::get('/thread', 'CategoryController@forumthread')
        ->name('module.forum.thread')
        ->where('cate_id', '[0-9]+')
        ->where('forum_id', '[0-9]+')->middleware('permission:forum_thread');

    Route::get('/thread/create', 'CreateNewsController@index')
        ->name('module.forum.thread.create')
        ->where('cate_id', '[0-9]+')
        ->where('forum_id', '[0-9]+')->middleware('permission:forum_thread-create');

    Route::post('/thread/save', 'CreateNewsController@save')
        ->where('cate_id', '[0-9]+')
        ->where('forum_id', '[0-9]+')
        ->name('module.forum.thread.save')->middleware('permission:forum_thread-create');

    Route::get('/thread/edit/{id}', 'CreateNewsController@index')
        ->where('cate_id', '[0-9]+')
        ->where('forum_id', '[0-9]+')
        ->name('module.forum.thread.edit')->middleware('permission:forum_thread-edit');

    Route::post('/thread/remove', 'CreateNewsController@remove')
        ->name('module.forum.thread.remove')
        ->where('cate_id', '[0-9]+')
        ->where('forum_id', '[0-9]+')->middleware('permission:forum_thread-remove');

    Route::get('/thread/getdata', 'CategoryController@getDatathread')
        ->name('module.forum.getdatathread')
        ->where('cate_id', '[0-9]+')
        ->where('forum_id', '[0-9]+')->middleware('permission:forum_thread');

    Route::post('/thread/ajax-save-status', 'CategoryController@saveStatus')
        ->name('module.forum.save_status')
        ->where('cate_id', '[0-9]+')
        ->where('forum_id', '[0-9]+');
});

Route::group(['prefix' => '/admin-cp/forums/{cate_id}/permission', 'middleware' => 'auth'], function() {
    //forum
    Route::get('/', 'CategoryController@permission')
        ->name('module.permission')
        ->where('cate_id', '[0-9]+');

    Route::get('/getdata', 'CategoryController@getDataPermission')
        ->name('module.permission.getdata')
        ->where('cate_id', '[0-9]+');

    Route::post('/save', 'CategoryController@savePermission')
        ->name('module.permission.save')
        ->where('cate_id', '[0-9]+');

    Route::post('/remove', 'CategoryController@removePermission')
        ->name('module.permission.remove')
        ->where('cate_id', '[0-9]+');

    Route::post('/check-unit-child', 'CategoryController@getChild')->name('module.permission.get_child')->where('cate_id', '[0-9]+');

    Route::get('/get-tree-child', 'CategoryController@getTreeChild')
        ->name('module.permission.get_tree_child')
        ->where('cate_id', '[0-9]+');
});

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

Route::group(['prefix' => 'message', 'middleware' => 'auth'], function() {
    Route::get('/', 'FrontendController@index')->name('module.notify.index');
    Route::get('/getdata', 'FrontendController@getData')->name('module.notify.getdata');
    Route::get('/goto/{url_encode}', 'FrontendController@gotoUrl')->name('module.notify.goto');
    Route::post('/remove', 'FrontendController@remove')->name('module.notify.remove');
    Route::get('/view/{id}/{type}', 'FrontendController@view')
        ->name('module.notify.view')
        ->where('id', '[0-9]+')
        ->where('type', '[0-9]+');
});

Route::group(['prefix' => '/admin-cp/notify-send', 'middleware' => 'auth'], function() {
    Route::get('/', 'NotifySendController@index')->name('module.notify_send')->middleware('permission:config-notify-send');
    Route::get('/getdata', 'NotifySendController@getData')->name('module.notify_send.getdata')->middleware('permission:config-notify-send');
    Route::get('/create', 'NotifySendController@form')->name('module.notify_send.create')->middleware('permission:config-notify-send-create');
    Route::get('/edit/{id}', 'NotifySendController@form')->name('module.notify_send.edit')->where('id', '[0-9]+')->middleware('permission:config-notify-edit');
    Route::post('/save', 'NotifySendController@save')->name('module.notify_send.save')->middleware('permission:config-notify-send-create|config-notify-edit');
    Route::post('/remove', 'NotifySendController@remove')->name('module.notify_send.remove')->middleware('permission:config-notify-send-delete');
    Route::post('/ajax-isopen-publish', 'NotifySendController@ajaxIsopenPublish')->name('module.notify_send.ajax_isopen_publish')->middleware('permission:config-notify-send-enable');
    Route::post('/edit/{id}/save-object', 'NotifySendController@saveObject')->name('module.notify_send.save_object')->where('id', '[0-9]+');
    Route::get('/edit/{id}/get-object', 'NotifySendController@getObject')->name('module.notify_send.get_object')->where('id', '[0-9]+');
    Route::post('/edit/{id}/remove-object', 'NotifySendController@removeObject')->name('module.notify_send.remove_object')->where('id', '[0-9]+');
    Route::post('/edit/{id}/import-object', 'NotifySendController@importObject')->name('module.notify_send.import_object')->where('id', '[0-9]+');

    Route::post('/edit/{id}/send-object', 'NotifySendController@sendObject')->name('module.notify_send.send_object')->where('id', '[0-9]+');
    Route::post('/ajax_isopen_publish', 'NotifySendController@ajaxIsopenPublish')->name('backend.logo.ajax_isopen_publish');
});

Route::group(['prefix' => '/admin-cp/notify-template'], function() {
    Route::get('/', 'NotifyTemplateController@index')->name('module.notify.template')->middleware('permission:config-notify-template');

    Route::get('/getdata', 'NotifyTemplateController@getData')->name('module.notify.template.getdata')->middleware('permission:config-notify-template');

    Route::get('/edit/{id}', 'NotifyTemplateController@form')->name('module.notify.template.edit')->where('id', '[0-9]+')->middleware('permission:config-notify-template');

    Route::post('/save', 'NotifyTemplateController@save')->name('module.notify.template.save')->middleware('permission:config-notify-template');
});

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

Route::group(['prefix' => 'promotion', 'middleware' => 'auth'], function () {
    Route::get('/', 'frontend\PromotionController@index')->name('module.front.promotion');
    Route::get('/detail', 'frontend\PromotionController@index')->name('module.front.promotion.detail');
    Route::post('/get/{id}', 'frontend\PromotionController@get')->name('module.front.promotion.get');
});

Route::group(['prefix' => '/admin-cp/promotion', 'middleware' => 'auth'], function () {
    Route::get('/', 'PromotionController@index')->name('module.promotion');
    Route::get('/getdata', 'PromotionController@getData')->name('module.promotion.getdata');
    Route::get('/create', 'PromotionController@create')->name('module.promotion.create');
    Route::post('/save', 'PromotionController@store')->name('module.promotion.save');
    Route::get('/edit/{id}', 'PromotionController@edit')->name('module.promotion.edit')->where('id', '[0-9]+');
    Route::post('/update/{id}', 'PromotionController@update')->name('module.promotion.update');
    Route::post('/remove', 'PromotionController@remove')->name('module.promotion.remove');
    Route::post('/save_setting', 'PromotionController@saveSetting')->name('module.promotion.save_setting');
    Route::post('/delete_setting', 'PromotionController@deleteSettingMethod')->name('module.promotion.delete_setting');
    Route::get('/get_setting/{courseId}/{course_type}/{code}','PromotionController@getPromotionSetting')->name('module.promotion.get_setting');
    Route::post('/ajax-is-open', 'PromotionController@ajaxIsopenPublish')->name('module.promotion.ajax_is_open');
});

Route::group(['prefix' => '/admin-cp/promotion-group', 'middleware' => 'auth'], function () {
    Route::get('/', 'PromotionGroupController@index')->name('module.promotion.group');
    Route::get('/getdata', 'PromotionGroupController@getData')->name('module.promotion.group.getdata');
    Route::post('/save', 'PromotionGroupController@save')->name('module.promotion.group.save');
    Route::post('/edit', 'PromotionGroupController@form')->name('module.promotion.group.edit')->where('id', '[0-9]+');
    Route::post('/remove', 'PromotionGroupController@remove')->name('module.promotion.group.remove');
    Route::post('/ajax-is-open', 'PromotionGroupController@ajaxIsopenPublish')->name('module.promotion.group.ajax_is_open');
});

Route::group(['prefix' => '/admin-cp/promotion-level', 'middleware' => 'auth'], function () {
    Route::get('/', 'PromotionLevelController@index')->name('module.promotion.level');
    Route::get('/getdata', 'PromotionLevelController@getData')->name('module.promotion.level.getdata');
    Route::post('/save', 'PromotionLevelController@save')->name('module.promotion.level.save');
    Route::post('/edit', 'PromotionLevelController@form')->name('module.promotion.level.edit')->where('id', '[0-9]+');
    Route::post('/remove', 'PromotionLevelController@remove')->name('module.promotion.level.remove');
    Route::post('/ajax-is-open', 'PromotionLevelController@ajaxIsopenPublish')->name('module.promotion.level.ajax_is_open');
});

Route::group(['prefix' => '/admin-cp/promotion-orders', 'middleware' => 'auth'], function () {
    Route::get('/', 'PromotionOrdersController@index')->name('module.promotion.orders.buy');
    Route::get('/getdata', 'PromotionOrdersController@getData')->name('module.promotion.orders.buy.getdata');
    Route::get('/detail/{id}', 'PromotionOrdersController@getDetail')->name('module.promotion.orders.buy.detail');
    Route::post('/remove', 'PromotionOrdersController@remove')->name('module.promotion.orders.buy.remove');
    Route::post('/update_status/{id}', 'PromotionOrdersController@updateStatus')->name('module.promotion.orders.buy.update_status');
});

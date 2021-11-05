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

Route::prefix('faq')->group(function() {
    Route::get('/', 'FrontendController@index')->name('module.faq.frontend.index');
});

Route::group(['prefix' => '/admin-cp/faq', 'middleware' => 'auth'], function() {
    Route::get('/', 'BackendController@index')->name('module.faq')->middleware('permission:FAQ');
    Route::get('/getdata', 'BackendController@getData')->name('module.faq.getdata')->middleware('permission:FAQ');
    Route::post('/edit', 'BackendController@form')->name('module.faq.edit')->where('id', '[0-9]+')->middleware('permission:FAQ-edit');
    Route::get('/create', 'BackendController@form')->name('module.faq.create')->middleware('permission:FAQ-create');
    Route::post('/save', 'BackendController@save')->name('module.faq.save')->middleware('permission:FAQ-create|FAQ-edit');
    Route::post('/remove', 'BackendController@remove')->name('module.faq.remove')->middleware('permission:FAQ-delete');
});

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

Route::group(['prefix' => '/admin-cp/manual-api', 'middleware' => 'auth'], function () {
    Route::get('/', 'APIController@index')->name('backend.manual-api')->middleware('permission:api-manual');
    Route::post('/sync-manual', 'APIController@update')->name('backend.api.sync.manual')->middleware('permission:api-manual-sync');
});

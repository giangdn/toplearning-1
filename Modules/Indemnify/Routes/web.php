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

//Route::prefix('indemnify')->group(function() {
//    Route::get('/', 'IndemnifyController@index');
//});
Route::group(['prefix' => '/admin-cp/indemnify', 'middleware' => ['auth','permission:indemnify']], function() {
    Route::get('/', 'IndemnifyController@index')->name('module.indemnify');
    Route::get('/getdata', 'IndemnifyController@getData')->name('module.indemnify.getdata');
    Route::get('/export', 'IndemnifyController@export')->name('module.indemnify.export');

    Route::get('/user/{id}', 'UserDetailController@index')->name('module.indemnify.user')->where('id', '[0-9]+');
    Route::get('/user/{id}/getdata', 'UserDetailController@getData')->name('module.indemnify.user.getdata');
    Route::post('/user/{id}/save', 'UserDetailController@save')->name('module.indemnify.user.save');
    Route::post('/user/{id}/save-percent', 'UserDetailController@savePercent')->name('module.indemnify.user.save_percent');
    Route::post('/user/{id}/save-exemption-amount', 'UserDetailController@saveExemptionAmount')->name('module.indemnify.user.save_exemption_amount');
    Route::post('/user/{id}/save-total-cost', 'UserDetailController@saveTotalCost')->name('module.indemnify.user.save_total_cost');
    Route::post('/user/{id}/save-contract', 'UserDetailController@saveContract')->name('module.indemnify.user.save_contract');
    Route::post('/user/{id}/save-compensated', 'UserDetailController@saveCompensated')->name('module.indemnify.user.save_compensated');
});

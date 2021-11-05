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

Route::group(['prefix'=>'/messages', 'middleware' => 'auth'], function() {
    Route::post('/user', 'MessagesController@store');
    Route::post('/bot', 'MessagesController@botProcess');

});
Route::group(['prefix' => '/messages/user','middleware'=>'auth'], function () {
    Route::get('/', 'MessagesController@getMessageUser');
    Route::post('/', 'MessagesController@saveMessageUser');
});

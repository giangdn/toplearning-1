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

Route::group(['prefix'=>'/admin-cp/table-manager','middleware' => 'auth'], function() {
    Route::get('/', 'TableManagerController@index')->name('module.tablemanager.index');
    Route::post('/save', 'TableManagerController@store')->name('module.tablemanager.save');
    Route::put('/update/{id}', 'TableManagerController@update')->name('module.tablemanager.update')->where('id','[0-9]+');
    Route::get('/edit/{id}', 'TableManagerController@edit')->name('module.tablemanager.edit')->where('id','[0-9]+');
    Route::delete('/delete', 'TableManagerController@destroy')->name('module.tablemanager.delete');
});

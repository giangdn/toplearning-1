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


Route::group(['prefix'=>'/admin-cp','middleware' => 'auth','namespace'=>'Backend'], function() {
    Route::resource('/subjectregister','SubjectRegisterController');
    Route::get('/export', 'SubjectRegisterExportController@export')->name('backend.subjectregister.export');
});

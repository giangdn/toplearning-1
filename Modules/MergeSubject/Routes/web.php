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

Route::group(['prefix'=>'/admin-cp/mergesubject','middleware' => 'auth'], function() {
    Route::get('/', 'MergeSubjectController@index')->name('module.mergesubject.index');
    Route::get('/getData', 'MergeSubjectController@getData')->name('module.mergesubject.getData');
    Route::get('/create', 'MergeSubjectController@create')->name('module.mergesubject.create');
    Route::get('/edit/{id}', 'MergeSubjectController@edit')->name('module.mergesubject.edit')->where('id', '[0-9]+');
    Route::post('/update/{id}', 'MergeSubjectController@update')->name('module.mergesubject.update')->where('id', '[0-9]+');
    Route::post('/store', 'MergeSubjectController@store')->name('module.mergesubject.store');
    Route::post('/remove', 'MergeSubjectController@destroy')->name('module.mergesubject.remove');
    Route::post('/approve', 'MergeSubjectController@approve')->name('module.mergesubject.approve');
    Route::post('/import', 'MergeSubjectController@import')->name('module.mergesubject.import');
    Route::get('/logs', 'MergeSubjectController@showLogs')->name('module.mergesubject.logs');
    Route::get('/getlogs', 'MergeSubjectController@getLogs')->name('module.mergesubject.logs.getData');
});

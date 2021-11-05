<?php

Route::group(['prefix'=>'/admin-cp/splitsubject','middleware' => 'auth'], function() {
    Route::get('/', 'SplitSubjectController@index')->name('module.splitsubject.index');
    Route::get('/getData', 'SplitSubjectController@getData')->name('module.splitsubject.getData');
    Route::get('/create', 'SplitSubjectController@create')->name('module.splitsubject.create');
    Route::get('/edit/{id}', 'SplitSubjectController@edit')->name('module.splitsubject.edit')->where('id', '[0-9]+');
    Route::post('/update/{id}', 'SplitSubjectController@update')->name('module.splitsubject.update')->where('id', '[0-9]+');
    Route::post('/store', 'SplitSubjectController@store')->name('module.splitsubject.store');
    Route::post('/remove', 'SplitSubjectController@destroy')->name('module.splitsubject.remove');
    Route::post('/approve', 'SplitSubjectController@approve')->name('module.splitsubject.approve');
    Route::post('/import', 'SplitSubjectController@import')->name('module.splitsubject.import');
    Route::get('/logs', 'SplitSubjectController@showLogs')->name('module.splitsubject.logs');
    Route::get('/getlogs', 'SplitSubjectController@getLogs')->name('module.splitsubject.logs.getData');
});

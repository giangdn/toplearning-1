<?php


Route::group(['prefix' => '/admin-cp/cron', 'middleware' => 'auth'], function () {
    Route::get('/', 'CronController@index')->name('module.cron');
    Route::get('/cron', 'CronController@getData')->name('module.cron.getData');
//    Route::get('/edit/{user_id}/getData', 'CronController@getTrainingProcessUser')->name('module.cron.user.getData')->where('user_id', '[0-9]+');
    Route::get('/create', 'CronController@create')->name('module.cron.create');
    Route::get('/edit/{id}', 'CronController@edit')->name('module.cron.edit')->where('id', '[0-9]+');
    Route::put('/update/{id}', 'CronController@update')->name('module.cron.update')->where('id', '[0-9]+');
    Route::post('/store', 'CronController@store')->name('module.cron.store');
    Route::delete('/remove', 'CronController@destroy')->name('module.cron.remove');

});

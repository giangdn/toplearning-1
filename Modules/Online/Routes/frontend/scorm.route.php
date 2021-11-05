<?php

Route::group(['prefix' => '/online/scorm', 'middleware' => ['quiz.secondary','auth']], function () {
    Route::get('/lesson/{id}/{activity_id}/{lesson}', 'Frontend\ScormController@index')->name('module.online.scorm')->where('id', '[0-9]+')->where('activity_id', '[0-9]+');

    // Route::get('/{id}/{activity_id}/', 'Frontend\ScormController@play')->name('module.online.scorm.play')->where('id', '[0-9]+')->where('activity_id', '[0-9]+');

    Route::post('/{id}/{activity_id}/', 'Frontend\ScormController@play')->name('module.online.scorm.play')->where('id', '[0-9]+')->where('activity_id', '[0-9]+');

    Route::get('/{id}/{activity_id}/{attempt_id}', 'Frontend\ScormController@player')->name('module.online.scorm.player')->where('id', '[0-9]+')->where('activity_id', '[0-9]+')->where('attempt_id', '[0-9]+');

    Route::get('/attempts/{activity_id}', 'Frontend\ScormController@getDataAttempt')->name('module.online.attempts')->where('activity_id', '[0-9]+');

    Route::get('/redirect', 'Frontend\ScormController@redirect')->name('module.online.scorm.player.redirect');

    Route::match(['get', 'post'], '/check-net', 'Frontend\ScormController@checkNet')->name('module.online.scorm.player.checknet');

    Route::post('/save', 'Frontend\ScormController@save')->name('module.online.scorm.player.save');

});

<?php

Route::group(['prefix' => '/admin-cp', 'middleware' => 'auth'], function() {
    Route::get('/config-refer', 'ConfigController@formRefer')->name('backend.config.refer')->middleware('permission:config-point-refer');
    Route::post('/config-refer/save', 'ConfigController@saveRefer')->name('backend.config.refer.save')->middleware('permission:config-point-refer-save');
    Route::get('/config-email', 'ConfigController@formEmail')->name('backend.config.email')->middleware('permission:config-email');
    Route::post('/config-email', 'ConfigController@saveEmail')->name('backend.config.email.save')->middleware('permission:config-email-save');
    Route::post('/config-email/send-test', 'ConfigController@testSendMail')->name('backend.config.email.test')->middleware('permission:config-email-save');
});

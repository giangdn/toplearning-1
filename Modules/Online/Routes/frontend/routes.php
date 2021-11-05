<?php

require_once (__DIR__ . '/scorm.route.php');
require_once (__DIR__ . '/classroom.route.php');

Route::group(['prefix' => '/online', 'middleware' => ['quiz.secondary','auth']], function() {
    Route::get('/', 'FrontendController@index')->name('module.online');

    Route::get('/getdata', 'FrontendController@getData')->name('module.online.getdata');

    Route::post('/rating/{id}', 'FrontendController@rating')->name('module.online.rating')->where('id', '[0-9]+');

    Route::post('/register-course/{id}', 'FrontendController@registerCourse')->name('module.online.register_course')->where('id', '[0-9]+');

    Route::post('/commnent/{id}', 'FrontendController@comment')->name('module.online.comment')->where('id', '[0-9]+');

    Route::get('/search','FrontendController@search')->name('module.online.search');

    Route::get('/embed/{id}/{lesson}','EmbedUrlController@index')->name('module.online.embed');

    Route::get('/view-pdf/{id}', 'FrontendController@viewPDF')->name('module.online.view_pdf')->where('id', '[0-9]+');

    Route::get('/view-video/{file}', 'FrontendController@viewVideo')->name('module.online.view_video');

    Route::get('/tutorial-view-pdf/{id}/{key}', 'FrontendController@tutorialViewPDF')->name('module.online.tutorial.view_pdf')->where('id', '[0-9]+');

    Route::post('/referer/qrcode/{id}', 'FrontendController@showModalQrcodeReferer')->name('frontend.online.referer.show_modal')->where('id', '[0-9]+');

    Route::get('/get-object/{id}', 'FrontendController@getObject')->name('frontend.online.get_object')->where('id', '[0-9]+');

    Route::get('/getdata-rating-level/{id}', 'FrontendController@getDataRatingLevel')
        ->name('module.online.detail.rating_level.getdata')
        ->where('id', '[0-9]+');
});
Route::group(['prefix' => '/online/detail-online', 'middleware' => ['quiz.secondary','auth']], function () {
    Route::get('/{id}', 'FrontendController@detail2')->name('module.online.detail_online')->where('id', '[0-9]+');
});

Route::group(['prefix' => '/online/detail', 'middleware' => ['quiz.secondary','auth']], function () {
    Route::get('/{id}', 'FrontendController@detail')->name('module.online.detail')->where('id', '[0-9]+');

    Route::post('/share-course/{id}/{type}', 'FrontendController@shareCourse')->name('module.online.detail.share_course')->where('id', '[0-9]+')->where('type', '[0-9]+');

    Route::get('/{id}/activity/{aid}/{lesson}', 'FrontendController@goActivity')->name('module.online.goactivity')->where('id', '[0-9]+')->where('aid', '[0-9]+');

    Route::post('/ajax-activity', 'FrontendController@ajaxActivity')->name('module.online.detail.ajax_activity');

    Route::post('/ajax-run-last-quiz', 'FrontendController@ajaxRunLastQuiz')->name('module.online.detail.ajax_run_last_quiz');
});


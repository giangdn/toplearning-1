<?php

require_once (__DIR__ . '/quiz.route.php');

Route::group(['prefix' => '/quiz', 'middleware' => 'quiz.secondary'], function() {
    Route::get('/', 'FrontendController@index')->name('module.quiz');

    Route::get('/getdata', 'FrontendController@getData')->name('module.quiz.frontend.getdata');

    Route::post('/save-note-quiz', 'FrontendController@saveNoteQuiz')->name('module.quiz.frontend.save_note_quiz');
});

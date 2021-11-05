<?php

Route::group(['prefix' => '/quiz-mobile/{quiz_id}/{part_id}', 'middleware' => 'quiz.secondary'], function() {
    Route::get('/', 'Mobile\QuizController@index')
        ->name('module.quiz_mobile.doquiz.index')
        ->where('quiz_id', '[0-9]+')
        ->where('part_id', '[0-9]+');

    Route::get('/index-by-online', 'Mobile\QuizController@indexByOnline')
        ->name('module.quiz_mobile.doquiz.index_by_online')
        ->where('quiz_id', '[0-9]+')
        ->where('part_id', '[0-9]+');

    Route::post('/create-quiz', 'Mobile\QuizController@createQuiz')
        ->name('module.quiz_mobile.doquiz.create_quiz')
        ->where('quiz_id', '[0-9]+')
        ->where('part_id', '[0-9]+');

    Route::post('/user-review-quiz', 'Mobile\QuizController@userReviewQuiz')
        ->name('module.quiz_mobile.doquiz.user_review_quiz')
        ->where('quiz_id', '[0-9]+')
        ->where('part_id', '[0-9]+');

    Route::get('/attempt-history', 'Mobile\QuizController@getAttemptHistory')
        ->name('module.quiz_mobile.doquiz.attempt_history')
        ->where('quiz_id', '[0-9]+')
        ->where('part_id', '[0-9]+');
});

Route::group(['prefix' => '/quiz-mobile/{quiz_id}/{part_id}/do-quiz/{attempt_id}', 'middleware' => 'quiz.secondary'], function() {
    Route::get('/', 'Mobile\DoQuizController@index')
        ->name('module.quiz_mobile.doquiz.do_quiz')
        ->where('quiz_id', '[0-9]+')
        ->where('part_id', '[0-9]+')
        ->where('attempt_id', '[0-9]+');

    Route::post('/question-quiz', 'Mobile\DoQuizController@getQuestionQuiz')
        ->name('module.quiz_mobile.doquiz.question_quiz')
        ->where('quiz_id', '[0-9]+')
        ->where('part_id', '[0-9]+')
        ->where('attempt_id', '[0-9]+');

    Route::post('/save', 'Mobile\DoQuizController@saveUserQuiz')
        ->name('module.quiz_mobile.doquiz.save')
        ->where('quiz_id', '[0-9]+')
        ->where('part_id', '[0-9]+')
        ->where('attempt_id', '[0-9]+');

    Route::post('/saveflag', 'Mobile\DoQuizController@saveUserFlag')
        ->name('module.quiz_mobile.doquiz.saveflag')
        ->where('quiz_id', '[0-9]+')
        ->where('part_id', '[0-9]+')
        ->where('attempt_id', '[0-9]+');

    Route::post('/save/file', 'Mobile\DoQuizController@saveFileQuestionEssay')
        ->name('module.quiz_mobile.doquiz.save.file_essay')
        ->where('quiz_id', '[0-9]+')
        ->where('part_id', '[0-9]+')
        ->where('attempt_id', '[0-9]+');

    Route::post('/submit', 'Mobile\DoQuizController@submitQuiz')
        ->name('module.quiz_mobile.doquiz.submit')
        ->where('quiz_id', '[0-9]+')
        ->where('part_id', '[0-9]+')
        ->where('attempt_id', '[0-9]+');

    Route::post('/complete', 'Mobile\DoQuizController@completeQuiz')
        ->name('module.quiz_mobile.doquiz.complete')
        ->where('quiz_id', '[0-9]+')
        ->where('part_id', '[0-9]+')
        ->where('attempt_id', '[0-9]+');

    Route::post('/save-webcam', 'Mobile\DoQuizController@saveImage')
        ->name('module.quiz_mobile.doquiz.save_webcam')
        ->where('quiz_id', '[0-9]+')
        ->where('part_id', '[0-9]+')
        ->where('attempt_id', '[0-9]+');

    Route::post('/save-error-user', 'Mobile\DoQuizController@saveErrorUser')
        ->name('module.quiz_mobile.doquiz.save_error_user')
        ->where('quiz_id', '[0-9]+')
        ->where('part_id', '[0-9]+')
        ->where('attempt_id', '[0-9]+');

    Route::post('/check-info-user', 'Mobile\DoQuizController@checkUserQuestion')
        ->name('module.quiz_mobile.doquiz.check_user_question')
        ->where('quiz_id', '[0-9]+')
        ->where('part_id', '[0-9]+')
        ->where('attempt_id', '[0-9]+');
});

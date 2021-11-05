<?php

Route::group(['prefix' => '/quiz/{quiz_id}/{part_id}', 'middleware' => 'quiz.secondary'], function() {
    Route::get('/', 'Quiz\QuizController@index')
        ->name('module.quiz.doquiz.index')
        ->where('quiz_id', '[0-9]+')
        ->where('part_id', '[0-9]+');

    Route::get('/index_by_online', 'Quiz\QuizController@indexByOnline')
        ->name('module.quiz.doquiz.index_by_online')
        ->where('quiz_id', '[0-9]+')
        ->where('part_id', '[0-9]+');

    Route::post('/create-quiz', 'Quiz\QuizController@createQuiz')
        ->name('module.quiz.doquiz.create_quiz')
        ->where('quiz_id', '[0-9]+')
        ->where('part_id', '[0-9]+');

    Route::get('/attempt-history', 'Quiz\QuizController@getAttemptHistory')
        ->name('module.quiz.doquiz.attempt_history')
        ->where('quiz_id', '[0-9]+')
        ->where('part_id', '[0-9]+');

    Route::post('/user-review-quiz', 'Quiz\QuizController@userReviewQuiz')
        ->name('module.quiz.doquiz.user_review_quiz')
        ->where('quiz_id', '[0-9]+')
        ->where('part_id', '[0-9]+');
});

Route::group(['prefix' => '/quiz/{quiz_id}/{part_id}/do-quiz/{attempt_id}', 'middleware' => 'quiz.secondary'], function() {
    Route::get('/', 'Quiz\DoQuizController@index')
        ->name('module.quiz.doquiz.do_quiz')
        ->where('quiz_id', '[0-9]+')
        ->where('part_id', '[0-9]+')
        ->where('attempt_id', '[0-9]+');

    Route::post('/question-quiz', 'Quiz\DoQuizController@getQuestionQuiz')
        ->name('module.quiz.doquiz.question_quiz')
        ->where('quiz_id', '[0-9]+')
        ->where('part_id', '[0-9]+')
        ->where('attempt_id', '[0-9]+');

    Route::post('/save', 'Quiz\DoQuizController@saveUserQuiz')
        ->name('module.quiz.doquiz.save')
        ->where('quiz_id', '[0-9]+')
        ->where('part_id', '[0-9]+')
        ->where('attempt_id', '[0-9]+');

    Route::post('/saveflag', 'Quiz\DoQuizController@saveUserFlag')
        ->name('module.quiz.doquiz.saveflag')
        ->where('quiz_id', '[0-9]+')
        ->where('part_id', '[0-9]+')
        ->where('attempt_id', '[0-9]+');

    Route::post('/save/file', 'Quiz\DoQuizController@saveFileQuestionEssay')
        ->name('module.quiz.doquiz.save.file_essay')
        ->where('quiz_id', '[0-9]+')
        ->where('part_id', '[0-9]+')
        ->where('attempt_id', '[0-9]+');

    Route::post('/submit', 'Quiz\DoQuizController@submitQuiz')
        ->name('module.quiz.doquiz.submit')
        ->where('quiz_id', '[0-9]+')
        ->where('part_id', '[0-9]+')
        ->where('attempt_id', '[0-9]+');

    Route::post('/complete', 'Quiz\DoQuizController@completeQuiz')
        ->name('module.quiz.doquiz.complete')
        ->where('quiz_id', '[0-9]+')
        ->where('part_id', '[0-9]+')
        ->where('attempt_id', '[0-9]+');

    Route::post('/save-webcam', 'Quiz\DoQuizController@saveImage')
        ->name('module.quiz.doquiz.save_webcam')
        ->where('quiz_id', '[0-9]+')
        ->where('part_id', '[0-9]+')
        ->where('attempt_id', '[0-9]+');

    Route::post('/save-error-user', 'Quiz\DoQuizController@saveErrorUser')
        ->name('module.quiz.doquiz.save_error_user')
        ->where('quiz_id', '[0-9]+')
        ->where('part_id', '[0-9]+')
        ->where('attempt_id', '[0-9]+');

    Route::post('/check-info-user', 'Quiz\DoQuizController@checkUserQuestion')
        ->name('module.quiz.doquiz.check_user_question')
        ->where('quiz_id', '[0-9]+')
        ->where('part_id', '[0-9]+')
        ->where('attempt_id', '[0-9]+');
});

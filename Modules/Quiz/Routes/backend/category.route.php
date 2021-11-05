<?php

Route::group(['prefix' => '/admin-cp/category/quiz-type', 'middleware' => 'auth'], function() {
    Route::get('/', 'Backend\QuizTypeController@index')->name('module.quiz.type.manager');

    Route::post('/edit', 'Backend\QuizTypeController@form')->name('module.quiz.type.edit')->where('id', '[0-9]+');

    Route::get('/getdata', 'Backend\QuizTypeController@getData')->name('module.quiz.type.getdata');

    Route::post('/save', 'Backend\QuizTypeController@save')->name('module.quiz.type.save');

    Route::post('/remove', 'Backend\QuizTypeController@remove')->name('module.quiz.type.remove');
});

Route::group(['prefix' => '/admin-cp/quiz/user-secondary/{id}', 'middleware' => 'auth'], function() {
    Route::get('/', 'Backend\RegisterController@indexSecondary')
        ->name('module.quiz.register.user_secondary')
        ->where('id', '[0-9]+');

    Route::get('/getdata', 'Backend\RegisterController@getDataSecondary')
        ->name('module.quiz.register.user_secondary.getdata');

    Route::get('/getDataNotUserSecondary', 'Backend\RegisterController@getDataNotUserSecondary')
        ->name('module.quiz.register.user_secondary.getDataNotUserSecondary')
        ->where('id', '[0-9]+');

    Route::get('/create', 'Backend\RegisterController@formSecondary')
        ->name('module.quiz.register.user_secondary.create')
        ->where('id', '[0-9]+');

    Route::post('/save', 'Backend\RegisterController@saveSecondary')
        ->name('module.quiz.register.user_secondary.save')
        ->where('id', '[0-9]+');

    Route::post('/remove', 'Backend\RegisterController@removeSecondary')
        ->name('module.quiz.register.user_secondary.remove')
        ->where('id', '[0-9]+');

    Route::post('/import-register', 'Backend\RegisterController@importRegisterSecondary')
        ->name('module.quiz.register.user_secondary.import_register')
        ->where('id', '[0-9]+');

    Route::get('/export-register-secondary', 'Backend\RegisterController@exportRegisterSecondary')
        ->name('module.quiz.register.export_register_secondary')
        ->where('id', '[0-9]+');

    Route::get('/create-new-user-secondary', 'Backend\RegisterController@createNewSecondary')
        ->name('module.quiz.register.user_secondary.create_new_user')
        ->where('id', '[0-9]+');

    Route::post('/save-new-user-secondary', 'Backend\RegisterController@saveNewSecondary')
        ->name('module.quiz.register.user_secondary.save_new_user')
        ->where('id', '[0-9]+');
});

Route::group(['prefix' => '/admin-cp/user-secondary', 'middleware' => 'auth'], function() {
    Route::get('/', 'Backend\QuizUserSecondaryController@index')->name('module.quiz.user_secondary');

    Route::get('/getdata', 'Backend\QuizUserSecondaryController@getData')->name('module.quiz.user_secondary.getdata');

    Route::post('/edit', 'Backend\QuizUserSecondaryController@form')->name('module.quiz.user_secondary.edit')->where('id', '[0-9]+');

    Route::post('/save', 'Backend\QuizUserSecondaryController@save')->name('module.quiz.user_secondary.save');

    Route::post('/remove', 'Backend\QuizUserSecondaryController@remove')->name('module.quiz.user_secondary.remove');

    Route::post('/import-user-secondary', 'Backend\QuizUserSecondaryController@importUserSecondary')->name('module.quiz.user_secondary.import');

    Route::post('/lock', 'Backend\QuizUserSecondaryController@lockUserSecond')->name('module.quiz.user_secondary.lock');
});

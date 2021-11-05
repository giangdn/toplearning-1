<?php

Route::group(['prefix' => '/admin-cp/question-lib', 'middleware' => 'auth'], function() {
    Route::get('/', 'Backend\QuestionlibController@index')->name('module.quiz.questionlib');

    Route::get('/getdata-category', 'Backend\QuestionlibController@getDataCategory')->name('module.quiz.questionlib.getdata_category');

    Route::post('/save-category', 'Backend\QuestionlibController@saveCategory')->name('module.quiz.questionlib.save_category');

    Route::post('/remove-category', 'Backend\QuestionlibController@removeCategory')->name('module.quiz.questionlib.remove_category');

    Route::post('/get-modal', 'Backend\QuestionlibController@showModal')->name('module.quiz.questionlib.get_modal');

    Route::post('/save-status-category', 'Backend\QuestionlibController@saveStatusCategory')
        ->name('module.quiz.questionlib.save_status_category');
});

Route::group(['prefix' => '/admin-cp/question-lib/{id}', 'middleware' => 'auth'], function() {
    Route::get('/', 'Backend\QuestionlibController@question')
        ->name('module.quiz.questionlib.question')
        ->where('id', '[0-9]+');

    Route::get('/getdata-question', 'Backend\QuestionlibController@getDataQuestion')
        ->name('module.quiz.questionlib.question.getdata')
        ->where('id', '[0-9]+');

    Route::get('/create', 'Backend\QuestionlibController@questionForm')
        ->name('module.quiz.questionlib.question.create')
        ->where('id', '[0-9]+');

    Route::get('/edit/{qid}', 'Backend\QuestionlibController@questionForm')
        ->name('module.quiz.questionlib.question.edit')
        ->where('id', '[0-9]+')
        ->where('qid', '[0-9]+');

    Route::post('/save-question', 'Backend\QuestionlibController@saveQuestion')
        ->name('module.quiz.questionlib.save_question')
        ->where('id', '[0-9]+');

    Route::post('/remove-question', 'Backend\QuestionlibController@removeQuestion')
        ->name('module.quiz.questionlib.remove_question')
        ->where('id', '[0-9]+');

    Route::post('/remove-question-answer', 'Backend\QuestionlibController@removeQuestionAnswer')
        ->name('module.quiz.questionlib.remove_question_answer')
        ->where('id', '[0-9]+');

    Route::post('/ajax-status', 'Backend\QuestionlibController@saveStatus')
        ->name('module.quiz.questionlib.ajax_status')
        ->where('id', '[0-9]+');

    Route::get('/user', 'Backend\QuestionlibController@cateUser')
        ->name('module.quiz.questionlib.cate_user')
        ->where('id', '[0-9]+');

    Route::get('/get-user', 'Backend\QuestionlibController@getCateUser')
        ->name('module.quiz.questionlib.get_cate_user')
        ->where('id', '[0-9]+');

    Route::post('/save-user', 'Backend\QuestionlibController@saveCateUser')
        ->name('module.quiz.questionlib.save_cate_user')
        ->where('id', '[0-9]+');

    Route::post('/remove-user', 'Backend\QuestionlibController@removeCateUser')
        ->name('module.quiz.questionlib.remove_cate_user')
        ->where('id', '[0-9]+');

    Route::post('/import-question', 'Backend\QuestionlibController@importQuestion')
        ->name('module.quiz.questionlib.import_question')
        ->where('id', '[0-9]+');

    Route::get('/export-word-question', 'Backend\QuestionlibController@exportWordQuestion')
        ->name('module.quiz.questionlib.export_word_question')
        ->where('id', '[0-9]+');

    Route::get('/export-excel-question', 'Backend\QuestionlibController@exportExcelQuestion')
        ->name('module.quiz.questionlib.export_excel_question')
        ->where('id', '[0-9]+');

    Route::post('/view-question/{qid}', 'Backend\QuestionlibController@viewQuestion')
        ->name('module.quiz.questionlib.view_question')
        ->where('id', '[0-9]+')
        ->where('qid', '[0-9]+');
});

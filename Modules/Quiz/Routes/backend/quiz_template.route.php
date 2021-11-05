<?php

Route::group(['prefix' => '/admin-cp/quiz-template', 'middleware' => 'auth'], function() {
    Route::get('/', 'Backend\QuizTemplatesController@index')->name('module.quiz_template.manager');

    Route::get('/getdata', 'Backend\QuizTemplatesController@getData')->name('module.quiz_template.getdata');

    Route::post('/save', 'Backend\QuizTemplatesController@save')->name('module.quiz_template.save');

    Route::get('/edit/{id}', 'Backend\QuizTemplatesController@form')->name('module.quiz_template.edit')
        ->where('id', '[0-9]+');

    Route::post('/edit/{id}/save-rank', 'Backend\QuizTemplatesController@saveRank')->name('module.quiz_template.edit.saverank')
        ->where('id', '[0-9]+');

    Route::get('/edit/{id}/get-rank', 'Backend\QuizTemplatesController@getDataRank')->name('module.quiz_template.edit.getrank')
        ->where('id', '[0-9]+');

    Route::post('/edit/{id}/remove-rank', 'Backend\QuizTemplatesController@removeRank')->name('module.quiz_template.edit.removerank')
        ->where('id', '[0-9]+');

    Route::post('/edit/{id}/save-setting', 'Backend\QuizTemplatesController@saveSetting')->name('module.quiz_template.save_setting')
        ->where('id', '[0-9]+');

    Route::get('/get-unit', 'Backend\QuizTemplatesController@loadUnit')->name('module.quiz_template.edit.getunit');

    Route::get('/create', 'Backend\QuizTemplatesController@form')->name('module.quiz_template.create');

    Route::post('/remove', 'Backend\QuizTemplatesController@remove')->name('module.quiz_template.remove');

    Route::post('/ajax-is-open', 'Backend\QuizTemplatesController@saveIsOpen')->name('module.quiz_template.ajax_is_open');

    Route::post('/ajax-status', 'Backend\QuizTemplatesController@saveStatus')->name('module.quiz_template.ajax_status');

    Route::post('/get-user-create-quiz-template/{user_id}', 'Backend\QuizTemplatesController@getUserCreateQuiz')
        ->name('module.quiz_template.get_user_create_quiz_template')
        ->where('user_id', '[0-9]+');

    Route::get('/export/{id}', 'Backend\QuizTemplatesController@exportQuiz')->name('module.quiz_template.export_quiz');
});

Route::group(['prefix' => '/admin-cp/quiz-template/{id}', 'middleware' => 'auth'], function() {

    Route::get('/add-question', 'Backend\QuizTemplateQuestionController@index')
        ->name('module.quiz_template.question')
        ->where('id', '[0-9]+');

    Route::post('/get-modal-quiz-template-question', 'Backend\QuizTemplateQuestionController@showModal')
        ->name('module.quiz_template.question.get_modal_quiz_template_question')
        ->where('id', '[0-9]+');

    Route::post('/get-modal-question-category', 'Backend\QuizTemplateQuestionController@showModalCategory')
        ->name('module.quiz_template.question.get_modal_question_category')
        ->where('id', '[0-9]+');

    Route::post('/save-question-random', 'Backend\QuizTemplateQuestionController@saveQuestionRandom')
        ->name('module.quiz_template.question.save_question_random')
        ->where('id', '[0-9]+');

    Route::post('/save-category-question', 'Backend\QuizTemplateQuestionController@saveCategoryQuestion')
        ->name('module.quiz_template.question.save_category_question')
        ->where('id', '[0-9]+');

    Route::get('/getdata-question', 'Backend\QuizTemplateQuestionController@getDataQuestion')
        ->name('module.quiz_template.question.getdata_question')
        ->where('id', '[0-9]+');

    Route::post('/remove-quiz-template-question', 'Backend\QuizTemplateQuestionController@removeQuizQuestion')
        ->name('module.quiz_template.question.remove_quiz_template_question')
        ->where('id', '[0-9]+');

    Route::post('/update-max-score', 'Backend\QuizTemplateQuestionController@updateMaxScore')
        ->name('module.quiz_template.question.update_max_score')
        ->where('id', '[0-9]+');

    Route::post('/update-num-order', 'Backend\QuizTemplateQuestionController@updateNumOrder')
        ->name('module.quiz_template.question.update_num_order')
        ->where('id', '[0-9]+');

    Route::post('/modal-qqcategory', 'Backend\QuizTemplateQuestionController@showModalQQCategory')
        ->name('module.quiz_template.question.modal_qqcategory')
        ->where('id', '[0-9]+');

    Route::post('/save-qqcategory', 'Backend\QuizTemplateQuestionController@saveQQCategory')
        ->name('module.quiz_template.question.save_qqcategory')
        ->where('id', '[0-9]+');

    Route::post('/remove-qqcategory', 'Backend\QuizTemplateQuestionController@removeQQCategory')
        ->name('module.quiz_template.question.remove_qqcategory')
        ->where('id', '[0-9]+');
});

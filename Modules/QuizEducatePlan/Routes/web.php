<?php
Route::group(['prefix' => '/admin-cp/quiz-educate-plan', 'middleware' => 'auth'], function() {

    Route::get('/suggest', 'QuizEducatePlanController@indexSuggest')->name('module.quiz_educate_plan_suggest');
    Route::post('/suggest/save', 'QuizEducatePlanController@saveSuggest')->name('module.quiz_educate_plan_suggest.save');
    Route::get('/suggest/getdata', 'QuizEducatePlanController@getDataSuggest')->name('module.quiz_educate_plan_suggest.getdata');
    Route::post('/suggest/remove', 'QuizEducatePlanController@removeSuggest')->name('module.quiz_educate_plan_suggest.remove');

    Route::get('/{idsg}', 'QuizEducatePlanController@index')->name('module.quiz_educate_plan.index');

    Route::get('/{idsg}/getdata', 'QuizEducatePlanController@getData')->name('module.quiz_educate_plan.getdata');

    Route::get('/{idsg}/edit/{id}', 'QuizEducatePlanController@form')->name('module.quiz_educate_plan.edit');

    Route::get('/{idsg}/create', 'QuizEducatePlanController@form')->name('module.quiz_educate_plan.create');

    Route::post('/{idsg}/save', 'QuizEducatePlanController@save')->name('module.quiz_educate_plan.save');

    Route::post('/edit/{id}/save-part', 'QuizEducatePlanController@savePart')->name('module.quiz_educate_plan.edit.savepart')
        ->where('id', '[0-9]+');

    Route::get('/edit/{id}/get-part', 'QuizEducatePlanController@getDataPart')->name('module.quiz_educate_plan.edit.getpart')
        ->where('id', '[0-9]+');

    Route::post('/edit/{id}/remove-part', 'QuizEducatePlanController@removePart')->name('module.quiz_educate_plan.edit.removepart')
        ->where('id', '[0-9]+');

    Route::post('/edit/{id}/save-rank', 'QuizEducatePlanController@saveRank')->name('module.quiz_educate_plan.edit.saverank')
        ->where('id', '[0-9]+');

    Route::get('/edit/{id}/get-rank', 'QuizEducatePlanController@getDataRank')->name('module.quiz_educate_plan.edit.getrank')
        ->where('id', '[0-9]+');

    Route::post('/edit/{id}/remove-rank', 'QuizEducatePlanController@removeRank')->name('module.quiz_educate_plan.edit.removerank')
        ->where('id', '[0-9]+');

    Route::post('/edit/{id}/save-teacher', 'QuizEducatePlanController@saveTeacher')->name('module.quiz_educate_plan.save_teacher')
        ->where('id', '[0-9]+');

    Route::post('/edit/{id}/save-setting', 'QuizEducatePlanController@saveSetting')->name('module.quiz_educate_plan.save_setting')
        ->where('id', '[0-9]+');

    Route::post('/{idsg}/remove', 'QuizEducatePlanController@remove')->name('module.quiz_educate_plan.remove');

    Route::post('/approve', 'QuizEducatePlanController@approve')->name('module.quiz_educate_plan.approve');

    Route::post('/{idsg}/convert', 'QuizEducatePlanController@convert')->name('module.quiz_educate_plan.convert');
});

Route::group(['prefix' => '/admin-cp/quiz-educate-plan/{idsg}/{id}', 'middleware' => 'auth'], function() {

    Route::get('/add-question', 'QuizEducatePlanQuestionController@index')
        ->name('module.quiz_plan.question')
        ->where('id', '[0-9]+');

    Route::post('/get-modal-quiz-question', 'QuizQuestionController@showModal')
        ->name('module.quiz_plan.question.get_modal_quiz_question')
        ->where('id', '[0-9]+');

  Route::post('/get-modal-question-category', 'QuizQuestionController@showModalCategory')
        ->name('module.quiz_plan.question.get_modal_question_category')
        ->where('id', '[0-9]+');

    Route::post('/save-question-random', 'QuizQuestionController@saveQuestionRandom')
        ->name('module.quiz_plan.question.save_question_random')
        ->where('id', '[0-9]+');

    Route::post('/save-category-question', 'QuizQuestionController@saveCategoryQuestion')
        ->name('module.quiz_plan.question.save_category_question')
        ->where('id', '[0-9]+');

    /*


    Route::get('/getdata-question', 'Backend\QuizQuestionController@getDataQuestion')
        ->name('module.quiz.question.getdata_question')
        ->where('id', '[0-9]+');

    Route::post('/remove-quiz-question', 'Backend\QuizQuestionController@removeQuizQuestion')
        ->name('module.quiz.question.remove_quiz_question')
        ->where('id', '[0-9]+');

    Route::post('/update-max-score', 'Backend\QuizQuestionController@updateMaxScore')
        ->name('module.quiz.question.update_max_score')
        ->where('id', '[0-9]+');

    Route::post('/update-num-order', 'Backend\QuizQuestionController@updateNumOrder')
        ->name('module.quiz.question.update_num_order')
        ->where('id', '[0-9]+');

    Route::post('/modal-qqcategory', 'Backend\QuizQuestionController@showModalQQCategory')
        ->name('module.quiz.question.modal_qqcategory')
        ->where('id', '[0-9]+');

    Route::post('/save-qqcategory', 'Backend\QuizQuestionController@saveQQCategory')
        ->name('module.quiz.question.save_qqcategory')
        ->where('id', '[0-9]+');

    Route::post('/remove-qqcategory', 'Backend\QuizQuestionController@removeQQCategory')
        ->name('module.quiz.question.remove_qqcategory')
        ->where('id', '[0-9]+');*/
});

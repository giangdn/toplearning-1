<?php

Route::group(['prefix' => '/training-by-title'], function() {
    Route::get('/', 'Frontend\TrainingByTitleController@index')->name('module.frontend.training_by_title');
});

Route::group(['prefix' => '/admin-cp/training-by-title'], function() {
    Route::get('/', 'Backend\TrainingByTitleController@index')->name('module.training_by_title');
    Route::post('/save', 'Backend\TrainingByTitleController@save')->name('module.training_by_title.save');
    Route::get('/getdata', 'Backend\TrainingByTitleController@getData')->name('module.training_by_title.getdata');
    Route::post('/remove', 'Backend\TrainingByTitleController@remove')->name('module.training_by_title.remove');

    Route::post('/ajax-copy', 'Backend\TrainingByTitleController@copy')->name('module.training_by_title.ajax_copy');
    Route::post('/ajax-check-training-roadmap', 'Backend\TrainingByTitleController@checkTrainingByTitle')->name('module.training_by_title.ajax_check_training_by_title');

    Route::post('/import', 'Backend\TrainingByTitleController@import')->name('module.training_by_title.import');
    Route::get('/export', 'Backend\TrainingByTitleController@export')->name('module.training_by_title.export');
});

Route::group(['prefix' => '/admin-cp/training-by-title/detail/{id}'], function() {
    Route::get('/', 'Backend\TrainingByTitleDetailController@index')
        ->name('module.training_by_title.detail')
        ->where('id', '[0-9]+');

    Route::post('/save-category', 'Backend\TrainingByTitleDetailController@saveCategory')
        ->name('module.training_by_title.detail.save_category')
        ->where('id', '[0-9]+');

    Route::post('/remove-category', 'Backend\TrainingByTitleDetailController@removeCategory')
        ->name('module.training_by_title.detail.remove_category')
        ->where('id', '[0-9]+');

    Route::post('/save', 'Backend\TrainingByTitleDetailController@save')
        ->name('module.training_by_title.detail.save')
        ->where('id', '[0-9]+');

    Route::post('/remove', 'Backend\TrainingByTitleDetailController@remove')
        ->name('module.training_by_title.detail.remove')
        ->where('id', '[0-9]+');

    Route::post('/edit-detail', 'Backend\TrainingByTitleDetailController@editDetail')
        ->name('module.training_by_title.detail.edit_detail')
        ->where('id', '[0-9]+');
});

Route::group(['prefix' => '/admin-cp/training-by-title-result'], function() {
    Route::get('/', 'Backend\TrainingByTitleResultController@index')->name('module.training_by_title.result');
    Route::get('/getdata', 'Backend\TrainingByTitleResultController@getDataUser')->name('module.training_by_title.result.getdata_user');

    Route::get('/detail/{user_id}', 'Backend\TrainingByTitleResultController@detail')
        ->name('module.training_by_title.result.detail')
        ->where('user_id', '[0-9]+');

    Route::get('/getdata-detail/{user_id}', 'Backend\TrainingByTitleResultController@getDataUserDetail')
        ->name('module.training_by_title.result.getdata_detail')
        ->where('user_id', '[0-9]+');
});

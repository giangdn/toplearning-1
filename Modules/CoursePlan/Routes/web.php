<?php
Route::group(['prefix' => '/admin-cp/course-plan', 'middleware' => 'auth'], function() {
    Route::get('/', 'Backend\CoursePlanController@index')->name('module.course_plan.management')->middleware('permission:course-plan');

    Route::get('/getdata', 'Backend\CoursePlanController@getData')->name('module.course_plan.getdata')->middleware('permission:course-plan');

    Route::get('/{course_type}/edit/{id}', 'Backend\CoursePlanController@form')->name('module.course_plan.edit')->middleware('permission:course-plan-create|course-plan-edit');

    Route::get('/{course_type}/create', 'Backend\CoursePlanController@form')->name('module.course_plan.create')->middleware('permission:course-plan-create|course-plan-edit');

    Route::post('/{course_type}/save', 'Backend\CoursePlanController@save')->name('module.course_plan.save')->middleware('permission:course-plan-create|course-plan-edit');

    Route::post('/remove', 'Backend\CoursePlanController@remove')->name('module.course_plan.remove')->middleware('permission:course-plan-delete');

    Route::post('/approve', 'Backend\CoursePlanController@approve')->name('module.course_plan.approve')->middleware('permission:course-plan-approved');

    Route::post('/ajax-get-course-code', 'Backend\CoursePlanController@ajaxGetCourseCode')->name('module.course_plan.ajax_get_course_code');

    Route::post('/ajax-get-subject', 'Backend\CoursePlanController@ajaxGetSubject')->name('module.course_plan.ajax_get_subject');

    Route::post('/ajax-isopen-publish', 'Backend\CoursePlanController@ajaxIsopenPublish')->name('module.course_plan.ajax_isopen_publish');

    Route::post('/{course_type}/edit/{id}/save-object', 'Backend\CoursePlanController@saveObject')->name('module.course_plan.save_object')->where('id', '[0-9]+')->middleware('permission:course-plan-create-object');

    Route::get('/{course_type}/edit/{id}/get-object', 'Backend\CoursePlanController@getObject')->name('module.course_plan.get_object')->where('id', '[0-9]+');

    Route::post('/{course_type}/edit/{id}/remove-object', 'Backend\CoursePlanController@removeObject')->name('module.course_plan.remove_object')->where('id', '[0-9]+')->middleware('permission:course-plan-delete-object');

    Route::post('/{course_type}/edit/{id}/save-cost', 'Backend\CoursePlanController@saveCost')->name('module.course_plan.save_cost')->where('id', '[0-9]+')->middleware('permission:course-plan-create-cost');

    Route::post('/{course_type}/edit/{id}/save-condition', 'Backend\CoursePlanController@saveCondition')->name('module.course_plan.save_condition')->where('id', '[0-9]+')->middleware('permission:course-plan-create-condition');

    Route::post('/{course_type}/edit/{id}/save-schedule', 'Backend\CoursePlanController@saveSchedule')->name('module.course_plan.save_schedule')->where('id', '[0-9]+')->middleware('permission:course-plan-create-schedule');

    Route::get('/{course_type}/edit/{id}/get-schedule', 'Backend\CoursePlanController@getSchedule')->name('module.course_plan.get_schedule')->where('id', '[0-9]+');

    Route::post('/{course_type}/edit/{id}/remove-schedule', 'Backend\CoursePlanController@removeSchedule')->name('module.course_plan.remove_schedule')->where('id', '[0-9]+')->middleware('permission:course-plan-delete-schedule');

    Route::get('/{course_type}/edit/{id}/teacher', 'Backend\CoursePlanController@teacher')->name('module.course_plan.teacher')->where('id', '[0-9]+');

    Route::post('/{course_type}/edit/{id}/save-teacher', 'Backend\CoursePlanController@saveTeacher')->name('module.course_plan.save_teacher')->where('id', '[0-9]+')->middleware('permission:course-plan-add-teacher');

    Route::get('/{course_type}/edit/{id}/get-teacher', 'Backend\CoursePlanController@getDataTeacher')->name('module.course_plan.get_teacher')->where('id', '[0-9]+');

    Route::post('/{course_type}/edit/{id}/remove-teacher', 'Backend\CoursePlanController@removeTeacher')->name('module.course_plan.remove_teacher')->where('id', '[0-9]+')->middleware('permission:course-plan-delete-teacher');

    Route::post('/{course_type}/edit/{id}/check-unit-child', 'Backend\CoursePlanController@getChild')->name('module.course_plan.get_child')->where('id', '[0-9]+');

    Route::get('/{course_type}/edit/{id}/get-tree-child', 'Backend\CoursePlanController@getTreeChild')->name('module.course_plan.get_tree_child')->where('id', '[0-9]+');

    Route::post('/convert', 'Backend\CoursePlanController@convert')->name('module.course_plan.convert');

    Route::post('/ajax-get-unit', 'Backend\CoursePlanController@ajaxGetUnit')->name('module.course_plan.ajax_get_unit');
});

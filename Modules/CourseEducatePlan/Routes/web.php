<?php


Route::group(['prefix' => '/admin-cp/course-educate-plan', 'middleware' => 'auth'], function() {
    
    Route::get('/', 'CourseEducatePlanController@index')->name('module.course_educate_plan.management');

    Route::get('/getdata', 'CourseEducatePlanController@getData')->name('module.course_educate_plan.getdata');

    Route::get('/edit/{id}', 'CourseEducatePlanController@form')->name('module.course_educate_plan.edit');

    Route::get('/create', 'CourseEducatePlanController@form')->name('module.course_educate_plan.create');

    Route::post('/save', 'CourseEducatePlanController@save')->name('module.course_educate_plan.save');

    Route::post('/remove', 'CourseEducatePlanController@remove')->name('module.course_educate_plan.remove');

    Route::post('/approve', 'CourseEducatePlanController@approve')->name('module.course_educate_plan.approve');

    Route::post('/ajax-get-course-code', 'CourseEducatePlanController@ajaxGetCourseCode')->name('module.course_educate_plan.ajax_get_course_code');

    Route::post('/ajax-get-subject', 'CourseEducatePlanController@ajaxGetSubject')->name('module.course_educate_plan.ajax_get_subject');

    Route::post('/ajax-isopen-publish', 'CourseEducatePlanController@ajaxIsopenPublish')->name('module.course_educate_plan.ajax_isopen_publish');

    Route::post('/edit/{id}/save-object', 'CourseEducatePlanController@saveObject')->name('module.course_educate_plan.save_object')->where('id', '[0-9]+');

    Route::get('/edit/{id}/get-object', 'CourseEducatePlanController@getObject')->name('module.course_educate_plan.get_object')->where('id', '[0-9]+');

    Route::post('/edit/{id}/remove-object', 'CourseEducatePlanController@removeObject')->name('module.course_educate_plan.remove_object')->where('id', '[0-9]+');

    Route::post('/edit/{id}/save-cost', 'CourseEducatePlanController@saveCost')->name('module.course_educate_plan.save_cost')->where('id', '[0-9]+');

    Route::post('/edit/{id}/save-condition', 'CourseEducatePlanController@saveCondition')->name('module.course_educate_plan.save_condition')->where('id', '[0-9]+');

    Route::post('/edit/{id}/save-schedule', 'CourseEducatePlanController@saveSchedule')->name('module.course_educate_plan.save_schedule')->where('id', '[0-9]+');

    Route::get('/edit/{id}/get-schedule', 'CourseEducatePlanController@getSchedule')->name('module.course_educate_plan.get_schedule')->where('id', '[0-9]+');

    Route::post('/edit/{id}/remove-schedule', 'CourseEducatePlanController@removeSchedule')->name('module.course_educate_plan.remove_schedule')->where('id', '[0-9]+');

    Route::get('/edit/{id}/teacher', 'CourseEducatePlanController@teacher')->name('module.course_educate_plan.teacher')->where('id', '[0-9]+');

    Route::post('/edit/{id}/save-teacher', 'CourseEducatePlanController@saveTeacher')->name('module.course_educate_plan.save_teacher')->where('id', '[0-9]+');

    Route::get('/edit/{id}/get-teacher', 'CourseEducatePlanController@getDataTeacher')->name('module.course_educate_plan.get_teacher')->where('id', '[0-9]+');

    Route::post('/edit/{id}/remove-teacher', 'CourseEducatePlanController@removeTeacher')->name('module.course_educate_plan.remove_teacher')->where('id', '[0-9]+');

    Route::post('/edit/{id}/check-unit-child', 'CourseEducatePlanController@getChild')->name('module.course_educate_plan.get_child')->where('id', '[0-9]+');

    Route::get('/edit/{id}/get-tree-child', 'CourseEducatePlanController@getTreeChild')->name('module.course_educate_plan.get_tree_child')->where('id', '[0-9]+');

    Route::post('/convert', 'CourseEducatePlanController@convert')->name('module.course_educate_plan.convert');
});

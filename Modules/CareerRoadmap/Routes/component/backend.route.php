<?php

Route::group(['prefix' => 'career-roadmap'], function() {
    Route::get('/', 'Backend\CareerRoadmapController@index')->name('module.career_roadmap')->middleware('permission:career-roadmap');

    Route::get('/getdata', 'Backend\CareerRoadmapController@getData')->name('module.career_roadmap.getdata')->middleware('permission:career-roadmap');
});

Route::group(['prefix' => 'career-roadmap/title/{title_id}'], function() {
    Route::get('/', 'Backend\CareerRoadmapTitleController@index')->name('module.career_roadmap.title')->where('title_id', '[0-9]+')->middleware('permission:career-roadmap');

    Route::post('/save', 'Backend\CareerRoadmapTitleController@save')->name('module.career_roadmap.title.save')->where('title_id', '[0-9]+')->middleware('permission:career-roadmap-create');

    Route::get('/parents', 'Backend\CareerRoadmapTitleController@getParents')->name('module.career_roadmap.getparents')->where('title_id', '[0-9]+');

    Route::post('/remove-roadmap', 'Backend\CareerRoadmapTitleController@removeRoadmap')->name('module.career_roadmap.title.remove_roadmap')->where('title_id', '[0-9]+')->middleware('permission:career-roadmap-delete');

    Route::post('/add-title', 'Backend\CareerRoadmapTitleController@addTitle')->name('module.career_roadmap.title.add_title')->where('title_id', '[0-9]+')->middleware('permission:career-roadmap-create');

    Route::get('/edit', 'Backend\CareerRoadmapTitleController@edit')->name('module.career_roadmap.title.edit')->where('id', '[0-9]+')->middleware('permission:career-roadmap-edit');

    Route::post('/save-edit-title', 'Backend\CareerRoadmapTitleController@saveEditTitle')->name('module.career_roadmap.title.save_eidt_title')->where('title_id', '[0-9]+')->middleware('permission:career-roadmap-edit|career-roadmap-create');

    Route::post('/remove-title', 'Backend\CareerRoadmapTitleController@remove')->name('module.career_roadmap.title.remove')->where('title_id', '[0-9]+')->middleware('permission:career-roadmap-delete');

    Route::post('/import-roadmap', 'Backend\CareerRoadmapTitleController@import')->name('module.career_roadmap.import');
});

Route::group(['prefix' => '/user/career-roadmap/{user_id}'], function() {

    Route::get('/', 'Backend\CareerUserController@index')->name('module.career_roadmap.user')->where('user_id', '[0-9]+');

    Route::get('/courses/{title_id}', 'Backend\CareerUserController@getCourses')->name('module.career_roadmap.user.getcourses')->where('user_id', '[0-9]+');
});

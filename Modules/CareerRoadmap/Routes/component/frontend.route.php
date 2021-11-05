<?php

Route::get('/', 'Frontend\CareerRoadmapController@index')->name('module.career_roadmap.frontend');

Route::get('/get-course/{title_id}', 'Frontend\CareerRoadmapController@getCourses')->name('module.career_roadmap.frontend.get_courses');

Route::post('/tree/getChild', 'Frontend\CareerRoadmapController@getChild')->name('module.career_roadmap.frontend.tree_folder.get_child');

Route::post('/save', 'Frontend\CareerRoadmapController@save')->name('module.career_roadmap.frontend.save');

Route::get('/parents', 'Frontend\CareerRoadmapController@getParents')->name('module.career_roadmap.frontend.getparents');

Route::post('/remove-roadmap', 'Frontend\CareerRoadmapController@removeRoadmap')->name('module.career_roadmap.frontend.remove_roadmap');

Route::post('/add-title', 'Frontend\CareerRoadmapController@addTitle')->name('module.career_roadmap.frontend.add_title');

Route::get('/edit', 'Frontend\CareerRoadmapController@edit')->name('module.career_roadmap.frontend.edit');

Route::post('/save-edit-title', 'Frontend\CareerRoadmapController@saveEditTitle')->name('module.career_roadmap.frontend.save_edit_title');

Route::post('/remove-title', 'Frontend\CareerRoadmapController@remove')->name('module.career_roadmap.frontend.remove');

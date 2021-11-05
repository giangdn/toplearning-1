<?php

Route::get('/', 'Frontend\ListController@index')->name('module.training_action.list');

Route::post('/register-student', 'Frontend\ListController@registerStudent')->name('module.training_action.register_student');

Route::post('/register-teacher', 'Frontend\ListController@registerTeacher')->name('module.training_action.register_teacher');
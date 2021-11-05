<?php

Route::group(['prefix' => 'training-action'], function() {
    Route::get('/', 'Backend\TrainingActionController@index')->name('module.training_action');
});

Route::group(['prefix' => 'category/training-action'], function() {
    Route::get('/', 'Backend\CategoryController@index')->name('module.training_action.category');
    
    Route::get('/create', 'Backend\CategoryController@form')->name('module.training_action.category.create');
    
    Route::get('/edit/{id}', 'Backend\CategoryController@form')->name('module.training_action.category.edit')->where('id', '[0-9]+');
    
    Route::get('/getdata', 'Backend\CategoryController@getData')->name('module.training_action.category.getdata');
    
    Route::post('/save', 'Backend\CategoryController@save')->name('module.training_action.category.save');
    
    Route::post('/remove', 'Backend\CategoryController@remove')->name('module.training_action.category.remove');
    
    Route::get('/getscores', 'Backend\CategoryController@getScores')->name('module.training_action.category.getscores');
});

Route::group(['prefix' => 'category/training-action/field'], function() {
    Route::get('/', 'Backend\FieldController@index')->name('module.training_action.field');
    
    Route::get('/create', 'Backend\FieldController@form')->name('module.training_action.field.create');
    
    Route::get('/edit/{id}', 'Backend\FieldController@form')->name('module.training_action.field.edit')->where('id', '[0-9]+');
    
    Route::get('/getdata', 'Backend\FieldController@getData')->name('module.training_action.field.getdata');
    
    Route::post('/save', 'Backend\FieldController@save')->name('module.training_action.field.save');
    
    Route::post('/remove', 'Backend\FieldController@remove')->name('module.training_action.field.remove');
});

Route::group(['prefix' => 'category/training-action/person-charge'], function() {
    Route::get('/', 'Backend\PersonChargeController@index')->name('module.training_action.person_charge');
    
    Route::get('/create', 'Backend\PersonChargeController@form')->name('module.training_action.person_charge.create');
    
    Route::get('/edit/{id}', 'Backend\PersonChargeController@form')->name('module.training_action.person_charge.edit')->where('id', '[0-9]+');
    
    Route::get('/getdata', 'Backend\PersonChargeController@getData')->name('module.training_action.person_charge.getdata');
    
    Route::post('/save', 'Backend\PersonChargeController@save')->name('module.training_action.person_charge.save');
    
    Route::post('/remove', 'Backend\PersonChargeController@remove')->name('module.training_action.person_charge.remove');
});

Route::group(['prefix' => 'category/training-action/field'], function() {
    Route::get('/', 'Backend\FieldController@index')->name('module.training_action.field');
    
    Route::get('/create', 'Backend\FieldController@form')->name('module.training_action.field.create');
    
    Route::get('/edit/{id}', 'Backend\FieldController@form')->name('module.training_action.field.edit')->where('id', '[0-9]+');
    
    Route::get('/getdata', 'Backend\FieldController@getData')->name('module.training_action.field.getdata');
    
    Route::post('/save', 'Backend\FieldController@save')->name('module.training_action.field.save');
    
    Route::post('/remove', 'Backend\FieldController@remove')->name('module.training_action.field.remove');
});

Route::group(['prefix' => 'category/training-action/roles'], function() {
    Route::get('/', 'Backend\RoleController@index')->name('module.training_action.role');
    
    Route::get('/create', 'Backend\RoleController@form')->name('module.training_action.role.create');
    
    Route::get('/edit/{id}', 'Backend\RoleController@form')->name('module.training_action.role.edit')->where('id', '[0-9]+');
    
    Route::get('/getdata', 'Backend\RoleController@getData')->name('module.training_action.role.getdata');
    
    Route::post('/save', 'Backend\RoleController@save')->name('module.training_action.role.save');
    
    Route::post('/remove', 'Backend\RoleController@remove')->name('module.training_action.role.remove');
});

Route::group(['prefix' => 'training-action/teachers/{training_action}'], function() {
    Route::get('/', 'Backend\TeacherController@index')->name('module.training_action.teachers');
    
    Route::get('/create', 'Backend\TeacherController@form')->name('module.training_action.teachers.create');
    
    Route::get('/getdata', 'Backend\TeacherController@getData')->name('module.training_action.teachers.getdata');
    
    Route::post('/save', 'Backend\TeacherController@save')->name('module.training_action.teachers.save');
    
    Route::post('/remove', 'Backend\TeacherController@remove')->name('module.training_action.teachers.remove');
    
    Route::post('/approve', 'Backend\TeacherController@approve')->name('module.training_action.teachers.approve');
});

Route::group(['prefix' => 'training-action/register/{training_action}'], function() {
    Route::get('/', 'Backend\RegisterController@index')->name('module.training_action.register');
    
    Route::get('/create', 'Backend\RegisterController@form')->name('module.training_action.register.create');
    
    Route::get('/getdata', 'Backend\RegisterController@getData')->name('module.training_action.register.getdata');
    
    Route::post('/save', 'Backend\RegisterController@save')->name('module.training_action.register.save');
    
    Route::post('/remove', 'Backend\RegisterController@remove')->name('module.training_action.register.remove');
    
    Route::post('/approve', 'Backend\RegisterController@approve')->name('module.training_action.register.approve');
});
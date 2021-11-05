<?php

/* backend */
Route::group(['prefix' => '/admin-cp/virtualclassroom', 'middleware' => 'auth'], function() {

    Route::get('/', 'VirtualClassroomController@index')
        ->name('module.virtualclassroom.index');

    Route::get('/getdata', 'VirtualClassroomController@getData')
        ->name('module.virtualclassroom.getdata');

    Route::get('/edit/{id}', 'VirtualClassroomController@form')
        ->name('module.virtualclassroom.edit')
        ->where('id', '[0-9]+');

    Route::get('/create', 'VirtualClassroomController@form')
        ->name('module.virtualclassroom.create');

    Route::post('/save', 'VirtualClassroomController@save')
        ->name('module.virtualclassroom.save');

    Route::post('/remove', 'VirtualClassroomController@remove')
        ->name('module.virtualclassroom.remove');

    Route::post('/approve', 'VirtualClassroomController@approve')
        ->name('module.virtualclassroom.approve');

    Route::post('/save-teacher/{id}', 'VirtualClassroomController@saveTeacher')
        ->name('module.virtualclassroom.save_teacher')
        ->where('id', '[0-9]+');

    Route::get('/get-teacher/{id}', 'VirtualClassroomController@getTeacher')
        ->name('module.virtualclassroom.get_teacher')
        ->where('id', '[0-9]+');

    Route::post('/remove-teacher/{id}', 'VirtualClassroomController@removeTeacher')
        ->name('module.virtualclassroom.remove_teacher')
        ->where('id', '[0-9]+');
});

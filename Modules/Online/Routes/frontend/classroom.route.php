<?php

Route::group(['prefix' => '/virtual-classroom', 'middleware' => 'auth'], function() {
    Route::get('/', 'Frontend\ClassroomController@index')->name('module.online.virtualclassroom');
    
    Route::get('/getdata', 'Frontend\ClassroomController@getDataVirtualClassroom')->name('module.online.getdata.virtualclassroom');
    
    Route::post('/get-user/{course_id}', 'Frontend\ClassroomController@getListUser')
        ->name('module.online.get_list_user')
        ->where('course_id', '[0-9]+');
});
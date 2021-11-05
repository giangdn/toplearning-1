<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('attendance')->group(function() {
    Route::get('/', 'AttendanceController@index');
});
Route::group(['prefix' => '/attendance', 'middleware' => 'auth'], function() {
    Route::get('/', 'AttendanceController@index')->name('frontend.attendance');
    Route::get('/getdata/', 'AttendanceController@getData')->name('frontend.attendance.getData');
    Route::get('/course/{course_id}/', 'AttendanceController@showStudents')->name('frontend.attendance.course')->where('course_id', '[0-9]+');
    Route::get('/course/{course_id}/students', 'AttendanceController@getStudents')->name('frontend.attendance.getStudents')->where('course_id', '[0-9]+');
    Route::post('/course/showModal/{course_id}/{schedule_id}/', 'AttendanceController@showModal')->name('frontend.attendance.show_modal')->where('course_id', '[0-9]+')->where('schedule_id', '[0-9]+');

    Route::get('/detail/{id}', 'FrontendController@detail')
        ->name('module.offline.detail')
        ->where('id', '[0-9]+');

    Route::post('/rating/{id}', 'FrontendController@rating')
        ->name('module.offline.rating')
        ->where('id', '[0-9]+');

    Route::post('/register-course/{id}', 'FrontendController@registerCourse')
        ->name('module.offline.register_course')
        ->where('id', '[0-9]+');

    Route::post('/commnent/{id}', 'FrontendController@comment')
        ->name('module.offline.comment')
        ->where('id', '[0-9]+');

    Route::get('/search','FrontendController@search')->name('module.offline.search');
});

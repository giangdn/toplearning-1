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

Route::group(['prefix'=>'/admin-cp/log-view-course','middleware' => 'auth'], function() {
    Route::get('/', 'LogViewCourseController@index')->name('module.log.view.course.index')->middleware('permission:log-view-course');;
});

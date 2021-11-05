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
Route::group(['prefix' => '/admin-cp/courseold', 'middleware' => 'auth'], function() {
    Route::get('/', 'CourseOldController@index')->name('module.courseold');
    Route::post('/remove', 'CourseOldController@destroy')->name('module.courseold.remove');
    Route::post('/import', 'CourseOldController@import')->name('module.courseold.import');
    Route::get('/export', 'CourseOldController@export')->name('module.courseold.export');
    Route::get('/show/{id}', 'CourseOldController@show')->name('module.courseold.show')->where('id','[0-9]+');
});

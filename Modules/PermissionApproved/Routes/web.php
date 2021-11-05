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

Route::group(['prefix' => '/admin-cp/permission-approved', 'middleware' => 'auth'], function() {
    Route::get('/', 'PermissionApprovedController@index')->name('backend.permission.approved.index')->middleware('permission:approved-process');
    Route::post('/save', 'PermissionApprovedController@store')->name('backend.permission.approved.save')->middleware('permission:approved-process-create');
    Route::delete('/delete', 'PermissionApprovedController@destroy')->name('backend.permission.approved.delete')->middleware('permission:approved-process-delete');
    Route::get('/create', 'PermissionApprovedController@create')->name('backend.permission.approved.create')->middleware('permission:approved-process-create');
    Route::get('/edit/{id}', 'PermissionApprovedController@edit')->name('backend.permission.approved.edit')->where('id','[0-9]+')->middleware('permission:approved-process-edit');
    Route::put('/update/{id}', 'PermissionApprovedController@update')->name('backend.permission.approved.update')->where('id','[0-9]+')->middleware('permission:approved-process-edit');
});

Route::group(['prefix' => '/admin-cp/approved-process', 'middleware' => 'auth'], function() {
    Route::get('/', 'ApprovedProcessController@index')->name('backend.approved.process.index')->middleware('permission:approved-process');
    Route::post('/save', 'ApprovedProcessController@store')->name('backend.approved.process.save')->middleware('permission:approved-process-create');
    Route::post('/delete', 'ApprovedProcessController@destroy')->name('backend.approved.process.delete')->middleware('permission:approved-process-delete');
    Route::get('/create', 'PermissionApprovedController@create')->name('backend.permission.approved.create')->middleware('permission:approved-process-create');
    Route::get('/edit/{id}', 'PermissionApprovedController@edit')->name('backend.permission.approved.edit')->where('id','[0-9]+')->middleware('permission:approved-process-edit');
    Route::put('/update/{id}', 'PermissionApprovedController@update')->name('backend.permission.approved.update')->where('id','[0-9]+')->middleware('permission:approved-process-edit');
});

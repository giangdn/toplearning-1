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

Route::group(['prefix' => '/admin-cp/userpermission','middleware'=>'auth'], function() {
    Route::get('/', 'UserPermissionController@index')->name('module.userpermission')->middleware('permission:permission-user');
    Route::get('/getdata', 'UserPermissionController@getData')->name('module.userpermission.getdata')->middleware('permission:permission-user');
    Route::get('/form/{user_id}', 'UserPermissionController@form')->name('module.userpermission.form')->where('user_id', '[0-9]+')->middleware('permission:permission-user-permission');
    Route::get('/getpermission/{user_id}', 'UserPermissionController@getPermission')->name('module.userpermission.getpermission')->where('user_id', '[0-9]+')->middleware('permission:permission-user');
    Route::get('/create', 'PermissionController@create')->name('backend.userpermission.create')->middleware('permission:permission-user-permission');
    Route::post('/save/{user_id}/', 'UserPermissionController@store')->name('module.userpermission.save')->where('user_id', '[0-9]+')->middleware('permission:permission-user-permission');
});

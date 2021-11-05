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

//Route::prefix('permissiontype')->group(function() {
//    Route::get('/', 'PermissionTypeController@index');
//});

Route::group(['prefix' => '/admin-cp/permission-type', 'middleware' => 'auth'], function() {
    Route::get('/', 'PermissionTypeController@index')
        ->name('module.permission.type')
        ->middleware('permission:permission-group');

    Route::get('/getdata', 'PermissionTypeController@getData')
        ->name('module.permission.type.getdata')
        ->middleware('permission:permission-group');

    Route::post('/get-modal', 'PermissionTypeController@showModal')
        ->name('module.permission.type.get_modal');

    Route::post('/save', 'PermissionTypeController@save')
        ->name('module.permission.type.save')
        ->middleware('permission:permission-group-create|permission-group-edit');

    Route::post('/delete', 'PermissionTypeController@delete')
        ->name('module.permission.type.delete')
        ->middleware('permission:permission-group-delete');
});

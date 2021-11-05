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


Route::group(['prefix' => '/admin-cp/permission', 'middleware' => 'auth'], function() {
    Route::get('/', 'PermissionController@index')->name('backend.permissions');
    Route::get('/getdata', 'PermissionController@getData')->name('backend.permissions.getdata');
    Route::post('/delete', 'PermissionController@remove')->name('backend.permissions.delete');
    Route::get('/create', 'PermissionController@create')->name('backend.permissions.create');
    Route::put('/save/{permission}', 'PermissionController@store')->name('backend.permissions.save');

    Route::get('/unit-manager', 'UnitManagerSettingController@index')->name('backend.permission.unitmanager')->middleware('permission:unit-manager-setting');
    Route::delete('/unit-manager/remove', 'UnitManagerSettingController@destroy')->name('backend.permission.unitmanager.remove')->middleware('permission:unit-manager-setting-delete');
    Route::get('/unit-manager/create', 'UnitManagerSettingController@create')->name('backend.permission.unitmanager.create')->middleware('permission:unit-manager-setting-create');
    Route::get('/unit-manager/edit/{id}', 'UnitManagerSettingController@edit')->name('backend.permission.unitmanager.edit')->where('id','[0-9]+')->middleware('permission:unit-manager-setting-edit');
    Route::put('/unit-manager/update/{id}', 'UnitManagerSettingController@update')->name('backend.permission.unitmanager.update')->where('id','[0-9]+')->middleware('permission:unit-manager-setting-edit');
    Route::post('/unit-manager/save', 'UnitManagerSettingController@store')->name('backend.permission.unitmanager.save')->middleware('permission:unit-manager-setting-create');
    Route::post('/unit-manager/import', 'UnitManagerSettingController@import')->name('backend.permission.unitmanager.import')->middleware('permission:unit-manager-setting-import');
});

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

Route::group(['prefix' => '/admin-cp/role', 'middleware' => 'auth'], function() {
    Route::get('/', 'RoleController@index')->name('backend.roles')->middleware('permission:role');
    Route::get('/getdata', 'RoleController@getData')->name('backend.roles.getdata')->middleware('permission:role');
    Route::post('/delete', 'RoleController@delete')->name('backend.roles.delete')->middleware('permission:role-delete');
    Route::get('/create', 'RoleController@create')->name('backend.roles.create')->middleware('permission:role-create');
    Route::get('/edit/{id}', 'RoleController@edit')->name('backend.roles.edit')->where('id', '[0-9]+')->middleware('permission:role-edit');
    Route::post('/save', 'RoleController@store')->name('backend.roles.save')->middleware('permission:role-edit|role-create');
    Route::get('/getpermission/{role}', 'RoleController@getpermission')->name('backend.roles.getpermission')->where('role', '[0-9]+')->middleware('permission:role');
    Route::post('/ajax_save', 'RoleController@savePermissionByRole')->name('backend.roles.ajax_save')->middleware('permission:role-edit|role-create');
    Route::get('/export', 'RoleController@export')->name('backend.roles.export')->middleware('permission:role-export');
    Route::post('/group-permission/save', 'RoleController@saveGroupPermission')->name('backend.roles.group.permission.save')->middleware('permission:role-edit|role-create');
});

Route::group(['prefix' => '/admin-cp/role/user/{role}', 'middleware' => ['auth','permission:role']], function() {
    Route::get('/', 'RoleController@userAssign')->name('backend.roles.user.assign_role')->where('role','[0-9]+');
    Route::get('/getdata/assign-role', 'RoleController@getUserAssignRole')->name('backend.role.user.getdata.assign.role')->where('role','[0-9]+');
    Route::get('/getdata/unassign-role', 'RoleController@getUserUnassignRole')->name('backend.role.user.getdata.unassign.role')->where('role','[0-9]+');

    Route::get('/create', 'RoleController@userUnassign')->name('backend.roles.user.unassign_role')->where('role','[0-9]+');
    Route::post('/save', 'RoleController@saveRoleUser')->name('backend.roles.user.save')->where('role','[0-9]+');
    Route::post('/delete', 'RoleController@deleteRoleUser')->name('backend.roles.user.delete')->where('role','[0-9]+');
});

Route::group(['prefix' => '/admin-cp/role/title/{role}', 'middleware' => 'auth'], function() {
    Route::get('/', 'RoleController@titleAssign')->name('backend.roles.title.assign_role')->where('role','[0-9]+');
    Route::get('/getdata/assign-role', 'RoleController@getTitleAssignRole')->name('backend.role.title.getdata.assign.role')->where('role','[0-9]+');
    Route::get('/getdata/unassign-role', 'RoleController@getTitleUnassignRole')->name('backend.role.title.getdata.unassign.role')->where('role','[0-9]+');

    Route::get('/create', 'RoleController@titleUnassign')->name('backend.roles.title.unassign_role')->where('role','[0-9]+');
    Route::post('/save', 'RoleController@saveRoleTitle')->name('backend.roles.title.save')->where('role','[0-9]+');
    Route::post('/delete', 'RoleController@deleteRoleTitle')->name('backend.roles.title.delete')->where('role','[0-9]+');
});

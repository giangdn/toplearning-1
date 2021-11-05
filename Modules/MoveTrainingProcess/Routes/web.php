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

Route::group(['prefix'=>'/admin-cp/movetrainingprocess','middleware' => 'auth'], function() {
    Route::get('/', 'MoveTrainingProcessController@index')->name('module.movetrainingprocess.index');
    Route::get('/getData', 'MoveTrainingProcessController@getData')->name('module.movetrainingprocess.getData');
    Route::get('/edit/{user_id}/getData', 'MoveTrainingProcessController@getTrainingProcessUser')->name('module.movetrainingprocess.user.getData')->where('user_id', '[0-9]+');
    Route::get('/create', 'MoveTrainingProcessController@create')->name('module.movetrainingprocess.create');
    Route::get('/edit/{user_id}', 'MoveTrainingProcessController@edit')->name('module.movetrainingprocess.edit')->where('user_id', '[0-9]+');
    Route::post('/update/{id}', 'MoveTrainingProcessController@update')->name('module.movetrainingprocess.update')->where('id', '[0-9]+');
    Route::post('/store', 'MoveTrainingProcessController@store')->name('module.movetrainingprocess.store');

    Route::post('/remove', 'MoveTrainingProcessController@destroy')->name('module.movetrainingprocess.remove');
    Route::post('/submit', 'MoveTrainingProcessController@submitMoveTrainingProcess')->name('module.movetrainingprocess.submit');
    Route::post('/modal', 'MoveTrainingProcessController@showModalMoveTrainingProcess')->name('module.movetrainingprocess.modal');
    Route::get('/training-process-old/getdata/{user_id}', 'MoveTrainingProcessController@getTrainingProcessOld')->name('module.movetrainingprocess.training_process_old.getData')->where('user_id', '[0-9]+');
    Route::post('/approved', 'MoveTrainingProcessController@approved')->name('module.movetrainingprocess.approved');
    Route::get('/logs', 'MoveTrainingProcessController@showLogs')->name('module.movetrainingprocess.logs');
    Route::get('/getlogs', 'MoveTrainingProcessController@getLogs')->name('module.movetrainingprocess.logs.getData');
});

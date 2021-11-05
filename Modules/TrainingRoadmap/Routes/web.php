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
Route::group(['prefix' => '/admin-cp/trainingroadmap', 'middleware' => 'auth'], function() {

    Route::get('/', 'BackendController@index')->name('module.trainingroadmap');

    Route::get('/list', 'BackendController@listRoadmap')->name('module.trainingroadmap.list');

    Route::get('/getdata', 'BackendController@getData')->name('module.trainingroadmap.getdata');

    Route::post('/import', 'TrainingRoadmapController@import')->name('module.trainingroadmap.detail.import');
    Route::get('/export-roadmap', 'TrainingRoadmapController@exportRoadmap')->name('module.trainingroadmap.export_roadmap');

    Route::post('/ajax-copy', 'TrainingRoadmapController@copy')->name('module.trainingroadmap.ajax_copy');
    Route::post('/saveOrder', 'TrainingRoadmapController@saveOrder')->name('module.trainingroadmap.saveOrder');
    Route::post('/ajax-check-training-roadmap', 'TrainingRoadmapController@checkTrainingRoadmap')->name('module.trainingroadmap.ajax_check_training_roadmap');
});

Route::group(['prefix' => '/admin-cp/trainingroadmap/train-detail/{id}', 'middleware' => 'auth'], function() {
    Route::get('/', 'TrainingRoadmapController@index')->name('module.trainingroadmap.detail')->where('id', '[0-9]+');

    Route::get('/getdata', 'TrainingRoadmapController@getData')->name('module.trainingroadmap.detail.getdata');

    Route::get('/edit/{train_id}', 'TrainingRoadmapController@form')
        ->name('module.trainingroadmap.detail.edit')
        ->where('id', '[0-9]+')
        ->where('train_id', '[0-9]+');

    Route::get('/create', 'TrainingRoadmapController@form')->name('module.trainingroadmap.detail.create');

    Route::post('/save', 'TrainingRoadmapController@save')->name('module.trainingroadmap.detail.save');

    Route::post('/remove', 'TrainingRoadmapController@remove')->name('module.trainingroadmap.detail.remove');

    Route::get('/export', 'TrainingRoadmapController@export')->name('module.trainingroadmap.detail.export');
});

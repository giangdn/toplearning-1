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

Route::group(['prefix' => '/admin-cp/potential/kpi', 'middleware' => 'auth'], function() {
    Route::get('/', 'BackendController@listKPI')->name('module.potential.kpi.list_kpi');
    Route::get('/getdata-kpi', 'BackendController@getDataKPI')->name('module.potential.kpi.getdata_kpi');
    Route::post('/import-kpi', 'BackendController@importKPI')->name('module.potential.import_kpi');
});

Route::group(['prefix' => '/admin-cp/potential', 'middleware' => 'auth'], function() {
    Route::get('/', 'BackendController@index')->name('module.potential.index');
    Route::get('/getdata', 'BackendController@getData')->name('module.potential.getdata');
    Route::get('/edit/{train_id}', 'BackendController@form')->name('module.potential.edit')->where('id', '[0-9]+');
    Route::get('/create', 'BackendController@form')->name('module.potential.create');
    Route::post('/save', 'BackendController@save')->name('module.potential.save');
    Route::post('/remove', 'BackendController@remove')->name('module.potential.remove');
    Route::post('/import', 'BackendController@import')->name('module.potential.import');
    Route::get('/export', 'BackendController@export')->name('module.potential.export');

    Route::get('/{user_id}/course', 'BackendController@course')->name('module.potential.course')->where('user_id', '[0-9]+');
    Route::get('/{user_id}/export-course', 'BackendController@exportCourse')->name('module.potential.export_course')->where('user_id', '[0-9]+');

    Route::get('/search', 'BackendController@search')->name('module.potential.search');
    Route::get('/getdata-search', 'BackendController@getDataSearch')->name('module.potential.getdata.search');
    Route::get('/export-search', 'BackendController@exportSearch')->name('module.potential.export_search');
});

Route::group(['prefix' => '/admin-cp/potential/roadmap', 'middleware' => 'auth'], function() {
    Route::get('/list-title', 'PotentialRoadmapController@listTitle')->name('module.potential.roadmap.list_title');
    Route::get('/getdata-title', 'PotentialRoadmapController@getDataTitle')->name('module.potential.roadmap.getdata_title');

    /* new potential roadmap detail */

    Route::get('/{id}', 'PotentialRoadmapController@index')->name('module.potential.roadmap')->where('id', '[0-9]+');

    Route::get('/{id}/getdata', 'PotentialRoadmapController@getData')->name('module.potential.roadmap.getdata')->where('id', '[0-9]+');

    Route::get('/{id}/edit/{train_id}', 'PotentialRoadmapController@form')
        ->name('module.potential.roadmap.edit')
        ->where('id', '[0-9]+')
        ->where('train_id', '[0-9]+');

    Route::get('/{id}/create', 'PotentialRoadmapController@form')->name('module.potential.roadmap.create')->where('id', '[0-9]+');

    Route::post('/{id}/save', 'PotentialRoadmapController@save')->name('module.potential.roadmap.save')->where('id', '[0-9]+');

    Route::post('/{id}/remove', 'PotentialRoadmapController@remove')->name('module.potential.roadmap.remove')->where('id', '[0-9]+');

    Route::post('/{id}/import', 'PotentialRoadmapController@import')->name('module.potential.roadmap.import')->where('id', '[0-9]+');

    Route::get('/{id}/export', 'PotentialRoadmapController@export')->name('module.potential.roadmap.export')->where('id', '[0-9]+');

});

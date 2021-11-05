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

Route::group(['prefix' => '/admin-cp/convert-titles', 'middleware' => 'auth'], function() {

    Route::get('/', 'BackendController@index')->name('module.convert_titles');

    Route::get('/getdata', 'BackendController@getData')->name('module.convert_titles.getdata');

    Route::get('/edit/{id}', 'BackendController@form')->name('module.convert_titles.edit')
        ->where('id', '[0-9]+');

    Route::get('/create', 'BackendController@form')->name('module.convert_titles.create');

    Route::post('/save', 'BackendController@save')->name('module.convert_titles.save');

    Route::post('/save-file', 'BackendController@saveFile')->name('module.convert_titles.save_file');

    Route::post('/remove', 'BackendController@remove')->name('module.convert_titles.remove');

    Route::post('/import', 'BackendController@import')->name('module.convert_titles.import');

    Route::get('/export', 'BackendController@export')->name('module.convert_titles.export');

    Route::get('/{user_id}/course', 'BackendController@course')->name('module.convert_titles.course')->where('user_id', '[0-9]+');

    Route::get('/{user_id}/export-course', 'BackendController@exportCourse')->name('module.convert_titles.export_course')->where('user_id', '[0-9]+');

});

Route::group(['prefix' => '/admin-cp/convert-titles/roadmap', 'middleware' => 'auth'], function() {
    Route::get('/list-title', 'ConvertTitlesRoadmapController@listTitle')->name('module.convert_titles.roadmap.list_title');

    Route::get('/getdata-title', 'ConvertTitlesRoadmapController@getDataTitle')->name('module.convert_titles.roadmap.getdata_title');

    /* new convert titles detail */

    Route::get('/{id}', 'ConvertTitlesRoadmapController@index')->name('module.convert_titles.roadmap')->where('id', '[0-9]+');

    Route::get('/{id}/getdata', 'ConvertTitlesRoadmapController@getData')->name('module.convert_titles.roadmap.getdata')->where('id', '[0-9]+');

    Route::get('/{id}/edit/{train_id}', 'ConvertTitlesRoadmapController@form')
        ->name('module.convert_titles.roadmap.edit')
        ->where('id', '[0-9]+')
        ->where('train_id', '[0-9]+');

    Route::get('/{id}/create', 'ConvertTitlesRoadmapController@form')->name('module.convert_titles.roadmap.create')->where('id', '[0-9]+');

    Route::post('/{id}/save', 'ConvertTitlesRoadmapController@save')->name('module.convert_titles.roadmap.save')->where('id', '[0-9]+');

    Route::post('/{id}/remove', 'ConvertTitlesRoadmapController@remove')->name('module.convert_titles.roadmap.remove')->where('id', '[0-9]+');

    Route::post('/{id}/import', 'ConvertTitlesRoadmapController@import')->name('module.convert_titles.roadmap.import')->where('id', '[0-9]+');

    Route::get('/{id}/export', 'ConvertTitlesRoadmapController@export')->name('module.convert_titles.roadmap.export')->where('id', '[0-9]+');
});

Route::group(['prefix' => '/admin-cp/convert-titles/reviews', 'middleware' => 'auth'], function() {

    Route::get('/', 'ConvertTitlesReviewsController@index')->name('module.convert_titles.reviews');

    Route::get('/getdata', 'ConvertTitlesReviewsController@getData')->name('module.convert_titles.reviews.getdata');

    Route::get('/edit/{id}', 'ConvertTitlesReviewsController@form')->name('module.convert_titles.reviews.edit')->where('id', '[0-9]+');

    Route::get('/create', 'ConvertTitlesReviewsController@form')->name('module.convert_titles.reviews.create');

    Route::post('/save', 'ConvertTitlesReviewsController@save')->name('module.convert_titles.reviews.save');

    Route::post('/remove', 'ConvertTitlesReviewsController@remove')->name('module.convert_titles.reviews.remove');

});

Route::group(['prefix' => '/admin-cp/convert-titles/list-unit', 'middleware' => 'auth'], function() {

    Route::get('/', 'BackendController@listUnit')->name('module.convert_titles.list_unit');

    Route::get('/getdata', 'BackendController@getDataListUnit')->name('module.convert_titles.getdata.list_unit');

    Route::get('/export-employees', 'BackendController@exportEmployees')->name('module.convert_titles.list_unit.export_employees');
});

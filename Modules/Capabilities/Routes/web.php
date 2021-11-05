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

Route::group(['prefix' => '/admin-cp/capabilities', 'middleware' => 'auth'], function() {
    Route::get('/', 'BackendController@index')->name('module.capabilities');

    Route::get('/getdata', 'BackendController@getData')->name('module.capabilities.getdata');

    Route::get('/edit/{id}', 'BackendController@form')->name('module.capabilities.edit')
        ->where('id', '[0-9]+');

    Route::get('/create', 'BackendController@form')->name('module.capabilities.create');

    Route::post('/save', 'BackendController@save')->name('module.capabilities.save');

    Route::post('/remove', 'BackendController@remove')->name('module.capabilities.remove');

    Route::post('/ajax-get-group-name', 'BackendController@ajaxGetGroupName')
        ->name('module.capabilities.ajax_get_group_name');

});

Route::group([
    'prefix' => '/admin-cp/capabilities/group-percent',
    'middleware' => 'auth'
], function() {

    Route::get('/', 'PercentController@index')->name('module.capabilities.group_percent');

    Route::get('/getdata', 'PercentController@getData')->name('module.capabilities.group_percent.getdata');

    Route::get('/edit/{id}', 'PercentController@form')->name('module.capabilities.group_percent.edit')
        ->where('id', '[0-9]+');

    Route::get('/create', 'PercentController@form')->name('module.capabilities.group_percent.create');

    Route::post('/save', 'PercentController@save')->name('module.capabilities.group_percent.save');

    Route::post('/remove', 'PercentController@remove')->name('module.capabilities.group_percent.remove');

    Route::post('/remove-convention/{id}', 'PercentController@removeConvention')
        ->name('module.capabilities.group_percent.remove_convention')
        ->where('id', '[0-9]+');

});


Route::group([
    'prefix' => '/admin-cp/capabilities/group',
    'middleware' => 'auth'
], function() {

    Route::get('/', 'LevelController@index')->name('module.capabilities.group');

    Route::get('/getdata', 'LevelController@getData')->name('module.capabilities.group.getdata');

    Route::get('/edit/{id}', 'LevelController@form')->name('module.capabilities.group.edit')
        ->where('id', '[0-9]+');

    Route::get('/create', 'LevelController@form')->name('module.capabilities.group.create');

    Route::post('/save', 'LevelController@save')->name('module.capabilities.group.save');

    Route::post('/remove', 'LevelController@remove')->name('module.capabilities.group.remove');

});

Route::group(['prefix' => '/admin-cp/capabilities/category', 'middleware' => 'auth'], function() {
    Route::get('/', 'CategoryController@index')->name('module.capabilities.category');

    Route::get('/getdata', 'CategoryController@getData')->name('module.capabilities.category.getdata');

    Route::get('/edit/{id}', 'CategoryController@form')->name('module.capabilities.category.edit')
        ->where('id', '[0-9]+');

    Route::get('/create', 'CategoryController@form')->name('module.capabilities.category.create');

    Route::post('/save', 'CategoryController@save')->name('module.capabilities.category.save');

    Route::post('/remove', 'CategoryController@remove')->name('module.capabilities.category.remove');

});

Route::group(['prefix' => '/admin-cp/capabilities/title', 'middleware' => 'auth'], function() {
    Route::get('/', 'CapabilitiesTitleController@index')->name('module.capabilities.title');

    Route::get('/getdata', 'CapabilitiesTitleController@getData')->name('module.capabilities.title.getdata');

    Route::get('/edit/{id}', 'CapabilitiesTitleController@form')->name('module.capabilities.title.edit')
        ->where('id', '[0-9]+');

    Route::get('/create', 'CapabilitiesTitleController@form')->name('module.capabilities.title.create');

    Route::post('/save', 'CapabilitiesTitleController@save')->name('module.capabilities.title.save');

    Route::post('/copy', 'CapabilitiesTitleController@copy')->name('module.capabilities.title.copy');

    Route::post('/remove', 'CapabilitiesTitleController@remove')->name('module.capabilities.title.remove');

    Route::post('/ajax-get-capabilities', 'CapabilitiesTitleController@ajaxGetCapabilities')
        ->name('module.capabilities.title.ajax_get_capabilities');

    Route::post('/import-capabilities-title', 'CapabilitiesTitleController@importCapabilitiesTitle')
        ->name('module.capabilities.title.import_capabilities_title');

    Route::get('/export-capabilities-title', 'CapabilitiesTitleController@exportCapabilitiesTitle')
        ->name('module.capabilities.title.export_capabilities_title');

    Route::get('/{id}/course', 'CapabilitiesTitleController@course')
        ->name('module.capabilities.title.course')->where('id', '[0-9]+');

    Route::post('/{id}/save-course', 'CapabilitiesTitleController@saveCourse')
        ->name('module.capabilities.title.save_course')->where('id', '[0-9]+');

    Route::get('/{id}/get-course', 'CapabilitiesTitleController@getCourse')
        ->name('module.capabilities.title.get_course')->where('id', '[0-9]+');

    Route::post('/{id}/remove-course', 'CapabilitiesTitleController@removeCourse')
        ->name('module.capabilities.title.remove_course')->where('id', '[0-9]+');

    Route::post('/{id}/import-capabilities-title-subject', 'CapabilitiesTitleController@importCapabilitiesTitleSubject')
        ->name('module.capabilities.title.import_capabilities_title_subject');

});

Route::group([
    'prefix' => '/admin-cp/capabilities/review',
    'middleware' => 'auth'
], function() {

    Route::get('/', 'ReviewController@index')->name('module.capabilities.review');

    Route::get('/getdata', 'ReviewController@getData')->name('module.capabilities.review.getdata');

});

Route::group([
    'prefix' => '/admin-cp/capabilities/review/{user_id}',
    'middleware' => 'auth',
    'where' => [
        'user_id' => '[0-9]+'
    ]
], function() {

    Route::get('/', 'ReviewController@detail')->name('module.capabilities.review.user.index');

    Route::get('/getdata', 'ReviewController@getDataDetail')->name('module.capabilities.review.user.getdata');

    Route::get('/edit/{id}', 'ReviewController@form')->name('module.capabilities.review.user.edit')->where('id', '[0-9]+');

    Route::get('/view/{id}', 'ReviewController@view')->name('module.capabilities.review.user.view')->where('id', '[0-9]+');

    Route::get('/create', 'ReviewController@form')->name('module.capabilities.review.user.create');

    Route::get('/view-course', 'ReviewController@viewCourse')->name('module.capabilities.review.user.view_course');

    Route::post('/chart-course', 'ReviewController@chartCourse')->name('module.capabilities.review.user.chart_course');

    Route::post('/send', 'ReviewController@sendReview')->name('module.capabilities.review.user.send');

    Route::post('/save', 'ReviewController@save')->name('module.capabilities.review.user.save');

    Route::post('/remove', 'ReviewController@remove')->name('module.capabilities.review.user.remove');

    Route::post('/get-practical', 'ReviewController@getPracticalGoal')->name('module.capabilities.review.user.getpractical');

    Route::get('/export/{id}', 'ReviewController@exportReview')->name('module.capabilities.review.user.export');

    Route::post('/modal-dictionary/{id}', 'ReviewController@modalDictionary')
        ->name('module.capabilities.review.user.modal_dictionary')
        ->where('id', '[0-9]+');

});

Route::group([
    'prefix' => '/admin-cp/capabilities/review/result',
    'middleware' => 'auth',
], function() {

    Route::get('/', 'ResultController@index')->name('module.capabilities.review.result.index');

    Route::get('/getdata', 'ResultController@getData')->name('module.capabilities.review.result.getdata');

    Route::get('/create', 'ResultController@form')->name('module.capabilities.review.result.create');

    Route::get('/edit/{id}', 'ResultController@form')->name('module.capabilities.review.result.edit');

    Route::get('/export/{id}', 'ResultController@view')->name('module.capabilities.review.result.export');

    Route::post('/save', 'ResultController@save')->name('module.capabilities.review.result.save');

    Route::post('/remove', 'ResultController@remove')->name('module.capabilities.review.result.remove');

    Route::post('/send', 'ResultController@send')->name('module.capabilities.review.result.send');

});

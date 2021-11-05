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
Route::group(['prefix' => '/survey', 'middleware' => 'auth'], function() {
    Route::get('/', 'FrontendController@index')->name('module.survey');
    Route::get('/getdata', 'FrontendController@getData')->name('module.survey.get_data');
    Route::get('/user/{id}', 'FrontendController@getSurveyUser')->name('module.survey.user')->where('id', '[0-9]+');
    Route::get('/edit/{id}', 'FrontendController@editSurveyUser')->name('module.survey.user.edit')->where('id', '[0-9]+');
    Route::post('/save', 'FrontendController@saveSurveyUser')->name('module.survey.user.save');
});

Route::group(['prefix' => '/admin-cp/survey/template', 'middleware' => 'auth'], function() {
    Route::get('/', 'TemplateController@index')->name('module.survey.template');
    Route::get('/getdata', 'TemplateController@getData')->name('module.survey.template.getdata');
    Route::get('/edit/{id}', 'TemplateController@form')->name('module.survey.template.edit')->where('id', '[0-9]+');
    Route::get('/create', 'TemplateController@form')->name('module.survey.template.create');
    Route::post('/save', 'TemplateController@save')->name('module.survey.template.save');
    Route::post('/remove', 'TemplateController@remove')->name('module.survey.template.remove');

    Route::post('/remove-category', 'TemplateController@removeCategory')->name('module.survey.template.remove_category');
    Route::post('/remove-question', 'TemplateController@removeQuestion')->name('module.survey.template.remove_question');
    Route::post('/remove-answer', 'TemplateController@removeAnswer')->name('module.survey.template.remove_answer');

    Route::get('/review-template/{id}', 'TemplateController@reviewTemplate')->name('module.survey.template.review')->where('id', '[0-9]+');
});

Route::group(['prefix' => '/admin-cp/survey', 'middleware' => 'auth'], function() {
    Route::get('/', 'BackendController@index')->name('module.survey.index');
    Route::get('/getdata', 'BackendController@getData')->name('module.survey.getdata');
    Route::get('/create', 'BackendController@form')->name('module.survey.create');
    Route::get('/edit/{id}', 'BackendController@form')->name('module.survey.edit')->where('id', '[0-9]+');
    Route::post('/save', 'BackendController@save')->name('module.survey.save');
    Route::post('/remove', 'BackendController@remove')->name('module.survey.remove');
    Route::post('/ajax-isopen-publish', 'BackendController@ajaxIsopenPublish')->name('module.survey.ajax_isopen_publish');
    Route::post('/edit/{id}/save-object', 'BackendController@saveObject')->name('module.survey.save_object')->where('id', '[0-9]+');
    Route::get('/edit/{id}/get-object', 'BackendController@getObject')->name('module.survey.get_object')->where('id', '[0-9]+');
    Route::get('/edit/{id}/get-user-object', 'BackendController@getUserObject')->name('module.survey.get_user_object')->where('id', '[0-9]+');
    Route::post('/edit/{id}/remove-object', 'BackendController@removeObject')->name('module.survey.remove_object')->where('id', '[0-9]+');
    Route::post('/edit/{id}/import-object', 'BackendController@importObject')->name('module.survey.import_object')->where('id', '[0-9]+');

    Route::post('/edit/{id}/check-unit-child', 'BackendController@getChild')->name('module.survey.get_child')->where('id', '[0-9]+');

    Route::get('/edit/{id}/get-tree-child', 'BackendController@getTreeChild')
        ->name('module.survey.get_tree_child')
        ->where('id', '[0-9]+');

    Route::get('/review-template/{id}', 'BackendController@reviewTemplate')->name('module.survey.review_template')->where('id', '[0-9]+');
});

Route::group(['prefix' => '/admin-cp/survey/report/{survey_id}', 'middleware' => 'auth'], function() {
    Route::get('/', 'ReportController@index')->name('module.survey.report.index')->where('survey_id', '[0-9]+');
    Route::get('/getdata', 'ReportController@getData')->name('module.survey.report.getdata')->where('survey_id', '[0-9]+');
    Route::get('/edit/{user_id}', 'ReportController@form')->name('module.survey.report.edit')->where('survey_id', '[0-9]+')->where('user_id', '[0-9]+');
    Route::get('/export', 'ReportController@export')->name('module.survey.report.export')->where('survey_id', '[0-9]+');
});

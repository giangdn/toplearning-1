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

/* backend */
Route::group(['prefix' => '/admin-cp/training-plan', 'middleware' => 'auth'], function() {
    Route::get('/', 'BackendController@index')->name('module.training_plan')->middleware('permission:training-plan');
    Route::get('/getdata', 'BackendController@getData')->name('module.training_plan.getdata');
    Route::get('/edit/{id}', 'BackendController@form')->name('module.training_plan.edit')->where('id', '[0-9]+');
    Route::get('/create', 'BackendController@form')->name('module.training_plan.create');
    Route::post('/save', 'BackendController@save')->name('module.training_plan.save');
    Route::post('/remove', 'BackendController@remove')->name('module.training_plan.remove');
    Route::post('/ajax-isopen-publish', 'BackendController@ajaxIsopenPublish')->name('module.training_plan.ajax_isopen_publish');

    /* training plan detail */
    Route::get('/plan-detail/{id}', 'PlanDetailController@index')->name('module.training_plan.detail')->where('id', '[0-9]+');
    Route::get('/plan-detail/{id}/getdata', 'PlanDetailController@getData')->name('module.training_plan.detail.getdata');
    Route::get('/plan-detail/{id}/edit/{plan_detail_id}', 'PlanDetailController@form')->name('module.training_plan.detail.edit')->where('id', '[0-9]+');
    Route::get('/plan-detail/{id}/create', 'PlanDetailController@form')->name('module.training_plan.detail.create');
    Route::post('/plan-detail/{id}/save', 'PlanDetailController@save')->name('module.training_plan.detail.save');
    Route::post('/plan-detail/{id}/remove', 'PlanDetailController@remove')->name('module.training_plan.detail.remove');
    Route::post('/plan-detail/{id}/import-plan', 'PlanDetailController@importPlanDetail')->name('module.training_plan.detail.import_plan');
    Route::get('/plan-detail/{id}/export-plan', 'PlanDetailController@exportPlanDetail')->name('module.training_plan.detail.export_plan');
    Route::get('/plan-detail/{id}/export-template', 'PlanDetailController@exportTemplate')->name('module.training_plan.detail.export_template');
    Route::post('/ajax-level-subject', 'PlanDetailController@ajaxLevelSubject')->name('module.training_plan.detail.ajax_level_subject');
    Route::post('/ajax-cost-calculate/{id}', 'PlanDetailController@ajaxCostCalculate')->name('module.training_plan.detail.ajax_cost_calculate');
    Route::post('/ajax-detail-cost/{id}', 'PlanDetailController@ajaxDetailCost')->name('module.training_plan.detail.ajax_detail_cost');
    Route::post('/ajax-type-cost-calculate/{id}', 'PlanDetailController@ajaxTypeCostCalculate')->name('module.training_plan.detail.ajax_type_cost_calculate');
});


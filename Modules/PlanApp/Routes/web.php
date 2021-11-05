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
Route::group(['prefix' => 'planapp', 'middleware' => 'auth'], function() {
    Route::get('/', 'PlanAppController@index');
});

Route::group(['prefix' => '/admin-cp/evaluationform/plan-app', 'middleware' => 'auth'], function() {
    Route::get('/', 'PlanAppController@index')->name('module.plan_app.template');
    Route::get('/getdata', 'PlanAppController@getData')->name('module.plan_app.template.getdata');
    Route::get('/edit/{id}', 'PlanAppController@form')->name('module.plan_app.template.edit');
    Route::get('/create', 'PlanAppController@form')->name('module.plan_app.template.create');
    Route::post('/save', 'PlanAppController@save')->name('module.plan_app.template.save');
    Route::post('/remove', 'PlanAppController@remove')->name('module.plan_app.template.remove');
});

Route::group(['prefix' => '/admin-cp/plan-app', 'middleware' => 'auth'], function() {
    Route::get('/course', 'PlanAppController@showCourses')->name('module.plan_app.course');
    Route::get('/course/getcourses', 'PlanAppController@getCourses')->name('module.plan_app.course.getCourses');
    Route::get('/course/{course}/{type}', 'PlanAppController@showUsers')->name('module.plan_app.user')->where(['course'=>'[0-9]+','type'=>'[0-9]+']);
    Route::get('/user/{course}/{type}/getusers', 'PlanAppController@getUsers')->name('module.plan_app.user.getUsers')->where(['course'=>'[0-9]+','type'=>'[0-9]+']);
    Route::get('/user/form/{id}/{user}', 'PlanAppController@formPlanAppUser')->name('module.plan_app.user.form')->where(['id'=>'[0-9]+','user'=>'[0-9]+']);
    Route::post('/user/form/{id}/{user}', 'PlanAppController@savePlanAppUser')->name('module.plan_app.user.form')->where(['id'=>'[0-9]+','user'=>'[0-9]+']);
    Route::get('/user/form-evaluation/{id}/{user}', 'PlanAppController@formEvaluation')->name('module.plan_app.user.form.evaluation')->where(['id'=>'[0-9]+','user'=>'[0-9]+']);
    Route::post('/user/form-evaluation/{id}/{user}', 'PlanAppController@saveFormEvaluation')->name('module.plan_app.user.form.evaluation')->where(['id'=>'[0-9]+','user'=>'[0-9]+']);
    Route::get('/export-plan-course/{course}/{type}', 'PlanAppController@exportPlanCourse')->name('module.plan_app.export_plan_course')->where(['course'=>'[0-9]+','type'=>'[0-9]+']);

});

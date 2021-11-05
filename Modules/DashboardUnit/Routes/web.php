<?php

Route::group(['prefix' => '/admin-cp/dashboard-unit', 'middleware' => 'auth'], function() {
    Route::get('/', 'DashboardUnitController@index')->name('module.dashboard_unit');

    Route::get('/export-dashboard-training_form/{type}', 'DashboardUnitController@exportDashboardTrainingForm')
        ->name('module.dashboard_unit.export_dashboard_training_form');

    Route::get('/export-dashboard-user-training-form', 'DashboardUnitController@exportDashboardUserTrainingForm')
        ->name('module.dashboard_unit.export_dashboard_user_training_form');

    Route::get('/export-dashboard-course-employee/{type}', 'DashboardUnitController@exportDashboardCourseEmployee')
        ->name('module.dashboard_unit.export_dashboard_course_employee');

    Route::get('/export-dashboard-user-course-employee', 'DashboardUnitController@exportDashboardUserCourseEmployee')
        ->name('module.dashboard_unit.export_dashboard_user_course_employee');

    Route::get('/export-dashboard-quiz/{type}', 'DashboardUnitController@exportDashboardQuiz')
        ->name('module.dashboard_unit.export_dashboard_quiz');

    Route::get('/export-dashboard-user-quiz', 'DashboardUnitController@exportDashboardUserQuiz')
        ->name('module.dashboard_unit.export_dashboard_user_quiz');
});

<?php

Route::group(['prefix'=>'/admin-cp/report-new', 'middleware' => 'auth'],function() {
    Route::get('/', 'ReportNewController@index')->name('module.report_new');

    Route::post('/route', 'ReportNewController@reportRoute')->name('module.report_new.route');

    Route::get('/getData', 'ReportNewController@getData')->name('module.report_new.getData');

    Route::post('/data-chart', 'ReportNewController@dataChart')->name('module.report_new.data_chart');

    Route::post('/getData', 'ReportNewController@getData')->name('module.report_new.postgetData');

    Route::post('/export', 'ReportNewController@export')->name('module.report_new.export');

    Route::match(['get', 'post'],'/review/{id}', 'ReportNewController@review')->name('module.report_new.review')->where(['id'=>'[a-zA-Z0-9]+']);

    Route::get('/getData', 'ReportNewController@getData')->name('module.report_new.getData');

    Route::match(['get', 'post'], 'filter', 'ReportNewController@filter')->name('module.report_new.filter');

    Route::get('/history-export', 'ReportNewController@history')->name('module.report_new.history_export');

    Route::get('/download/{history_id}', 'ReportNewController@download')->name('module.report_new.download');

    Route::get('/getDataHistoryExport', 'ReportNewController@getDataHistoryExport')->name('module.report_new.getDataHistoryExport');

//    Route::get('/getSubjectBySubject', 'ReportNewController@filter')->name('module.report_new.getSubjectBySubject');
});

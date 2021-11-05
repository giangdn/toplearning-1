<?php

Route::group(['prefix'=>'/admin-cp/report', 'middleware' => 'auth'],function() {
    Route::get('/', 'ReportController@index')->name('module.report');

    Route::post('/route', 'ReportController@reportRoute')->name('module.report.route');

    Route::get('/getData', 'ReportController@getData')->name('module.report.getData');

    Route::post('/data-chart', 'ReportController@dataChart')->name('module.report.data_chart');

    Route::post('/getData', 'ReportController@getData')->name('module.report.postgetData');

    Route::post('/export', 'ReportController@export')->name('module.report.export');

    Route::match(['get', 'post'],'/review/{id}', 'ReportController@review')->name('module.report.review')->where(['id'=>'[a-zA-Z0-9]+']);

    Route::get('/getData', 'ReportController@getData')->name('module.report.getData');

    Route::match(['get', 'post'], 'filter', 'ReportController@filter')->name('module.report.filter');

    Route::get('/history-export', 'ReportController@history')->name('module.report.history_export');

    Route::get('/download/{history_id}', 'ReportController@download')->name('module.report.download');

    Route::get('/getDataHistoryExport', 'ReportController@getDataHistoryExport')->name('module.report.getDataHistoryExport');
});

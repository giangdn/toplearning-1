<?php

/*Route::prefix('authorizedunit')->group(function() {
    Route::get('/', 'AuthorizedUnitController@index');
});*/

Route::group(['prefix' => '/admin-cp/authorized-unit', 'middleware' => 'auth'], function() {
    Route::get('/', 'AuthorizedUnitController@index')->name('module.authorized_unit');
    Route::get('/getdata', 'AuthorizedUnitController@getData')->name('module.authorized_unit.getdata');
    Route::get('/getdata-no-manager', 'AuthorizedUnitController@getDataNoManager')->name('module.authorized_unit.getdata_nomanager');
    Route::get('/create', 'AuthorizedUnitController@form')->name('module.authorized_unit.create');
    Route::post('/save', 'AuthorizedUnitController@save')->name('module.authorized_unit.save');
    Route::post('/remove', 'AuthorizedUnitController@remove')->name('module.authorized_unit.remove');
});

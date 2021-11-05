<?php

$middleware = array_merge(\Config::get('lfm.middlewares'), [
    '\UniSharp\LaravelFilemanager\Middlewares\MultiUser',
    '\UniSharp\LaravelFilemanager\Middlewares\CreateDefaultFolder',
]);
$prefix = \Config::get('lfm.url_prefix', \Config::get('lfm.prefix', 'laravel-filemanager'));
$as = 'unisharp.lfm.';
$namespace = '\UniSharp\LaravelFilemanager\Controllers';

Route::post('/media-name', 'MediaController@getFileName')->middleware('auth')->name('get_media');

Route::group(compact('middleware', 'prefix', 'as'), function () {
    Route::get('/jsonitems', [
        'uses' => 'Vendor\LaravelFilemanager\ItemsController@getItems',
        'as' => 'getItems',
    ]);

    Route::get('/newfolder', [
        'uses' => 'Vendor\LaravelFilemanager\FolderController@getAddfolder',
        'as' => 'getAddfolder',
    ]);

    Route::get('/folders', [
        'uses' => 'Vendor\LaravelFilemanager\FolderController@getFolders',
        'as' => 'getFolders',
    ]);

    // upload
    Route::any('/upload', [
        'uses' => 'Vendor\LaravelFilemanager\UploadController@upload',
        'as' => 'upload',
    ]);

    //delete
    Route::any('/delete', [
        'uses' => 'Vendor\LaravelFilemanager\FolderController@delete',
        'as' => 'delete',
    ]);
});

// make sure authenticated
Route::group(['prefix' => $prefix, 'middleware' => 'auth'], function () {

    // Show LFM
    Route::get('/', 'Vendor\LaravelFilemanager\LfmController@show');

    // Show integration error messages
    Route::get('/errors', 'Vendor\LaravelFilemanager\LfmController@getErrors');

    // folders
    /*Route::get('/newfolder', [
        'uses' => 'FolderController@getAddfolder',
        'as' => 'getAddfolder',
    ]);*/

    Route::get('/deletefolder', [
        'uses' => 'FolderController@getDeletefolder',
        'as' => 'getDeletefolder',
    ]);

    /*Route::get('/folders', [
        'uses' => 'FolderController@getFolders',
        'as' => 'getFolders',
    ]);*/

    // crop
    Route::get('/crop', [
        'uses' => 'CropController@getCrop',
        'as' => 'getCrop',
    ]);
    Route::get('/cropimage', [
        'uses' => 'CropController@getCropimage',
        'as' => 'getCropimage',
    ]);
    Route::get('/cropnewimage', [
        'uses' => 'CropController@getNewCropimage',
        'as' => 'getCropimage',
    ]);

    // rename
    Route::get('/rename', [
        'uses' => 'RenameController@getRename',
        'as' => 'getRename',
    ]);

    // scale/resize
    Route::get('/resize', [
        'uses' => 'ResizeController@getResize',
        'as' => 'getResize',
    ]);
    Route::get('/doresize', [
        'uses' => 'ResizeController@performResize',
        'as' => 'performResize',
    ]);

    // download
    Route::get('/download', [
        'uses' => 'DownloadController@getDownload',
        'as' => 'getDownload',
    ]);

    // delete
//    Route::get('/delete', [
//        'uses' => 'DeleteController@getDelete',
//        'as' => 'getDelete',
//    ]);

    // Route::get('/demo', 'DemoController@index');
});

Route::group(compact('prefix', 'as', 'namespace'), function () {
    // Get file when base_directory isn't public
    $images_url = '/' . \Config::get('lfm.images_folder_name') . '/{base_path}/{image_name}';
    $files_url = '/' . \Config::get('lfm.files_folder_name') . '/{base_path}/{file_name}';
    Route::get($images_url, 'RedirectController@getImage')
        ->where('image_name', '.*');
    Route::get($files_url, 'RedirectController@getFile')
        ->where('file_name', '.*');
});

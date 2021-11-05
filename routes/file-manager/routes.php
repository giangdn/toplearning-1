<?php

$prefix = config('lfm.url_prefix');

Route::post('/media-name', 'MediaController@getFileName')->middleware('auth')->name('get_media');

Route::group(['prefix' => $prefix, 'middleware' => ['web', 'auth']], function () {

    Route::get('/', 'FileManager\LfmController@show')->name('lfm.show');

    Route::get('/json-items', 'FileManager\ItemsController@getItems');

    Route::post('/upload', 'FileManager\UploadController@upload')->name('lfm.upload');

    Route::get('/errors', 'FileManager\LfmController@getErrors')->name('lfm.errors');

    Route::get('/folders', 'FileManager\FolderController@getFolders')->name('lfm.folder.items');

    Route::post('/newfolder', 'FileManager\FolderController@getAddfolder')->name('lfm.folder.new');

    Route::get('/delete', 'FileManager\FolderController@delete')->name('lfm.folder.delete');

    Route::get('/delete-folder', 'FileManager\FolderController@getDeletefolder')->name('lfm.folder.delete');
// rename
    Route::get('/rename', [
        'uses' => 'FileManager\RenameController@getRename',
        'as' => 'getRename',
    ]);

    // crop
    /*Route::get('/crop', [
        'uses' => 'FileManager\CropController@getCrop',
        'as' => 'getCrop',
    ]);
    Route::get('/cropimage', [
        'uses' => 'FileManager\CropController@getCropimage',
        'as' => 'getCropimage',
    ]);
    Route::get('/cropnewimage', [
        'uses' => 'FileManager\CropController@getNewCropimage',
        'as' => 'getCropimage',
    ]);



    // scale/resize
    Route::get('/resize', [
        'uses' => 'FileManager\ResizeController@getResize',
        'as' => 'getResize',
    ]);
    Route::get('/doresize', [
        'uses' => 'FileManager\ResizeController@performResize',
        'as' => 'performResize',
    ]);

    // download
    Route::get('/download', [
        'uses' => 'FileManager\DownloadController@getDownload',
        'as' => 'getDownload',
    ]);

    $images_url = '/' . \Config::get('lfm.images_folder_name') . '/{base_path}/{image_name}';
    $files_url = '/' . \Config::get('lfm.files_folder_name') . '/{base_path}/{file_name}';
    Route::get($images_url, 'FileManager\RedirectController@getImage')
        ->where('image_name', '.*');
    Route::get($files_url, 'FileManager\RedirectController@getFile')
        ->where('file_name', '.*');*/
});

<?php

include_once __DIR__ . '/file-manager/routes.php';

include_once __DIR__ . '/component/auth.route.php';

Route::group(['middleware' => 'auth'], function() {
    include_once __DIR__ . '/component/backend.route.php';
});

Route::group(['middleware' => ['notify', 'auth']], function() {
    include_once __DIR__ . '/component/frontend.route.php';
});

Route::group(['middleware' => ['notify', 'auth']], function() {
    include_once __DIR__ . '/component/mobile.route.php';
});

Route::group(['middleware' => 'locale'], function() {
    Route::get('change-language/{language}', 'Frontend\HomeController@changeLanguage')->name('change_language');
});

Route::get('mobile', function (){
    session(['layout' => 'mobile']);
    return view('themes.mobile.auth.login');
})->name('mobile');

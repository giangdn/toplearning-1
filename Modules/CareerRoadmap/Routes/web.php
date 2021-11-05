<?php

Route::group(['prefix' => '/admin-cp', 'middleware' => 'auth'], function() {
    require_once __DIR__ . '/component/backend.route.php';
});

Route::group(['prefix' => '/career-roadmap', 'middleware' => 'auth'], function() {
    require_once __DIR__ . '/component/frontend.route.php';
});
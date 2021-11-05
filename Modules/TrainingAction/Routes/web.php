<?php

Route::group(['prefix' => '/admin-cp', 'middleware' => 'auth'], function() {
    include_once __DIR__ . '/childs/backend.php';
});

Route::group(['prefix' => '/training-action', 'middleware' => 'auth'], function() {
    include_once __DIR__ . '/childs/frontend.php';
});
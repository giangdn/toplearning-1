<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Image Driver
    |--------------------------------------------------------------------------
    |
    | Intervention Image supports "GD Library" and "Imagick" to process images
    | internally. You may choose one of them according to your PHP
    | configuration. By default PHP's "GD Library" implementation is used.
    |
    | Supported: "gd", "imagick"
    |
    */

    'driver' => 'imagick',
    'sizes' => [
        'thumbnail' => [150, 150],
        'medium' => [400, 400],
        'library' => [350, 500],
        'news' => [300, 160],
        'amorial' => [200, 200],
        'larage' => [600, 600],
        'advertising_photo' => [300, 500],
        'slide' => [1500, 350],
        'logo' => [300, 100],
        'favicon' => [16, 16],
        'promotion_group' => [100, 100],
    ],
];

<?php

return [
    'siteName' => '',
    'mail' => [
        'from' => '',
        'name' => ''
    ],
    'company' => [
        'name' => '',
        'email' => '',
    ],
    'images' => [
        'driver' => 'gd',
        'optimize' => true,
        'keepOriginals' => true,
        'dirPattern' => 'uploads',
        'filePattern' => 'images/uploads',
        'types' => ['images'],
        'copies' => []
    ],
    'files' => [
        'dirPattern' => 'uploads',
        'filePattern' => 'uploads',
    ],

];
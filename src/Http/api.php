<?php

use Illuminate\Http\Request;


Route::post('loginUser', '\App\Http\Controllers\Api\Auth@signIn');

Route::get('me','\Mcms\Core\Http\Controllers\Api\Me@index')->middleware(['jwt.auth']);

Route::get('boot', '\Mcms\Core\Http\Controllers\Api\Boot@index')->middleware(['jwt.auth']);
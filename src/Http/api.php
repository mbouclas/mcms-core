<?php

use Illuminate\Http\Request;


Route::post('loginUser', '\App\Http\Controllers\Api\Auth@signIn');

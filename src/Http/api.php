<?php

use Illuminate\Http\Request;


Route::post('loginUser', '\App\Http\Controllers\Api\Auth@signIn');

Route::get('me','\Mcms\Core\Http\Controllers\Api\Me@index')->middleware(['jwt.auth']);

Route::get('boot', '\Mcms\Core\Http\Controllers\Api\Boot@index')->middleware(['jwt.auth']);

Route::post('register', '\Mcms\Core\Http\Controllers\JWTUserController@register');
Route::post('login', '\Mcms\Core\Http\Controllers\JWTUserController@authenticate');
Route::get('checkEmailNotTaken', '\Mcms\Core\Http\Controllers\JWTUserController@checkEmailNotTaken');

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('user', '\Mcms\Core\Http\Controllers\JWTUserController@getAuthenticatedUser');
    Route::get('refreshToken', '\Mcms\Core\Http\Controllers\JWTUserController@refreshToken');
    Route::get('logout', '\Mcms\Core\Http\Controllers\JWTUserController@logout');
    Route::get('/protectedTest', function (Request $request) {
       return 'ok';
    });
});
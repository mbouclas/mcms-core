<?php
Route::group(['prefix' => 'admin/api'], function () {
    Route::group(['middleware' =>['level:50']], function($router)
    {
        $router->resource('mailLog', 'Mcms\Core\Http\Controllers\MailLogController');
        $router->resource('siteSettings', 'Mcms\Core\Http\Controllers\SiteSettingsController');
    });

    Route::group(['middleware' =>['level:98']], function($router)
    {

    });

    Route::group(['middleware' =>['level:2']], function($router)
    {
        $router->post('itemSelector/filter' ,'Mcms\Core\Http\Controllers\ItemSelectorController@filter');
        $router->post('upload/{type}' ,'Mcms\Core\Http\Controllers\UploadController@handle');
        $router->resource('extraField', 'Mcms\Core\Http\Controllers\ExtraFieldController');
        $router->resource('settingsManager', 'Mcms\Core\Http\Controllers\SettingsManagerController');
        $router->put('dynamicTable/rebuild/{parentId}','Mcms\Core\Http\Controllers\DynamicTablesController@rebuild');
        $router->get('dynamicTable/getTableItems/{id}', 'Mcms\Core\Http\Controllers\DynamicTablesController@getTableItems');
        $router->resource('dynamicTable', 'Mcms\Core\Http\Controllers\DynamicTablesController');
    });


    Route::get('translations', [
        'uses' => 'Mcms\Core\Http\Controllers\TranslationsController@get',
        'as' => 'adminTranslationsGet',
        'middleware' => 'role:admin',
    ]);

    Route::post('translation', [
        'uses' => 'Mcms\Core\Http\Controllers\TranslationsController@update',
        'as' => 'adminTranslationsUpdate',
        'middleware' => 'role:admin',
    ]);

    Route::put('translation', [
        'uses' => 'Mcms\Core\Http\Controllers\TranslationsController@create',
        'as' => 'adminTranslationsCreate',
        'middleware' => 'role:admin',
    ]);

    Route::delete('translation/{id}', [
        'uses' => 'Mcms\Core\Http\Controllers\TranslationsController@delete',
        'as' => 'adminTranslationsDelete',
        'middleware' => 'role:admin',
    ]);

    Route::post('translations/sync', [
        'uses' => 'Mcms\Core\Http\Controllers\TranslationsController@sync',
        'as' => 'adminTranslationsSync',
        'middleware' => 'role:admin',
    ]);

    Route::get('locales/init', [
        'uses' => 'Mcms\Core\Http\Controllers\LocalesController@init',
        'as' => 'adminLocalesInit',
        'middleware' => 'role:admin',
    ]);

    Route::get('locales/get', [
        'uses' => 'Mcms\Core\Http\Controllers\LocalesController@get',
        'as' => 'adminLocalesEnable',
        'middleware' => 'role:admin',
    ]);

    Route::post('locales/enable', [
        'uses' => 'Mcms\Core\Http\Controllers\LocalesController@enable',
        'as' => 'adminLocalesEnable',
        'middleware' => 'role:admin',
    ]);

    Route::post('locales/disable', [
        'uses' => 'Mcms\Core\Http\Controllers\LocalesController@disable',
        'as' => 'adminLocalesDisable',
        'middleware' => 'role:admin',
    ]);

    Route::post('locales/update', [
        'uses' => 'Mcms\Core\Http\Controllers\LocalesController@update',
        'as' => 'adminLocalesUpdate',
        'middleware' => 'role:admin',
    ]);

    Route::post('locales/setDefault', [
        'uses' => 'Mcms\Core\Http\Controllers\LocalesController@setDefault',
        'as' => 'adminLocalesSetDefault',
        'middleware' => 'role:admin',
    ]);
});
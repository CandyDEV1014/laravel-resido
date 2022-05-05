<?php

use Botble\Base\Http\Controllers\SystemController;

Route::group(['namespace' => 'Botble\Base\Http\Controllers', 'middleware' => ['web', 'core']], function () {
    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {
        Route::group(['prefix' => 'system/info'], function () {
            Route::match(['GET', 'POST'], '', [
                'as'         => 'system.info',
                'uses'       => 'SystemController@getInfo',
                'permission' => 'superuser',
            ]);
        });

        Route::group(['prefix' => 'system/cache'], function () {

            Route::get('', [
                'as'         => 'system.cache',
                'uses'       => 'SystemController@getCacheManagement',
                'permission' => 'superuser',
            ]);

            Route::post('clear', [
                'as'         => 'system.cache.clear',
                'uses'       => 'SystemController@postClearCache',
                'permission' => 'superuser',
                'middleware' => 'preventDemo',
            ]);
        });

        Route::post('membership/authorize', [
            'as'         => 'membership.authorize',
            'uses'       => 'SystemController@authorize',
            'permission' => false,
        ]);

        Route::get('menu-items-count', [
            'as'         => 'menu-items-count',
            'uses'       => 'SystemController@getMenuItemsCount',
            'permission' => false,
        ]);
    });

    Route::get('settings-language/{alias}', [SystemController::class, 'getLanguage'])->name('settings.language');
});

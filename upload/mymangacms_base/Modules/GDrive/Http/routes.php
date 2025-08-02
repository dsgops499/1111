<?php

$adminPrefix = env('ADMIN_PREFIX', 'admin');

Route::group(['middleware' => ['web', 'auth.admin'], 'prefix' => $adminPrefix . '/gdrive', 'namespace' => 'Modules\GDrive\Http\Controllers'], function() {
    Route::get('/', ['middleware' => ['permission:gdrive.manage_gdrive'], 'as' => 'admin.settings.gdrive', 'uses' => 'GDriveController@index']);
});

<?php

$adminPrefix = env('ADMIN_PREFIX', 'admin');

Route::group(['middleware' => ['web', 'auth.admin'], 'prefix' => $adminPrefix, 'namespace' => 'Modules\Ads\Http\Controllers'], function() {
    Route::post('ads/storePlacements', ['as' => 'admin.ads.storePlacements', 'uses' => 'AdsController@storePlacements']);
    Route::resource('ads', 'AdsController', ["as" => "admin"]);
});

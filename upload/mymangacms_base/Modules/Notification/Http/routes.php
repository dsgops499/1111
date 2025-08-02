<?php

Route::group(['middleware' => ['web', 'auth.admin', 'init'], 'namespace' => 'Modules\Notification\Http\Controllers'], function() {
    Route::get('notifications', ['as' => 'front.notification.index', 'uses' => 'NotificationController@index']);
    Route::get('notifications/markAllAsRead', ['as' => 'front.notification.markAllAsRead', 'uses' => 'NotificationController@markAllAsRead']);
    Route::delete('notifications/destroyAll', ['as' => 'front.notification.destroyAll', 'uses' => 'NotificationController@destroyAll']);
    Route::delete('notifications/{notification}', ['as' => 'front.notification.destroy', 'uses' => 'NotificationController@destroy']);

    Route::post('notification/mark-read', ['as' => 'front.notification.read', 'uses' => 'NotificationController@markAsRead']);
    Route::post('notification/save-settings', ['as' => 'front.notification.saveSettings', 'uses' => 'NotificationController@saveSettings']);
});

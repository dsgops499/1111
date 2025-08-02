<?php

$adminPrefix = env('ADMIN_PREFIX', 'admin');
$authPrefix = env('AUTH_PREFIX', 'auth');

Route::group(['middleware' => ['web', 'auth.admin', 'noajax'], 'prefix' => $adminPrefix, 'namespace' => 'Modules\User\Http\Controllers'], function() {
    Route::resource('user', 'UserController', ['as' => 'admin']);
    Route::resource('role', 'RoleController', ['as' => 'admin']);
    Route::get('subscription', ['as' => 'admin.settings.subscription', 'uses' => 'UserController@showSubscriptionOpt'])->middleware('permission:settings.edit_general');
    Route::post('subscription', ['as' => 'admin.settings.subscription.post', 'uses' => 'UserController@saveSubscriptionOpt'])->middleware('permission:settings.edit_general');
    Route::get('profile', ['as' => 'admin.settings.profile', 'uses' => 'UserController@profile'])->middleware('permission:user.profile');
    Route::post('profile', ['as' => 'admin.settings.profile.save', 'uses' => 'UserController@saveProfile'])->middleware('permission:user.profile');
});

Route::group(['middleware' => ['web', 'init', 'noajax'], 'prefix' => $authPrefix, 'namespace' => 'Modules\User\Http\Controllers'], function () {
    # Login
    Route::get('login', ['middleware' => 'auth.guest', 'as' => 'login', 'uses' => 'AuthController@getLogin']);
    Route::post('login', ['as' => 'login.post', 'uses' => 'AuthController@postLogin']);
    # Register
    if (env('ALLOW_SUBSCRIBE', false)) {
        Route::get('register', ['middleware' => 'auth.guest', 'as' => 'register', 'uses' => 'AuthController@getRegister']);
        Route::post('register', ['as' => 'register.post', 'uses' => 'AuthController@postRegister']);
    }
    # Account Activation
    Route::get('activate/{userId}/{activationCode}', 'AuthController@getActivate');
    # Reset password
    Route::get('reset', ['as' => 'reset', 'uses' => 'AuthController@getReset']);
    Route::post('reset', ['as' => 'reset.post', 'uses' => 'AuthController@postReset']);
    Route::get('reset/{id}/{code}', ['as' => 'reset.complete', 'uses' => 'AuthController@getResetComplete']);
    Route::post('reset/{id}/{code}', ['as' => 'reset.complete.post', 'uses' => 'AuthController@postResetComplete']);
    # Logout
    Route::get('logout', ['as' => 'logout', 'uses' => 'AuthController@getLogout']);
});

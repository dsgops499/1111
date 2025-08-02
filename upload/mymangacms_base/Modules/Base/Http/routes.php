<?php

$adminPrefix = env('ADMIN_PREFIX', 'admin');

Route::group(['middleware' => ['web', 'auth.admin'], 'prefix' => $adminPrefix, 'namespace' => 'Modules\Base\Http\Controllers'], function() use ($adminPrefix) {
    // Dashboard
    Route::get('/', 'DashboardController@index')->name('admin.index')->middleware('permission:dashboard.index');

    // Uploads
    Route::post('uploadMangaCover', ['middleware' => ['permission:manga.manga.create|manga.manga.edit|manage_my_manga'], 'as' => 'admin.upload.cover', 'uses' => 'FileUploadController@uploadMangaCover']);
    Route::post('deleteCover', ['middleware' => ['permission:add_manga|edit_manga|manage_my_manga'], 'as' => 'admin.delete.cover', 'uses' => 'FileUploadController@deleteCover']);
    Route::post('uploadAvatar', ['middleware' => ['permission:user.profile'], 'as' => 'admin.upload.avatar', 'uses' => 'FileUploadController@uploadAvatar']);
    Route::post('deleteAvatar', ['middleware' => ['permission:user.profile'], 'as' => 'admin.delete.avatar', 'uses' => 'FileUploadController@deleteAvatar']);
    Route::post('uploadLogo', ['middleware' => ['permission:settings.edit_themes'], 'as' => 'admin.upload.logo', 'uses' => 'FileUploadController@uploadLogo']);
    Route::post('uploadIcon', ['middleware' => ['permission:settings.edit_themes'], 'as' => 'admin.upload.icon', 'uses' => 'FileUploadController@uploadIcon']);
    Route::post('deleteImg', ['middleware' => ['permission:settings.edit_themes'], 'as' => 'admin.delete.img', 'uses' => 'FileUploadController@deleteImg']);

    // Menu
    Route::resource('menu', 'MenuController', ["as" => "admin.settings"]);

    // Modules
    Route::resource('modules', 'ModulesController', ["as" => "admin"]);

    // Settings
    Route::get('/general', ['as' => 'admin.settings.general', 'uses' => 'SettingsController@general']);
    Route::post('general', ['as' => 'admin.settings.general.save', 'uses' => 'SettingsController@saveGeneral']);

    Route::get('/seo', ['as' => 'admin.settings.seo', 'uses' => 'SettingsController@seo']);
    Route::post('seo', ['as' => 'admin.settings.seo.save', 'uses' => 'SettingsController@saveSeo']);

    Route::get('/theme', ['as' => 'admin.settings.theme', 'uses' => 'SettingsController@theme']);
    Route::post('theme', ['as' => 'admin.settings.theme.save', 'uses' => 'SettingsController@saveTheme']);

    Route::get('/widgets', ['as' => 'admin.settings.widgets', 'uses' => 'SettingsController@widgets']);
    Route::post('widgets', ['as' => 'admin.settings.widgets.save', 'uses' => 'SettingsController@saveWidgets']);

    // Cache
    Route::get('/cache', ['as' => 'admin.settings.cache', 'uses' => 'SettingsController@cache']);
    Route::post('cache', ['as' => 'admin.settings.cache.save', 'uses' => 'SettingsController@saveCache']);
    Route::post('clear-cache', ['as' => 'admin.settings.cache.clear', 'uses' => 'SettingsController@clearCache']);
    Route::post('clear-downloads', ['as' => 'admin.settings.downloads.clear', 'uses' => 'SettingsController@clearDownloads']);
    Route::post('clear-views', ['as' => 'admin.settings.cache.clear-views', 'uses' => 'SettingsController@clearViews']);
    //Route::post('clear-cache-config', ['as' => 'admin.settings.cache.clear-cache-config', 'uses' => 'SettingsController@clearCacheConfig']);
    Route::post('clear-loader-class', ['as' => 'admin.settings.cache.clear-loader-class', 'uses' => 'SettingsController@clearClassLoader']);
    //Route::post('cache-config', ['as' => 'admin.settings.cache.cache-config', 'uses' => 'SettingsController@cacheConfig']);
    Route::post('cache-loader', ['as' => 'admin.settings.cache.cache-loader', 'uses' => 'SettingsController@cacheLoader']);
});

Route::group(['middleware' => ['web', 'init'], 'namespace' => 'Modules\Base\Http\Controllers\Front'], function () {
    Route::get('/', ['as' => 'front.index', 'uses' => 'FrontController@index']);

    Route::get('/contact-us', ['as' => 'front.manga.contactUs', 'uses' => 'FrontController@contactUs']);
    Route::post('contact-us', ['as' => 'front.manga.sendMessage', 'uses' => 'FrontController@sendMessage']);

    Route::get('/{type?}/sitemap.xml', ['as' => 'front.sitemap.adv', 'uses' => 'FrontController@sitemap']);
    Route::get('/sitemap.xml', ['as' => 'front.sitemap', 'uses' => 'FrontController@sitemap']);
    Route::get('/feed', ['as' => 'front.feed', 'uses' => 'FrontController@feed']);
});

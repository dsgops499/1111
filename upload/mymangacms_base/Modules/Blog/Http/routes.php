<?php

$adminPrefix = env('ADMIN_PREFIX', 'admin');
$postSlug = env('POST_SLUG', 'post');

Route::group(['middleware' => ['web', 'auth.admin'], 'prefix' => $adminPrefix, 'namespace' => 'Modules\Blog\Http\Controllers'], function() {
    // CKeditor upload image
    Route::post('uploadPostImage', ['as' => 'admin.posts.uploadImage', 'uses' => 'PostController@uploadImage']);
    Route::get('/uploadBrowseImage', ['as' => 'admin.posts.browseImage', 'uses' => 'PostController@browseImage']);
    Route::post('deletePostImage', ['as' => 'admin.posts.deletePostImage', 'uses' => 'PostController@deletePostImage']);

    // Posts
    Route::resource('posts', 'PostController', ["as" => "admin"]);
    // Pages CMS
    Route::resource('pages', 'PageController', ["as" => "admin"]);
});

Route::group(['middleware' => ['web', 'init'], 'namespace' => 'Modules\Blog\Http\Controllers\Front'], function() use($postSlug) {
    Route::get("/$postSlug/{post}", ['as' => 'front.news', 'uses' => 'BlogController@news']);
    Route::get("/latest-$postSlug", ['as' => 'front.manga.latestNews', 'uses' => 'BlogController@latestNews']);
    Route::get('/{slug?}', ['as' => 'front.pages', 'uses' => 'BlogController@resolvePage'])->where('slug', '[-A-Za-z0-9]+');
});

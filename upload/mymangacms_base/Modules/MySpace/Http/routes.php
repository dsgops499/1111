<?php

Route::group(['middleware' => ['web', 'init'], 'namespace' => 'Modules\MySpace\Http\Controllers'], function() {
    // my profil
    Route::resource('user', 'MySpaceController');

    // bookmarks
    Route::resource('bookmark', 'BookmarkController');
    Route::get('loadTabData', ['as' => 'front.bookmark.loadTabData', 'uses' => 'BookmarkController@loadTabData']);
    Route::post('changeStatus', ['as' => 'front.bookmark.changeStatus', 'uses' => 'BookmarkController@changeStatus']);
    Route::post('deleteChecked', ['as' => 'front.bookmark.deleteChecked', 'uses' => 'BookmarkController@deleteChecked']);
    Route::post('saveNotificationOption', ['as' => 'front.bookmark.saveNotificationOption', 'uses' => 'BookmarkController@saveNotificationOption']);

    // comment
    Route::get('/api/comments/{type}/{id}', ['as' => 'api.comments.index', 'uses' => 'CommentController@index']);
    Route::group(array('prefix' => 'api'), function() {
        Route::resource('comments', 'CommentController', array('except' => array('create', 'edit', 'update')));
    });
});

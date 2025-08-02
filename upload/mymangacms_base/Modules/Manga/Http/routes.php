<?php

$adminPrefix = env('ADMIN_PREFIX', 'admin');
$mangaSlug = env('MANGA_SLUG', 'manga');

Route::group(['middleware' => ['web', 'auth.admin'], 'prefix' => $adminPrefix, 'namespace' => 'Modules\Manga\Http\Controllers'], function() use ($mangaSlug) {
    Route::get("/hot-$mangaSlug", ['as' => 'admin.manga.hot', 'uses' => 'MangaController@hotManga']);
    Route::post('autoMangaInfo', ['as' => 'admin.manga.autoMangaInfo', 'uses' => 'MangaController@autoMangaInfo']);
    Route::post('updateHotManga', ['as' => 'admin.manga.hot.update', 'uses' => 'MangaController@updateHotManga']);

    Route::get("/options-$mangaSlug", ['as' => 'admin.manga.options', 'uses' => 'MangaController@mangaOptions']);
    Route::post('saveMangaOptions', ['as' => 'admin.manga.options.save', 'uses' => 'MangaController@saveMangaOptions']);
    Route::resource("$mangaSlug", 'MangaController', ['names' => [
            'index' => 'admin.manga.index',
            'create' => 'admin.manga.create',
            'store' => 'admin.manga.store',
            'show' => 'admin.manga.show',
            'edit' => 'admin.manga.edit',
            'update' => 'admin.manga.update',
            'destroy' => 'admin.manga.destroy',
    ]]);
    Route::get("$mangaSlug/{manga}/chapter/scraper", ['as' => 'admin.manga.chapter.scraper', 'uses' => 'WebScraperController@scraper']);
    Route::post('startScraper', ['as' => 'admin.manga.chapter.scraper.start', 'uses' => 'WebScraperController@startScraper']);
    Route::post('getTotalChapters', ['as' => 'admin.manga.chapter.scraper.getTotalChapters', 'uses' => 'WebScraperController@getTotalChapters']);
    Route::post('getChapter', ['as' => 'admin.manga.chapter.scraper.getChapter', 'uses' => 'WebScraperController@getChapter']);
    Route::post('abort', ['as' => 'admin.manga.chapter.scraper.abort', 'uses' => 'WebScraperController@abort']);
    Route::post('resume', ['as' => 'admin.manga.chapter.scraper.resume', 'uses' => 'WebScraperController@resume']);

    Route::delete('destroyChapters/{mangaId}', ['as' => 'admin.manga.chapter.destroyChapters', 'uses' => 'ChapterController@destroyChapters']);
    Route::post('notifyUsers', ['as' => 'admin.notify.users', 'uses' => 'ChapterController@notifyUsers']);
    Route::resource("$mangaSlug.chapter", 'ChapterController', ['names' => [
            'index' => 'admin.manga.chapter.index',
            'create' => 'admin.manga.chapter.create',
            'store' => 'admin.manga.chapter.store',
            'show' => 'admin.manga.chapter.show',
            'edit' => 'admin.manga.chapter.edit',
            'update' => 'admin.manga.chapter.update',
            'destroy' => 'admin.manga.chapter.destroy',
    ]]);

    Route::delete('destroyPages/{mangaId}/{chapterId}', ['as' => 'admin.manga.chapter.page.destroyPages', 'uses' => 'PageController@destroyPages']);
    Route::post('downloadImageFromUrl', ['as' => 'admin.manga.chapter.downloadImageFromUrl', 'uses' => 'PageController@downloadImageFromUrl']);
    Route::post('uploadZIPFile', ['as' => 'admin.manga.chapter.uploadZIPFile', 'uses' => 'PageController@uploadZIPFile']);
    Route::post('createExternalPages', ['as' => 'admin.manga.chapter.createExternalPages', 'uses' => 'PageController@createExternalPages']);
    Route::post('movePage', ['as' => 'admin.manga.chapter.movePage', 'uses' => 'PageController@movePage']);
    Route::resource("$mangaSlug.chapter.page", 'PageController', ['names' => [
            'index' => 'admin.manga.chapter.page.index',
            'create' => 'admin.manga.chapter.page.create',
            'store' => 'admin.manga.chapter.page.store',
            'show' => 'admin.manga.chapter.page.show',
            'edit' => 'admin.manga.chapter.page.edit',
            'update' => 'admin.manga.chapter.page.update',
            'destroy' => 'admin.manga.chapter.page.destroy',
    ]]);

    Route::resource('category', 'CategoryController', ["as" => "admin", 'middleware' => 'noajax']);
    Route::resource('tag', 'TagController', ["as" => "admin", 'middleware' => 'noajax']);
    Route::resource('author', 'AuthorController', ["as" => "admin", 'middleware' => 'noajax']);
    Route::resource('comictype', 'ComicTypeController', ["as" => "admin", 'middleware' => 'noajax']);
});

Route::group(['middleware' => ['web', 'init'], 'namespace' => 'Modules\Manga\Http\Controllers\Front'], function() use ($mangaSlug) {
    // manga
    Route::get("/$mangaSlug/{manga}/{chapter}/{page?}", ['as' => 'front.manga.reader', 'uses' => 'ReaderController@reader'])
            ->where('page', '^[1-9][0-9]*');
    Route::post('report-bug', ['as' => 'front.manga.reportBug', 'uses' => 'ReaderController@reportBug']);
    
    Route::get("/$mangaSlug/{manga}", ['as' => 'front.manga.show', 'uses' => 'FrontController@show']);
    Route::get('/latest-release', ['as' => 'front.manga.latestRelease', 'uses' => 'FrontController@latestRelease']);
    Route::get('/random', ['as' => 'front.manga.random', 'uses' => 'FrontController@randomManga']);
    Route::get('/filter', ['as' => 'front.filter', 'uses' => 'FrontController@filter']);
    Route::get('/filterList', ['as' => 'front.filterList', 'uses' => 'FrontController@filterList']);
    Route::get('/changeMangaList', ['as' => 'front.changeMangaList', 'uses' => 'FrontController@changeMangaList']);
    Route::get("/$mangaSlug-list/{type?}/{archive?}", ['as' => 'front.manga.list.archive', 'uses' => 'FrontController@mangalist']);
    Route::get("/$mangaSlug-list", ['as' => 'front.manga.list', 'uses' => 'FrontController@mangalist']);
    Route::get('/topManga', ['as' => 'front.topManga', 'uses' => 'FrontController@topManga']);
    Route::get('/download/{mangaSlug}/{chapterId}', ['as' => 'front.manga.download', 'uses' => 'FrontController@downloadChapter']);

    Route::get('/search', ['as' => 'front.search', 'uses' => 'FrontController@search']);
    Route::get('/advanced-search', ['as' => 'front.advSearch', 'uses' => 'FrontController@advSearch']);
    Route::post('advSearchFilter', ['as' => 'front.advSearch.filter', 'uses' => 'FrontController@advSearchFilter']);
});

<?php

namespace Modules\Manga\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Manga\Listeners\RegisterMangaSidebar;
use Modules\Manga\Listeners\SendNewChapterEmail;
use Modules\Manga\Listeners\MangaViewCounter;
use Modules\Base\Events\BuildingSidebar;
use Modules\Manga\Events\ChapterWasCreated;
use Modules\Manga\Events\MangaViewed;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        BuildingSidebar::class => [
            RegisterMangaSidebar::class
        ],
        ChapterWasCreated::class => [
            SendNewChapterEmail::class,
        ],
        MangaViewed::class => [
            MangaViewCounter::class,
        ],
    ];
}

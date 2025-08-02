<?php

namespace Modules\Notification\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Notification\Listeners\NotifyMangaCreation;
use Modules\Notification\Listeners\NotifyChapterCreation;
use Modules\Notification\Listeners\NotifyPostCreation;
use Modules\Notification\Listeners\NotifyUserCreation;
use Modules\Notification\Listeners\RegisterNotificationSidebar;
use Modules\Base\Events\BuildingSidebar;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        BuildingSidebar::class => [
            RegisterNotificationSidebar::class
        ],
        "Modules\\Manga\\Events\\MangaWasCreated" => [
            NotifyMangaCreation::class
        ],
        "Modules\\Manga\\Events\\ChapterWasCreated" => [
            NotifyChapterCreation::class
        ],
        "Modules\\Blog\\Events\\PostWasCreated" => [
            NotifyPostCreation::class
        ],
        "Modules\\User\\Events\\UserHasRegistered" => [
            NotifyUserCreation::class
        ],
    ];
}

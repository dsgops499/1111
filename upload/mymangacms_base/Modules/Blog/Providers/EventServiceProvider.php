<?php

namespace Modules\Blog\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Base\Events\BuildingSidebar;
use Modules\Blog\Listeners\RegisterBlogSidebar;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        BuildingSidebar::class => [
            RegisterBlogSidebar::class
        ],
    ];
}

<?php

namespace Modules\Base\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Base\Events\BuildingSidebar;
use Modules\Base\Listeners\RegisterDefaultSidebar;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        BuildingSidebar::class => [
            RegisterDefaultSidebar::class
        ],
    ];
}

<?php

namespace Modules\Ads\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Base\Events\BuildingSidebar;
use Modules\Ads\Listeners\RegisterAdsSidebar;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        BuildingSidebar::class => [
            RegisterAdsSidebar::class
        ],
    ];
}

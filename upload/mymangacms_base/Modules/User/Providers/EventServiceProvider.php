<?php

namespace Modules\User\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\User\Listeners\SendRegistrationConfirmationEmail;
use Modules\User\Listeners\SendResetCodeEmail;
use Modules\User\Listeners\RegisterUserSidebar;
use Modules\Base\Events\BuildingSidebar;
use Modules\User\Events\UserHasBegunResetProcess;
use Modules\User\Events\UserHasRegistered;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        BuildingSidebar::class => [
            RegisterUserSidebar::class
        ],
        UserHasRegistered::class => [
            SendRegistrationConfirmationEmail::class,
        ],
        UserHasBegunResetProcess::class => [
            SendResetCodeEmail::class,
        ],
    ];
}

<?php

namespace Modules\Base\Providers;

use Illuminate\Support\ServiceProvider;
use Maatwebsite\Sidebar\SidebarManager;
use Modules\Base\Http\Controllers\Sidebar\AdminSidebar;

class SidebarServiceProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * Register the service provider.
     * @return void
     */
    public function register()
    {
        //
    }

    public function boot(SidebarManager $manager)
    {
        $manager->register(AdminSidebar::class);
//        if ($this->app['asgard.onBackend'] === true) {
//            $manager->register(AdminSidebar::class);
//        }
    }
}

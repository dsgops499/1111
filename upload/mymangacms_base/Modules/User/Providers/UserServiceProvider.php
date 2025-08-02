<?php

namespace Modules\User\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Modules\User\Contracts\Authentication;
use Modules\User\Contracts\RoleRepository;
use Modules\User\Contracts\UserRepository;
use Modules\User\Http\Middleware\GuestMiddleware;
use Modules\User\Http\Middleware\LoggedInMiddleware;
use Illuminate\Support\Facades\Auth;
use Modules\User\Guards\Sentinel;

class UserServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;
    protected $middleware = [
        'auth.guest' => GuestMiddleware::class,
        'logged.in' => LoggedInMiddleware::class,
    ];

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
        
        Auth::extend('sentinel-guard', function () {
            return new Sentinel();
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(\Cartalyst\Sentinel\Laravel\SentinelServiceProvider::class);
        
        $this->app->bind(
            UserRepository::class,
            "Modules\\User\\Repositories\\SentinelUserRepository"
        );
        $this->app->bind(
            RoleRepository::class,
            "Modules\\User\\Repositories\\SentinelRoleRepository"
        );
        $this->app->bind(
            Authentication::class,
            "Modules\\User\\Repositories\\SentinelAuthentication"
        );
        
        foreach ($this->middleware as $name => $class) {
            $this->app['router']->aliasMiddleware($name, $class);
        }
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('modules\user.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'user'
        );
        $this->mergeConfigFrom(
            __DIR__.'/../Config/permissions.php', 'user.permissions'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/user');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ]);

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/user';
        }, \Config::get('view.paths')), [$sourcePath]), 'user');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/user');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'user');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'user');
        }
    }

    /**
     * Register an additional directory of factories.
     * @source https://github.com/sebastiaanluca/laravel-resource-flow/blob/develop/src/Modules/ModuleServiceProvider.php#L66
     */
    public function registerFactories()
    {
        if (! app()->environment('production')) {
            app(Factory::class)->load(__DIR__ . '/Database/factories');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}

<?php

namespace Modules\Base\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Modules\Base\Entities\Option;

class InitFront
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     * @param Redirector $redirect
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        if($this->alreadyInstalled()) {
            $options = Cache::remember('options', 300, function()
            {
                $opts = Option::pluck('value', 'key');
                unset($opts['site.gdrive']);
                return  $opts;
            });

            // bootswatch variation
            Cache::remember('theme', 300, function() use ($options) {
                $theme = $options['site.theme'];
                if (strpos($theme, 'default') !== false) {
                    $tab = explode('.', $theme);
                    $theme = $tab[0];
                }
                return $theme;
            });

            Cache::remember('variation', 300, function() use ($options) {
                $theme = $options['site.theme'];
                $variation = "";
                if (strpos($theme, 'default') !== false) {
                    $tab = explode('.', $theme);
                    $variation = $tab[1];
                }
                return $variation;
            });

            // set language
            \App::setLocale($options['site.lang'], 'en');

            // set orientation
            config(['settings.orientation' => $options['site.orientation']]);
        } else {
            return redirect()->route('LaravelInstaller::welcome');
        }
        return $next($request);
    }

    /**
     * If application is already installed.
     *
     * @return bool
     */
    public function alreadyInstalled()
    {
        return file_exists(storage_path('installed'));
    }
}

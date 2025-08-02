<?php

namespace Modules\Base\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Session;

use Modules\Base\Entities\Option;
use Modules\Base\Entities\Menu;

/**
 * Settings Controller Class
 * 
 * PHP version 5.4
 *
 * @category PHP
 * @package  Controller
 * @author   cyberziko <cyberziko@gmail.com>
 * @license  commercial http://getcyberworks.com/
 * @link     http://getcyberworks.com/
 */
class SettingsController extends Controller
{

    /**
     * Constructor
     * 
     */
    public function __construct()
    {
        $this->middleware('noajax');
        $this->middleware('permission:settings.edit_general', ['except' => ['theme', 'saveTheme']]);
        $this->middleware('permission:settings.edit_themes', ['only' => ['theme', 'saveTheme']]);
    }

    /**
     * General page
     * 
     * @return type
     */
    public function general()
    {
        $options = Option::pluck('value', 'key');

        $langRootDirectory = resource_path() . "/lang";
        $languagesDirectories = File::directories($langRootDirectory);

        $languages = array();
        foreach ($languagesDirectories as $directory) {
            $language = substr(
                $directory, 
                strrpos($directory, DIRECTORY_SEPARATOR) + 1
            );
            
            $languages[$language] = $language;
        }
	
        $pagination = json_decode($options['site.pagination']);
        $comment = json_decode($options['site.comment']);
        $captcha = json_decode($options['site.captcha']);
        
        return view('base::admin.settings.general',
            [
                "options" => $options, 
                "languages" => $languages,
                "pagination" => $pagination,
                "comment" => $comment,
                "captcha" => $captcha
            ]
        );        
    }

    /**
     * Save General settings
     * 
     * @return type
     */
    public function saveGeneral()
    {
        $input = clean(Input::all());

        foreach ($input as $key => $value) {
            $option = str_replace('_', '.', $key);

            if($option == "site.pagination" || $option == "site.comment") {
                $value = json_encode($value);
            }
            
            if($option == "site.captcha") {
                if(!is_null($value['secret_key'])){setEnvironmentValue('NOCAPTCHA_SECRET', $value['secret_key']);}
                if(!is_null($value['site_key'])){setEnvironmentValue('NOCAPTCHA_SITEKEY', $value['site_key']);}
                
                $value = json_encode($value);
            }
            
            Option::findByKey($option)
                ->update(
                    [
                        'value' => $value
                    ]
                );
        }

        Session::put("sitename", $input['site_name']);
        
        // clean cache
        Cache::forget('options');
        
        return redirect()->back()
            ->withSuccess(Lang::get('messages.admin.settings.update.success'));
    }

    /**
     * SEO page
     * 
     * @return type
     */
    public function seo()
    {
        $options = Option::pluck('value', 'key');
        $advanced = json_decode($options['seo.advanced']);
        
        return view('base::admin.settings.seo', 
                [
                    "options" => $options,
                    "advanced" => $advanced,
                ]
            );
    }

    /**
     * Save SEO settings
     * 
     * @return type
     */
    public function saveSeo()
    {
        $input = clean(Input::all());

        foreach ($input as $key => $value) {
            $option = str_replace('_', '.', $key);

            if($option == "seo.advanced") {
                $value = json_encode($value);
            }

            Option::findByKey($option)
                ->update(
                    [
                        'value' => $value
                    ]
                );
        }

        // clean cache
        Cache::forget('options');
        
        return redirect()->back()
            ->withSuccess(Lang::get('messages.admin.settings.update.success'));
    }

    /**
     * Theme page
     * 
     * @return type
     */
    public function theme()
    {
        $options = Option::pluck('value', 'key');
        $themeOpts = json_decode($options['site.theme.options']);

        $themeRootDirectory = resource_path() . "/views/front/themes";

        $themesDirectories = File::directories($themeRootDirectory);

        $themes = array();
        foreach ($themesDirectories as $directory) {
            $themeName = substr(
                $directory, 
                strrpos($directory, DIRECTORY_SEPARATOR) + 1
            );

            if ($themeName != 'default') {
                $themes[$themeName] = ucfirst($themeName);
            }
        }

        $bootswatch = [
            'default.cerulean' => 'Cerulean',
            'default.cosmo' => 'Cosmo',
            'default.cyborg' => 'Cyborg (Dark)',
            'default.darkly' => 'Darkly (Dark)',
            'default.flatly' => 'Flatly',
            'default.journal' => 'Journal',
            'default.lumen' => 'Lumen',
            'default.paper' => 'Paper',
            'default.readable' => 'Readable',
            'default.sandstone' => 'Sandstone',
            'default.simplex' => 'Simplex',
            'default.slate' => 'Slate (Dark)',
            'default.spacelab' => 'Spacelab',
            'default.superhero' => 'Superhero (Dark)',
            'default.united' => 'United',
            'default.yeti' => 'Yeti'
        ];

        $themes['default - color variation'] = $bootswatch;
        $menus = Menu::where('status',1)->orderBy('id')->get()->pluck('title', 'id');
        
        $readerThemes = [
            'cerulean' => 'Cerulean',
            'cosmo' => 'Cosmo',
            'cyborg' => 'Cyborg (Dark)',
            'darkly' => 'Darkly (Dark)',
            'flatly' => 'Flatly',
            'journal' => 'Journal',
            'lumen' => 'Lumen',
            'paper' => 'Paper',
            'readable' => 'Readable',
            'sandstone' => 'Sandstone',
            'simplex' => 'Simplex',
            'slate' => 'Slate (Dark)',
            'spacelab' => 'Spacelab',
            'superhero' => 'Superhero (Dark)',
            'united' => 'United',
            'yeti' => 'Yeti'
        ];
        
        return view('base::admin.settings.theme', 
            [
                "options" => $options,
                "themes" => $themes,
                "menus" => $menus,
                "themeOpts" => $themeOpts,
                "readerThemes" => $readerThemes
            ]
        );
    }

    /**
     * Save Theme settings
     * 
     * @return type
     */
    public function saveTheme()
    {
        $input = Input::all();

        foreach ($input as $key => $value) {
            $option = str_replace('_', '.', $key);

            if($option == "site.theme.options") {
                $value = json_encode($value);
            }
            
            Option::findByKey($option)
                ->update(
                    [
                        'value' => $value
                    ]
                );
        }

        // clean cache
        Cache::forget('options');
        Cache::forget('theme');
        Cache::forget('variation');
        
        return redirect()->back()
            ->withSuccess(Lang::get('messages.admin.settings.update.success'));
    }
    
    public function widgets()
    {
        $options = Option::pluck('value', 'key');
        $widgets = json_decode($options['site.widgets']);
        
        return view('base::admin.settings.widgets', 
                [
                    "widgets" => $widgets,
                ]
            );
    }

    public function saveWidgets()
    {
        $input = Input::all();

        foreach ($input as $key => $value) {
            $option = str_replace('_', '.', $key);

            if($option == "site.widgets") {
                $value = json_encode($value);
            }

            Option::findByKey($option)
                ->update(
                    [
                        'value' => $value
                    ]
                );
        }

        // clean cache
        Cache::forget('options');
        
        return redirect()->back()
            ->withSuccess(Lang::get('messages.admin.settings.update.success'));
    }
    
    public function cache()
    {
        $options = Option::pluck('value', 'key');
        $cache = json_decode($options['site.cache']);
        
        return view('base::admin.settings.cache', 
                [
                    "cache" => $cache,
                ]
            );
    }

    public function saveCache()
    {
        $input = Input::all();

        foreach ($input as $key => $value) {
            $option = str_replace('_', '.', $key);

            if($option == "site.cache") {
                $value = json_encode($value);
            }

            Option::findByKey($option)
                ->update(
                    [
                        'value' => $value
                    ]
                );
        }

        // clean cache
        Cache::forget('options');
        
        return redirect()->back()
            ->withSuccess(Lang::get('messages.admin.settings.update.success'));
    }
    
    public function clearCache()
    {
        \Artisan::call('cache:clear');
        
        return redirect()->route('admin.index')
            ->withSuccess(Lang::get('messages.admin.settings.cache.cleared'));
    }
    
    public function clearDownloads()
    {
        if (FileUploadController::deleteDownloadsDir()) {
            return redirect()->route('admin.index')
                ->withSuccess(Lang::get('messages.admin.settings.downloads.cleared'));
        } else {
            return redirect()->back();
        }
    }
    
    public function clearViews()
    {
        \Artisan::call('view:clear');
        
        return redirect()->route('admin.index')
            ->withSuccess(Lang::get('messages.admin.settings.cache.cleared'));
    }
    
    public function clearCacheConfig()
    {
        \Artisan::call('config:clear');
        
        return redirect()->route('admin.index')
            ->withSuccess(Lang::get('messages.admin.settings.cache.cleared'));
    }
    
    public function clearClassLoader()
    {
        \Artisan::call('clear-compiled');
        
        return redirect()->route('admin.index')
            ->withSuccess(Lang::get('messages.admin.settings.cache.cleared'));
    }
    
    public function cacheConfig()
    {
        \Artisan::call('config:cache');
        
        return redirect()->route('admin.index')
            ->withSuccess('Config Cached');
    }
    
    public function cacheLoader()
    {
        \Artisan::call('optimize');
        
        return redirect()->route('admin.index')
            ->withSuccess('Class Loader Optimized');
    }

}

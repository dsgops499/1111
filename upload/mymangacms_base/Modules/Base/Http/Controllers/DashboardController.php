<?php

namespace Modules\Base\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;

use Modules\Manga\Entities\Manga;
use Modules\Manga\Entities\Chapter;
use Modules\Base\Entities\Option;
use Modules\User\Entities\User;
 
/**
 * Admin Dashborad Controller Class
 * 
 * PHP version 5.4
 *
 * @category PHP
 * @package  Controller
 * @author   cyberziko <cyberziko@gmail.com>
 * @license  commercial http://getcyberworks.com/
 * @link     http://getcyberworks.com/
 */
class DashboardController extends Controller
{
    CONST NUM_HOT_MANGA = 6;
    CONST NUM_MANGA = 10;
    CONST NUM_CHAPTERS = 10;
    
    /**
     * Load dashboard page
     * 
     * @return view
     */
    public function index()
    {
        $hotmanga = null;
        $mangas = null;
        $chapters = null;
        $mangasCount = 0;
        $chaptersCount = 0;
        
        if (is_module_enabled('Manga')) {
            $hotmanga = Manga::whereNotNull('hot')
                ->orderBy('created_at', 'desc')
                ->take(self::NUM_HOT_MANGA)
                ->get();
            $mangas = Manga::orderBy('created_at', 'desc')->take(self::NUM_MANGA)->get();
            $chapters = Chapter::latestAddeddChapter(self::NUM_CHAPTERS);
            
            $mangasCount = Manga::count('id');
            $chaptersCount = Chapter::count('id');
        }
        
        $sitename = Option::findByKey('site.name')->first();
        Session::put("sitename", $sitename['value']);
        
        $theme = Option::findByKey('site.theme')->first();
        if(str_contains($theme['value'], 'default')) {
            $theme = explode('.', $theme['value'])[1];
        } else {
            $theme = $theme['value'];
        }
        
        $statistics = [
            'chapters' => $chaptersCount,
            'manga' => $mangasCount,
            'users' => User::count('id'),
            'currentTheme' => $theme
        ];

        return view(
            'base::admin.index', 
            [
                'hotmanga' => $hotmanga,
                'mangas' => $mangas,
                'chapters' => $chapters,
                'statistics' => $statistics
            ]
        );
    }
}

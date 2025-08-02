<?php

namespace Modules\Base\Http\Controllers\Front;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\URL;

use Modules\Ads\Entities\Placement;
use Modules\Blog\Entities\Post;
use Modules\Manga\Entities\Chapter;
use Modules\Manga\Entities\Manga;
use Modules\Manga\Entities\Tag;
use Modules\User\Entities\User;

/**
 * Frontpage Controller Class
 * 
 * PHP version 5.4
 *
 * @category PHP
 * @package  Controller
 * @author   cyberziko <cyberziko@gmail.com>
 * @license  commercial http://getcyberworks.com/
 * @link     http://getcyberworks.com/
 */
class FrontController extends Controller
{

    /**
     * Load Homepage
     * 
     * @return view
     */
    public function index()
    {
        $settings = Cache::get('options');
        $theme = Cache::get('theme');
        $variation = Cache::get('variation');
        $limit = json_decode($settings['site.pagination']);
        
        $latestMangaUpdates = array();
        $latestMangaUpdatesResutlSet = array();
        $hotMangaList = array();
        $mangaNews = array();
        $ads = array();
        $topManga = array();
        $topViewsManga = array();
        $tags = array();

        // manga
        if (is_module_enabled('Manga')) {
            $latestMangaUpdatesResutlSet = Manga::latestRelease($limit->homepage);
            foreach ($latestMangaUpdatesResutlSet as $manga) {
                $key = "";
                if(date("d-n-Y", strtotime($manga->chapter_created_at)) == date("d-n-Y", strtotime('-1 day'))) {
                    $key = 'Y';
                } else if (date("d-n-Y", strtotime($manga->chapter_created_at)) == date("d-n-Y", strtotime(date("d-n-Y")))) {
                    $key = 'T';
                } else {
                    $key = date("d/n/Y", strtotime($manga->chapter_created_at));
                }

                if(!array_key_exists($key, $latestMangaUpdates)) {
                    $latestMangaUpdates[$key] = [];
                }

                if(array_key_exists($manga->manga_id, $latestMangaUpdates[$key])) {
                    array_push($latestMangaUpdates[$key][$manga->manga_id]['chapters'],  
                        [
                            'chapter_number' => $manga->chapter_number, 
                            'chapter_name' => $manga->chapter_name,
                            'chapter_slug' => $manga->chapter_slug
                        ]); 
                } else {
                    $latestMangaUpdates[$key][$manga->manga_id] = [
                        'manga_id' => $manga->manga_id, 
                        'manga_name' => $manga->manga_name, 
                        'manga_slug' => $manga->manga_slug,
                                            'manga_status' => $manga->manga_status,
                        'hot' => $manga->hot,
                        'chapters' => [
                            [
                                'chapter_number' => $manga->chapter_number, 
                                'chapter_name' => $manga->chapter_name,
                                'chapter_slug' => $manga->chapter_slug
                            ]
                        ]
                    ];
                }
            }

            $hotMangaResutlSet = Manga::hotManga();
            foreach ($hotMangaResutlSet as $manga) {
                array_push($hotMangaList, $manga);
            }
        }
        
        // news
        if (is_module_enabled('Blog')) {
            $mangaNews = Post::where('posts.status', '1')
                ->limit($limit->news_homepage)
                ->orderBy('created_at', 'desc')
                ->with('user')
                ->with('manga')
                ->get();
        }
        
        // ads
        if (is_module_enabled('Ads')) {
            $homepage = Placement::where('page', '=', 'HOMEPAGE')->first();

            foreach ($homepage->ads()->get() as $key => $ad) {
                $ads[$ad->pivot->placement] = $ad->code;
            }
        }
        
        // widgets
        $widgets = json_decode($settings['site.widgets']);
        
        foreach ($widgets as $widget) {
            if($theme=="colorful"){
                if ($widget->type == 'top_rates' && count($topManga) == 0) {
                    $topMangaResutlSet = Manga::topManga(strlen($widget->number)>0?$widget->number:10);
                    foreach ($topMangaResutlSet as $manga) {
                        array_push($topManga, $manga);
                    }
                }
            }
            if ($widget->type == 'top_views' && count($topViewsManga) == 0) { 
                if (is_module_enabled('Manga')) {
                    $topViewsManga = Manga::topViewsManga(strlen($widget->number)>0?$widget->number:10);
                }
            }
            if ($widget->type == 'tags') {
                if (is_module_enabled('Manga')) {
                    $tags = Tag::join('manga_tag','id','=','tag_id')->groupBy('tag_id')->pluck('name', 'slug')->all();
                }
            }
        }

        return view(
            'front.themes.' . $theme . '.index', 
            [
                "theme" => $theme,
                "variation" => $variation,
                "settings" => $settings,
                "hotMangaList" => $hotMangaList,
                "latestMangaUpdates" => $latestMangaUpdates,
                "latestMangaUpdatesResutlSet" => $latestMangaUpdatesResutlSet,
                "topManga" => $topManga,
                "mangaNews" => $mangaNews,
                "ads" => $ads,
                "widgets" => $widgets,
                "topViewsManga" => $topViewsManga,
                "tags" => $tags
            ]
        );
    }

    /**
     * Generate sitemap.xml
     * 
     * @return type
     */
    public function sitemap($type = "") {
        $page_limit = 500;
        $manga_page_pattern = "manga_page_";
            
        if ($type == "") {
            // create root sitemap
            $sitemap = \App::make("sitemap");
            $sitemap->addSitemap(route('front.sitemap.adv', 'other_urls'), date(DATE_W3C));
            
            $count = Manga::count();
            $res = floor($count / $page_limit);
            $pages = (int) $res + 1;
            
            if ($pages > 1) {
                for ($i=1; $i <= $pages; $i++) {
                    $sitemap->addSitemap(
                            route('front.sitemap.adv', $manga_page_pattern . $i, date(DATE_W3C)));
                }
            } else {
                $mangaList = Manga::all();
                foreach ($mangaList as $manga) {
                    $sitemap->addSitemap(
                            route('front.sitemap.adv', $manga->slug), $manga->created_at);
                }
            }
            $data = $sitemap->generate('sitemapindex');
            return Response::make($data['content'], 200, $data['headers']);
        } else if ($type == "other_urls") {
            // create others sitemap
            $sitemap = \App::make("sitemap");
            
            $settings = Cache::get('options');
            $themeOpts = json_decode($settings['site.theme.options']);

            if(!is_null($themeOpts) && !is_null($themeOpts->main_menu)) {
                $menu = \Modules\Base\Entities\Menu::find($themeOpts->main_menu);
                $menuNodes=array();
                if($menu->status==1) {
                    $menuNodes = (new \Modules\Base\Entities\MenuNode())->getMenuNodes($menu->id);
                    foreach($menuNodes as $node) {
                        if($node->type === 'route' && \Route::has($node->url)) {
                            $sitemap->add(URL::route($node->url), date(DATE_W3C), '0.6', 'daily');
                        }
                    }
                }
            } else {
                $sitemap->add(URL::to('/'), date(DATE_W3C), '1.0', 'daily');
            }
            return $sitemap->render();
        } else if (str_contains ($type, $manga_page_pattern)) {
            // create pagined sitemap
            $sitemap = \App::make("sitemap");

            $page = (int)substr($type, strlen($manga_page_pattern));
            $mangaList = Manga::orderBy('created_at', 'desc')->skip($page_limit * ($page - 1))->take($page_limit)
                    ->get();
            foreach ($mangaList as $manga) {
                $sitemap->addSitemap(
                        route('front.sitemap.adv', $manga->slug), $manga->created_at);
            }
            
            $data = $sitemap->generate('sitemapindex');
            return Response::make($data['content'], 200, $data['headers']);
        } else {
            // create manga sitemap
            $sitemap = \App::make("sitemap");
            $manga = Manga::where('slug', $type)->first();
            $sitemap->add(
                    route('front.manga.show', $manga->slug), $manga->created_at, '0.8', 'weekly'
            );

            $chapterList = Chapter::where('manga_id', '=', $manga->id)
                    ->orderBy('created_at', 'desc')
                    ->get();

            foreach ($chapterList as $chapter) {
                $sitemap->add(
                        route('front.manga.reader', [$manga->slug, $chapter->slug]), $chapter->created_at, '0.8', 'weekly'
                );
            }
            return $sitemap->render();
        }
    }

    /**
     * Generate feed
     * 
     * @return type
     */
    public function feed()
    {
        // create new feed
        $feed = \App::make("feed");

        $settings = Cache::get('options');
        $limit = json_decode($settings['site.pagination'])->homepage;
        
        // creating rss feed with our most recent chapters
        $chapters = Chapter::orderBy('created_at', 'desc')->take($limit)->get();
        
        // set your feed's title, description, link, pubdate and language
        $feed->title = $settings['site.name'];
        $feed->description = $settings['site.description'];
        $feed->link = URL::to('feed');
        $feed->setDateFormat('datetime'); // 'datetime', 'timestamp' or 'carbon'
        $feed->lang = 'en';
        $feed->setShortening(true); // true or false
        $feed->setTextLimit(100); // maximum length of description text

        foreach ($chapters as $chapter) {
            // set item's title, author, url, pubdate, description and content
            $feed->add($chapter->manga->name . ' #' . $chapter->number, '', route('front.manga.reader', [$chapter->manga->slug, $chapter->slug]), $chapter->created_at, $chapter->name, '');
        }

        return $feed->render('atom');
    }

    /**
     * Contact us
     */
    public function contactUs()
    {
        $settings = Cache::get('options');
        $theme = Cache::get('theme');
        $variation = Cache::get('variation');
        $captcha = json_decode($settings['site.captcha']);

        return view(
            'front.themes.' . $theme . '.contact', 
            [
                "theme" => $theme,
                "variation" => $variation,
                "settings" => $settings,
                'captcha' => $captcha,
            ]
        );
    }
    
    public function sendMessage()
    {
        $settings = Cache::get('options');
        $captcha = json_decode($settings['site.captcha']);
        if(isset($captcha->form_contact) && $captcha->form_contact === '1') {
            $rules['g-recaptcha-response'] = 'required|captcha';
            $validation = \Validator::make(Input::all(), $rules);
        }

        if ($validation->passes()) {
            $data = array();
            $data['name'] = filter_input(INPUT_POST, 'name');
            $data['email'] = filter_input(INPUT_POST, 'email');
            $data['subject'] = filter_input(INPUT_POST, 'subject');

            $user = User::find(1);

            Mail::send('base::emails.contact-us', compact('data'), function($message) use ($data,$user)
            {
              $message->to($user->email, $user->username)
                      ->subject('Contact from '.$data['name']);
            });

            return redirect()->back()->withSuccess('Message sent');
        } else {
            return redirect()->back()->withErrors($validation->errors());
        }
    }
}

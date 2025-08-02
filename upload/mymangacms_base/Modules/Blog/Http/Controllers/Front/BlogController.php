<?php

namespace Modules\Blog\Http\Controllers\Front;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;

use Modules\Blog\Entities\Page;
use Modules\Blog\Entities\Post;

/**
 * Blog Controller Class
 * 
 * PHP version 5.4
 *
 * @category PHP
 * @package  Controller
 * @author   cyberziko <cyberziko@gmail.com>
 * @license  commercial http://getcyberworks.com/
 * @link     http://getcyberworks.com/
 */
class BlogController extends Controller 
{
    public function news($slug)
    {
        $post = Post::where('slug', $slug)->first();
        $settings = Cache::get('options');
        $theme = Cache::get('theme');
        $variation = Cache::get('variation');
        
        $advancedSEO = json_decode($settings['seo.advanced']);
            
        return view(
            'front.themes.' . $theme . '.blocs.news.news', 
            [
                "theme" => $theme,
                "variation" => $variation,
                "settings" => $settings,
                "post" => $post,
                "seo" => $advancedSEO
            ]
        );
    }
  
    /**
     * Latest news
     */
    public function latestNews()
    {
        $settings = Cache::get('options');
        $theme = Cache::get('theme');
        $variation = Cache::get('variation');
        
        $advancedSEO = json_decode($settings['seo.advanced']);
        $limit = json_decode($settings['site.pagination'])->newslist;
        $posts = Post::where('posts.status', '1')
            ->orderBy('created_at', 'desc')
            ->with('user')
            ->with('manga')
            ->paginate($limit);

        return view(
            'front.themes.' . $theme . '.blocs.news.latest_news', 
            [
                "theme" => $theme,
                "variation" => $variation,
                "settings" => $settings,
                "posts" => $posts,
                "seo" => $advancedSEO
            ]
        );
    }
    
    public function resolvePage($slug = null)
    {
        $settings = Cache::get('options');
        $theme = Cache::get('theme');
        $variation = Cache::get('variation');

        if(!is_null($slug)) {
            $page = Page::where('slug', $slug)->where('status', '1')->first();
        }

        if(!$page) {
            abort(404);
        }

        $seo = [
            'description' => $page->description,
            'keywords' => $page->keywords
        ];
        
        return view(
            'front.themes.' . $theme . '.blocs.pages.page', 
            [
                "theme" => $theme,
                "variation" => $variation,
                "settings" => $settings,
                "page" => $page,
                "seo" => $seo
            ]
        );
    }
}

<?php

namespace Modules\Manga\Http\Controllers\Front;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Modules\Base\Http\Controllers\FileUploadController;
use Modules\Manga\Events\MangaViewed;

use Modules\Ads\Entities\Placement;
use Modules\Base\Entities\Menu;
use Modules\Base\Entities\MenuNode;
use Modules\Blog\Entities\Post;
use Modules\Manga\Entities\Category;
use Modules\Manga\Entities\Chapter;
use Modules\Manga\Entities\ComicType;
use Modules\Manga\Entities\Manga;
use Modules\Manga\Entities\Status;
use Modules\Manga\Entities\Tag;
use Modules\Manga\Entities\Author;

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
    public function topManga() {
        $settings = Cache::get('options');
        $theme = Cache::get('theme');
        $widgets = json_decode($settings['site.widgets']);
        $topManga = array();
        foreach ($widgets as $widget) {
            if ($widget->type == 'top_rates' && count($topManga) == 0) {
                $topMangaResutlSet = Manga::topManga(strlen($widget->number) > 0 ? $widget->number : 10);
                foreach ($topMangaResutlSet as $manga) {
                    array_push($topManga, $manga);
                }
            }
        }
        return view('front.themes.' . $theme . '.blocs.manga.rating', 
            ["topManga" => $topManga])->render();
    }

    /**
     * Show Manga info page
     * 
     * @param type $slug slug page
     * 
     * @return view
     */
    public function show($slug)
    {
        $mangaInfo = Manga::where('slug', $slug)->first();
        if(is_null($mangaInfo)) {
            abort (404);
        }
        
        $settings = Cache::get('options');
        $theme = Cache::get('theme');
        $variation = Cache::get('variation');

        $mangaOptions = json_decode($settings['manga.options']);
        $advancedSEO = json_decode($settings['seo.advanced']);
        
        // +1 hit
        event(new MangaViewed($mangaInfo));

        // ad placement
        $info = Placement::where('page', '=', 'MANGAINFO')->first();
        $ads = array();

        foreach ($info->ads()->get() as $key => $ad) {
            $ads[$ad->pivot->placement] = $ad->code;
        }
		
        // posts
        $posts = Post::where('manga_id', $mangaInfo->id)
                ->where('posts.status', '1')
                ->orderBy('created_at','desc')
                ->with('user')
                ->get();
        
        // sorted chapters
        $sortedChapters = array();
        $chapters = Chapter::where('manga_id', $mangaInfo->id)
                ->with('user')
                ->get();
        
        foreach ($chapters as $chapter) {
            $sortedChapters[$chapter->number] = $chapter;
        }

        array_multisort(array_keys($sortedChapters), SORT_DESC, SORT_NATURAL, $sortedChapters);

        return View::make(
            'front.themes.' . $theme . '.blocs.manga.show', 
            [
                "theme" => $theme,
                "variation" => $variation,
                "settings" => $settings,
                "manga" => $mangaInfo,
                'posts' => $posts,
                'chapters' => $sortedChapters,
                'mangaOptions' => $mangaOptions,
                'ads' => $ads,
                'seo' => $advancedSEO
            ]
        );
    }
    
    /**
     * Show Manga list page
     * 
     * @return view
     */
    public function mangalist($type="", $archive="")
    {
        $settings = Cache::get('options');
        $theme = Cache::get('theme');
        $variation = Cache::get('variation');
        $limit = json_decode($settings['site.pagination'])->mangalist;
        
        $advancedSEO = json_decode($settings['seo.advanced']);
        $categories = Category::pluck('name', 'id')->all();
        $tags = Tag::join('manga_tag','id','=','tag_id')->groupBy('tag_id')->pluck('name', 'slug')->all();
                
        if ($type == "category") {
            $mangaList = Category::where('slug',$archive)->first()
                    ->manga()->orderBy('name', 'asc')->with('categories')->paginate($limit);
        } else if ($type == "author"){
            $mangaList = Author::where('name', 'like', "%$archive%")->first()
                    ->mangaAuthors()->orderBy('name', 'asc')->with('categories')->paginate($limit);
        } else if ($type == "artist"){
            $mangaList = Author::where('name', 'like', "%$archive%")->first()
                    ->mangaArtists()->orderBy('name', 'asc')->with('categories')->paginate($limit);
        } else if ($type == "tag"){
            $mangaList = Tag::where('slug', $archive)->first()
                    ->manga()->orderBy('name', 'asc')->with('tags')->paginate($limit);
        } else {
            $mangaList = Manga::orderBy('name', 'asc')->with('categories')->paginate($limit);
        }
        
        return View::make(
            'front.themes.' . $theme . '.blocs.manga.list', 
            [
                "theme" => $theme,
                "variation" => $variation,
                "settings" => $settings,
                "filter" => $mangaList,
                'seo' => $advancedSEO,
                'categories' => $categories,
                'tags' => $tags
            ]
        );
    }

    public function changeMangaList() {
        $type = filter_input(INPUT_GET, 'type');
        $settings = Cache::get('options');
        $theme = Cache::get('theme');
        $variation = Cache::get('variation');
        $limit = json_decode($settings['site.pagination'])->mangalist;

        if($type == 'text') {
            $mangaList = Manga::orderBy('name')->get();

            $mangaListGrouped = array();
            foreach ($mangaList as $manga) {
                $firstLetter = substr($manga->name, 0, 1);
                if (strtoupper($firstLetter) >= 'A' && strtoupper($firstLetter) <= 'Z') {
                    if (!array_key_exists(strtoupper($firstLetter), $mangaListGrouped)) {
                        $lettreArray = array();
                        array_push($lettreArray, $manga);
                        $mangaListGrouped[strtoupper($firstLetter)] = $lettreArray;
                    } else {
                        array_push($mangaListGrouped[strtoupper($firstLetter)], $manga);
                    }
                } else {
                    if (!array_key_exists('#', $mangaListGrouped)) {
                        $lettreArray = array();
                        array_push($lettreArray, $manga);
                        $mangaListGrouped['#'] = $lettreArray;
                    } else {
                        array_push($mangaListGrouped['#'], $manga);
                    }
                }
            }

            return View::make(
                'front.themes.' . $theme . '.blocs.manga.list.text', 
                [
                    "theme" => $theme,
                    "variation" => $variation,
                    "settings" => $settings,
                    "mangaList" => $mangaListGrouped,
                ]
            )->render();
        } else if ($type == 'image') {
            $mangaList = Manga::orderBy('name', 'asc')->with('categories')->paginate($limit);

            return View::make(
                'front.themes.' . $theme . '.blocs.manga.list.image', [
                    "theme" => $theme,
                    "variation" => $variation,
                    "settings" => $settings,
                    "filter" => $mangaList
                ]
            )->render();
        }
    }
    
    public function filterList()
    {
        $settings = Cache::get('options');
        $theme = Cache::get('theme');
        $variation = Cache::get('variation');
        $limit = json_decode($settings['site.pagination'])->mangalist;
        
        $cat = filter_input(INPUT_GET, 'cat');
        $alpha = filter_input(INPUT_GET, 'alpha');
        $sortBy = filter_input(INPUT_GET, 'sortBy');
        $asc = filter_input(INPUT_GET, 'asc');
        $author = str_replace('+', ' ', filter_input(INPUT_GET, 'author'));
        $artist = str_replace('+', ' ', filter_input(INPUT_GET, 'artist'));
        $tag = filter_input(INPUT_GET, 'tag');
        
        $direction = 'asc';
        if($asc == 'false') {
            $direction = 'desc';
        }
        
        if ($cat != "") {
            if(is_numeric($cat)) {
                    $mangaList = Manga::where('category_manga.category_id', $cat)
                            ->selectRaw('manga.views,manga.slug,manga.id,manga.name')
                            ->join('category_manga', 'category_manga.manga_id', '=', 'manga.id')
                            ->orderBy($sortBy, $direction)->paginate($limit);
            } else {
                    $mangaList = Manga::where('category.slug', $cat)
                            ->join('category_manga', 'category_manga.manga_id', '=', 'manga.id')
                            ->join('category', 'category_manga.category_id', '=', 'category.id')
                            ->selectRaw('manga.views,manga.slug,manga.id,manga.name')
                            ->orderBy($sortBy, $direction)->paginate($limit);
            }
        } else if ($alpha != "") {
            if($alpha == "Other") {
                    $mangaList = Manga::where('name', 'like', "LOWER($alpha%)")
                                    ->orWhere('name', 'REGEXP', "^[^a-z,A-Z]")
                                    ->selectRaw('manga.views,manga.slug,manga.id,manga.name')
                                    ->orderBy($sortBy, $direction)->paginate($limit);
            } else {
                    $mangaList = Manga::where('name', 'like', "LOWER($alpha%)")
                                    ->orWhere('name', 'like', "$alpha%")
                                    ->selectRaw('manga.views,manga.slug,manga.id,manga.name')
                                    ->orderBy($sortBy, $direction)->paginate($limit);
            }
        } else if ($author != ""){
                $mangaList = Manga::where('author.name', 'like', "%$author%")
                        ->where('author_manga.type', 1)
                        ->join('author_manga', 'author_manga.manga_id', '=', 'manga.id')
                        ->join('author', 'author_manga.author_id', '=', 'author.id')
                        ->selectRaw('manga.views,manga.slug,manga.id,manga.name')
                        ->orderBy($sortBy, $direction)->paginate($limit);
        } else if ($artist != ""){
                $mangaList = Manga::where('author.name', 'like', "%$artist%")
                        ->where('author_manga.type', 2)
                        ->join('author_manga', 'author_manga.manga_id', '=', 'manga.id')
                        ->join('author', 'author_manga.author_id', '=', 'author.id')
                        ->selectRaw('manga.views,manga.slug,manga.id,manga.name')
                        ->orderBy($sortBy, $direction)->paginate($limit);
        } else if ($tag != "") {
                $mangaList = Manga::where('tag.slug', $tag)
                        ->join('manga_tag', 'manga_tag.manga_id', '=', 'manga.id')
                        ->join('tag', 'manga_tag.tag_id', '=', 'tag.id')
                        ->selectRaw('manga.views,manga.slug,manga.id,manga.name')
                        ->orderBy($sortBy, $direction)->paginate($limit);
        } else {
                $mangaList = Manga::selectRaw('manga.views,manga.slug,manga.id,manga.name')->orderBy($sortBy, $direction)->paginate($limit);
        }

        return View::make(
            'front.themes.' . $theme . '.blocs.manga.list.filter', [
                "theme" => $theme,
                "variation" => $variation,
                "settings" => $settings,
                "filter" => $mangaList
            ]
        )->render();
    }

    /**
     * Show Random Manga
     * 
     * @return view
     */
    public function randomManga()
    {
    	$mangas = Manga::select("slug")->get();
        
        if (!is_null($mangas) && count($mangas) > 0) {
            $i = rand(0, count($mangas) - 1);

            return Redirect::route(
                'front.manga.show', ['manga' => $mangas[$i]->slug]
            );
        } else {
            return Redirect::route('front.index');
        }
    }

    /**
     * Search Manga
     * 
     * @return view
     */   
    public function search()
    {
    	$mangas = Manga::searchManga(filter_input(INPUT_GET, 'query'));
		
        $suggestions = array();
        foreach ($mangas as $manga) {
            array_push(
                $suggestions, 
                ['value'=>$manga->name, 'data'=>$manga->slug]
            );
        }

        return Response::json(
            ['suggestions' => $suggestions]
        );
    }
	
    /**
     * download zip file
     */
    public function downloadChapter($mangaSlug, $chapterId) {
        $chapter = Chapter::find($chapterId);
        $chapterSlug = $chapter->slug;

        return FileUploadController::downloadChapter($mangaSlug, $chapterSlug);
    }

    /**
     * Latest release
     */
    public function latestRelease()
    {
        $settings = Cache::get('options');
        $theme = Cache::get('theme');
        $variation = Cache::get('variation');
        $limit = json_decode($settings['site.pagination'])->latest_release;
        
        $advancedSEO = json_decode($settings['seo.advanced']);
        
        $page = Input::get('page', 1);        
        $latestMangaUpdates = array();
        $data = Manga::allLatestRelease($page, $limit);
        foreach ($data['items'] as $manga) {
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
        $mangaList = new LengthAwarePaginator($latestMangaUpdates, $data['totalItems'], $limit, $page,
                 ['path' => Request::url(), 'query' => Request::query()]);
        
        return View::make(
            'front.themes.' . $theme . '.blocs.manga.latest_release', 
            [
                "theme" => $theme,
                "variation" => $variation,
                "settings" => $settings,
                "latestMangaUpdates" => $mangaList,
                'seo' => $advancedSEO
            ]
        );
    }
    
    public function advSearch()
    {
        $settings = Cache::get('options');
        $theme = Cache::get('theme');
        $variation = Cache::get('variation');
        
        $nodes = MenuNode::where('url', 'front.advSearch')->get();
        $found = false;
        if(count($nodes)>0) {
            foreach ($nodes as $node) {
                if(Menu::find($node->menu_id)->status==1) {
                    $found = true;
                    break;
                }
            }
        } else {
            abort(404);
        }
        
        if(!$found) {
            abort(404);
        }
        
        $categories = Category::pluck('name', 'id')->all();
        $types = ComicType::pluck('label', 'id')->all();
        $status = Status::pluck('label', 'id')->all();

        return View::make(
                'front.themes.' . $theme . '.blocs.manga.adv_search',
                [
                    "theme" => $theme,
                    "variation" => $variation,
                    "settings" => $settings,
                    'categories' => $categories,
                    'types' => $types,
                    'status' => $status
            ]
        );
    }
    
    public function advSearchFilter()
    {
        $settings = Cache::get('options');
        $theme = Cache::get('theme');
        $variation = Cache::get('variation');
        $limit = json_decode($settings['site.pagination'])->mangalist;
        
        parse_str(filter_input(INPUT_POST, 'params'), $params);
        $categories = isset($params['categories']) ? $params['categories'] : null;
        $status = isset($params['status']) ? $params['status'] : null;
        $types = isset($params['types']) ? $params['types'] : null;
        $release = !empty($params['release']) ? $params['release'] : null;
        $author = !empty($params['author']) ? strtolower($params['author']) : null;

        $query = Manga::select('manga.*')->groupBy('manga.id')->with('categories');

        if (!is_null($categories)) {
            $query = $query->join('category_manga', 'manga.id', '=', 'category_manga.manga_id')
                    ->whereIn('category_manga.category_id', $categories);
        }
        if (!is_null($status)){
            $query = $query->whereIn('manga.status_id', $status);
        }
        if (!is_null($types)){
            $query = $query->whereIn('manga.type_id', $types);
        }
        if (!is_null($release)){
            $query = $query->where('manga.releaseDate', 'like', $release);
        }
        if (!is_null($author)){
            $query = $query->where('author.name', 'like', "%$author%")
                        ->join('author_manga', 'author_manga.manga_id', '=', 'manga.id')
                        ->join('author', 'author_manga.author_id', '=', 'author.id');
        }

        $mangaList = $query->paginate($limit);

        return View::make(
            'front.themes.' . $theme . '.blocs.manga.list.filter', [
                "theme" => $theme,
                "variation" => $variation,
                "settings" => $settings,
                "filter" => $mangaList
            ]
        )->render();
    }
}

<?php

namespace Modules\Manga\Http\Controllers;

use Goutte\Client;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Modules\Manga\Events\MangaWasCreated;
use Modules\Manga\DataTables\MangaDataTable;
use Modules\User\Contracts\Authentication;
use Modules\Base\Http\Controllers\FileUploadController;

use Modules\Manga\Entities\Category;
use Modules\Manga\Entities\ComicType;
use Modules\Manga\Entities\Manga;
use Modules\Base\Entities\Option;
use Modules\Manga\Entities\Status;
use Modules\Manga\Entities\Tag;
use Modules\Manga\Entities\Author;

/**
 * Manga Controller Class
 * 
 * PHP version 5.4
 *
 * @category PHP
 * @package  Controller
 * @author   cyberziko <cyberziko@gmail.com>
 * @license  commercial http://getcyberworks.com/
 * @link     http://getcyberworks.com/
 */
class MangaController extends Controller
{
    protected $manga;
    private $auth;

    /**
     * Constructor
     * 
     * @param Manga $manga current manga
     */
    public function __construct(Manga $manga, Authentication $auth)
    {
        $this->manga = $manga;
        $this->auth = $auth;
        $this->middleware('noajax', ['except' => ['autoMangaInfo']]);
        $this->middleware('permission:settings.edit_general', ['only' => ['mangaOptions', 'saveMangaOptions']]);
        $this->middleware('permission:manga.manga.hot', ['only' => ['hotManga', 'updateHotManga']]);
        $this->middleware('permission:manga.manga.index|manage_my_manga', ['only' => ['index', 'show']]);
        $this->middleware('permission:manga.manga.create|manage_my_manga', ['only' => ['create', 'store', 'autoMangaInfo']]);
        $this->middleware('permission:manga.manga.edit|manage_my_manga', ['only' => ['edit', 'update', 'autoMangaInfo']]);
        $this->middleware('permission:manga.manga.destroy|manage_my_manga', ['only' => ['delete']]);
    }

    /**
     * Mangas page
     * 
     * @return view
     */
    public function index(MangaDataTable $dataTable)
    {
        return $dataTable->render('manga::admin.manga.index');
    }

    /**
     * Create manga page
     * 
     * @return view
     */
    public function create()
    {
        $status = array('' => trans('messages.admin.manga.create.choose-status')) + Status::pluck('label', 'id')->all();
        $comicTypes = array('' => 'Choose the comic type') + ComicType::pluck('label', 'id')->all();
        $categories = Category::pluck('name', 'id')->all();
        $tags = implode(',', Tag::pluck('name', 'id')->all());
        $authors = implode(',', Author::pluck('name', 'id')->all());

        return view(
            'manga::admin.manga.create', 
            [
                'status' => $status, 
                'comicTypes' => $comicTypes,
                'categories' => $categories,
                'tags' => $tags,
                'authors' => $authors
            ]
        );
    }

    /**
     * Create the page
     * 
     * @return view
     */
    public function store()
    {
        $input = clean(request()->all());

        if (!$this->manga->fill($input)->isValid()) {
            return redirect()->back()
                ->withInput()
                ->withErrors($this->manga->errors);
        }

        $this->createOrUpdate($input, $this->manga);

        event(new MangaWasCreated($this->manga));
        
        return redirect()->route('admin.manga.index');
    }

    /**
     * Show manga info
     * 
     * @param type $id manga identifier
     * 
     * @return view
     */
    public function show($id)
    {
        $mangaInfo = Manga::find($id);
        
        if(($this->auth->id()==$mangaInfo->user_id && $this->auth->hasAccess('manage_my_manga')) 
                || $this->auth->hasAccess('manga.manga.index')) {
            return view('manga::admin.manga.show', ['manga' => $mangaInfo]);
        } else {
            abort(403);
        }
    }

    /**
     * Edit page
     * 
     * @param type $id manga identifier
     * 
     * @return view
     */
    public function edit($id)
    {
        $mangaInfo = Manga::find($id);
	
        if(($this->auth->id()==$mangaInfo->user_id && $this->auth->hasAccess('manage_my_manga')) 
                || $this->auth->hasAccess('manga.manga.edit')) {
            $status = array('' => trans('messages.admin.manga.create.choose-status')) + Status::pluck('label', 'id')->all();
            $comicTypes = array('' => 'Choose the comic type') + ComicType::pluck('label', 'id')->all();
            $categories = Category::pluck('name', 'id')->all();
            $tags = implode(',', Tag::pluck('name', 'id')->all());
            $authors = implode(',', Author::pluck('name', 'id')->all());

            $categories_id = array();
            if (!is_null($mangaInfo->categories)) {
                foreach ($mangaInfo->categories as $category) {
                    array_push($categories_id, $category->id);
                }
            }

            $tags_id = array();
            if (!is_null($mangaInfo->tags)) {
                foreach ($mangaInfo->tags as $tag) {
                    array_push($tags_id, $tag->name);
                }
            }

            $authors_id = array();
            if (!is_null($mangaInfo->authors)) {
                foreach ($mangaInfo->authors as $author) {
                    array_push($authors_id, $author->name);
                }
            }

            $artists_id = array();
            if (!is_null($mangaInfo->artists)) {
                foreach ($mangaInfo->artists as $artist) {
                    array_push($artists_id, $artist->name);
                }
            }

            return view(
                'manga::admin.manga.edit',
                [
                    'manga' => $mangaInfo,
                    'status' => $status,
                    'comicTypes' => $comicTypes,
                    'categories' => $categories,
                    'categories_id' => $categories_id,
                    'tags' => $tags,
                    'authors' => $authors,
                    'tags_id' => implode(',', $tags_id),
                    'authors_id' => implode(',', $authors_id),
                    'artists_id' => implode(',', $artists_id)
                ]
            );
        } else {
            abort(403);
        }
    }

    /**
     * Edit the manga
     * 
     * @param type $id manga identifier
     * 
     * @return view
     */
    public function update($id)
    {
        $this->manga = Manga::find($id);
        if(($this->auth->id()==$this->manga->user_id && $this->auth->hasAccess('manage_my_manga')) 
                || $this->auth->hasAccess('manga.manga.edit')) {
            $input = clean(request()->all());
            $slugDiff = false;
            $newSlug = $input['slug'];
            $oldSlug = $this->manga->slug;
            if ($newSlug !== $oldSlug) {
                $slugDiff = true;
            }

            if (!$this->manga->fill($input)->isValid()) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors($this->manga->errors);
            }

            $this->createOrUpdate($input, $this->manga, $slugDiff, $oldSlug, $newSlug);

            return redirect()->route('admin.manga.show', $id);
        } else {
            abort(403);
        }
    }

    /**
     * Delete manga
     * 
     * @param type $mangaId manga identifier
     * 
     * @return view
     */
    public function destroy($mangaId)
    {
        $manga = Manga::find($mangaId);
        if(($this->auth->id()==$manga->user_id && $this->auth->hasAccess('manage_my_manga')) 
                || $this->auth->hasAccess('manga.manga.destroy')) {
            $manga->deleteMe();
            FileUploadController::cleanMangaDirectory($manga->slug);
            return redirect()->route('admin.manga.index');
        } else {
            abort(403);
        }
    }

    /**
     * Hot manga list
     * 
     * @return view
     */
    public function hotManga()
    {
        $mangas = Manga::select(["id","name"])->whereNull('hot')->get();
        $hotest = Manga::select(["id","name"])->whereNotNull('hot')->get();

        return view(
            'manga::admin.manga.hot',
            ['mangas' => $mangas, 'hotest' => $hotest]
        );
    }

    /**
     * Save the hot list
     * 
     * @return view
     */
    public function updateHotManga()
    {
        $input = request()->get('hotlist');
        $hotlist = explode(",", $input);

        if (count($hotlist > 0)) {
            Manga::where('hot', '<>', 'null')->update(array('hot' => null));

            foreach ($hotlist as $id) {
                Manga::where('id', $id)->update(array('hot' => true));
            }
        }

        return redirect()->back()
            ->withSuccess(trans('messages.admin.manga.hot.update-success'));
    }

    /**
     * Create/update manga
     * 
     * @param type $input inputs
     * @param type $manga manga id
     * 
     * @return void
     */
    private function createOrUpdate($input, $manga, $slugDiff=false, $oldSlug='', $newSlug='')
    {
        $cover = $input['cover'];
        $manga->cover = "1";
        
        if (str_contains($cover, FileUploadController::$TMP_COVER_DIR)) {
            $coverCreated = FileUploadController::createCover($cover, $manga->slug);
            if (!$coverCreated) {
                $manga->cover = null;
            }
        } else if (is_null($cover) || $cover == "") {
            $manga->cover = null;
            
            // clear cover directory
            FileUploadController::cleanCoverDir($manga->slug );
        }

        if($input['status_id'] == '') {
            $manga->status_id = null;
        }
        
        if($input['type_id'] == '') {
            $manga->type_id = null;
        }
        
        $manga->user_id = $this->auth->id();
        $manga->save();

        if (count(request()->get('categories')) > 0) {
            $manga->categories()->detach();
            $manga->categories()->attach(array_values(request()->get('categories')));
        } else {
            $manga->categories()->detach();
        }
        
        // tags
        if (count(request()->get('tags')) > 0) {
            $manga->tags()->detach();
            $tags = explode(",", request()->get('tags'));
            $tags_tosave = array();
            
            foreach ($tags as $index=>$entry) {
                if(strlen(trim($entry))>0) {
                    $tag = Tag::where('name',$entry)->first();
                    if(is_null($tag)) {
                        $tag = new Tag();
                        $tag->slug=str_slug($entry, '-');
                        $tag->name=$entry;
                        $tag->save();
                    }
                    $tags_tosave[$index]=$tag->id;
                }
            }
            if(count($tags_tosave)>0) {
                $manga->tags()->attach($tags_tosave);
            }
        } else {
            $manga->tags()->detach();
        }
        
        // authors
        if (count(request()->get('author')) > 0) {
            $manga->authors()->detach();
            $authors = explode(",", request()->get('author'));
            $authors_tosave = array();
            
            foreach ($authors as $index=>$entry) {
                if(strlen(trim($entry))>0) {
                    $author = Author::where('name',$entry)->first();
                    if(is_null($author)) {
                        $author = new Author();
                        $author->name=$entry;
                        $author->save();
                    }
                    $authors_tosave[$index]=$author->id;
                }
            }
            if(count($authors_tosave)>0) {
                $manga->authors()->attach($authors_tosave);
            }
        } else {
            $manga->authors()->detach();
        }
        
        // artist
        if (count(request()->get('artist')) > 0) {
            $manga->artists()->detach();
            $artists = explode(",", request()->get('artist'));
            $artists_tosave = array();
            
            foreach ($artists as $index=>$entry) {
                if(strlen(trim($entry))>0) {
                    $artist = Author::where('name',$entry)->first();
                    if(is_null($artist)) {
                        $artist = new Author();
                        $artist->name=$entry;
                        $artist->save();
                    }
                    $artists_tosave[$index]=$artist->id;
                }
            }
            if(count($artists_tosave)>0) {
                $manga->artists()->attach($artists_tosave, ['type' => 2]);
            }
        } else {
            $manga->artists()->detach();
        }
        
        // rename directory
        if ($slugDiff) {
            FileUploadController::moveMangaDirectory($oldSlug, $newSlug);
        }
    }

    public function autoMangaInfo() 
    {
        $url = filter_input(INPUT_POST, 'url-data');
        
        $client = new Client();
        $client->setHeader('timeout', '60');
        $crawler = $client->request('GET', $url);
        $contents = array();
        if(strpos($url, 'mangapanda.com')) {
            $contents = $crawler->filter('#mangaproperties table tr')
                ->each(
                    function ($node, $i) {
                        return $node->text();
                    }
                );
            
            array_push($contents, $crawler->filter('#readmangasum p')->text());
        } else if(strpos($url, 'pecintakomik.com')) {
            $contents = $crawler->filterXPath('(//div[@class="post-cnt"])[1]//li')
                ->each(
                    function ($node, $i) {
                        return $node->text();
                    }
                );
            
            array_push($contents, $crawler->filter('h2')->text());
        } else if(strpos($url, 'tumangaonline.com')) {
            $contents = $crawler->filterXPath('//table[@class="tbl table-hover"]//td')
                ->each(
                    function ($node, $i) {
                        return $node->text();
                    }
                );
            
            array_push($contents, $crawler->filter('h1')->text());
            array_push($contents, $crawler->filter('#descripcion')->text());
        } else if(strpos($url, 'lecture-en-ligne.com')) {
            $contents = $crawler->filter('#page .infos td')
                ->each(
                    function ($node, $i) {
                        return $node->text();
                    }
                );
            
            array_push($contents, $crawler->filter('h2')->text());
            array_push($contents, $crawler->filterXPath('(//div[@id="resume"]//p)[2]')->text());
        } else if(strpos($url, 'comicvn.net')) {
            array_push($contents, $crawler->filter('h1')->text());
            array_push($contents, $crawler->filter('li.author a')->text());
            array_push($contents, $crawler->filter('.detail-content p')->text());
        }

        return response()->json(
            [
                'contents' => $contents
            ]
        );
    }
	
    /**
     * manga options
     * 
     * @return view
     */
    public function mangaOptions()
    {
        $options = Option::where('key', 'manga.options')->first();
        $mangaOptions = json_decode($options->value);
        
        return view(
            'manga::admin.manga.options',
            [
                'mangaOptions' => $mangaOptions, 
            ]
        );
    }
	
    /**
     * save manga options
     * 
     * @return view
     */
    public function saveMangaOptions()
    {
    	$input = request()->all();
        unset($input['_token']);

        Option::findByKey("manga.options")
            ->update(
                [
                    'value' => json_encode($input)
                ]
            );

        // clean cache
        Cache::forget('options');
        
        return redirect()->back()
            ->withSuccess(trans('messages.admin.settings.update.success'));
    }	
}

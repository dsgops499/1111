<?php

namespace Modules\Manga\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Response;
use Modules\Base\Http\Controllers\FileUploadController;

use Modules\Manga\Events\ChapterWasCreated;
use Modules\User\Contracts\Authentication;
use Modules\Manga\Entities\Chapter;
use Modules\Manga\Entities\Manga;

/**
 * Chapter Controller Class
 * 
 * PHP version 5.4
 *
 * @category PHP
 * @package  Controller
 * @author   cyberziko <cyberziko@gmail.com>
 * @license  commercial http://getcyberworks.com/
 * @link     http://getcyberworks.com/
 */
class ChapterController extends Controller
{
    protected $chapter;
    private $auth;

    /**
     * Constructor
     * 
     * @param Chapter $chapter current chapter
     */
    public function __construct(Chapter $chapter, Authentication $auth)
    {
        $this->chapter = $chapter;
        $this->auth = $auth;
        $this->middleware('noajax', ['except' => ['notifyUsers']]);
        $this->middleware('permission:manga.chapter.index|manage_my_chapters', ['only' => ['show']]);
        $this->middleware('permission:manga.chapter.create|manage_my_chapters', ['only' => ['create', 'store', 'notifyUsers']]);
        $this->middleware('permission:manga.chapter.edit|manage_my_chapters', ['only' => ['update', 'notifyUsers']]);
        $this->middleware('permission:manga.chapter.destroy|manage_my_chapters', ['only' => ['delete', 'destroyChapters']]);
    }

    /**
     * Load Manga chapter create page
     * 
     * @param type $mangaId manga identifier
     * 
     * @return view
     */
    public function create($mangaId)
    {
        $manga = Manga::find($mangaId);

        return view('manga::admin.manga.chapter.create', ['manga' => $manga]);
    }

    /**
     * Save the new chapter
     * 
     * @return detail page
     */
    public function store($mangaId)
    {
        $input = clean(request()->all());

        if (!$this->chapter->fill($input)->isValid($mangaId)) {
            return redirect()->back()->withInput()->withErrors($this->chapter->errors);
        }

        $this->chapter->user_id = $this->auth->id();

        $manga = Manga::find($mangaId);
        $chapter = $manga->chapters()->save($this->chapter);
        
        // queue send notification
        if (is_module_enabled('MySpace')) {
            event(new ChapterWasCreated($mangaId, $chapter->number, route('front.manga.reader', [$manga->slug, $chapter->number])));
        }
        
        return redirect()->route(
            'admin.manga.chapter.show', 
            ['mangaId' => $mangaId, 'chapterId' => $chapter->id]
        );
    }

    /**
     * Load Manga chapter detail page
     * 
     * @param type $manga   manga identifier
     * @param type $chapter chapter identifier
     * 
     * @return view
     */
    public function show($manga, $chapter)
    {
        $mangaInfo = Manga::find($manga);
        $chapterInfo = Chapter::find($chapter);
        if ((($this->auth->id() == $mangaInfo->user->id || $this->auth->id() == $chapterInfo->user->id) && $this->auth->hasAccess('manage_my_chapters')) || $this->auth->hasAccess('manga.chapter.index')) {
            $settings = Cache::get('options');

            return view('manga::admin.manga.chapter.show', 
                ['manga' => $mangaInfo, 'chapter' => $chapterInfo, 'settings' => $settings]
            );
        } else {
            abort(403);
        }
    }

    /**
     * Load Manga chapter edit page
     * 
     * @param type $mangaId   manga identifier
     * @param type $chapterId chapter identifier
     * 
     * @return edit page with message
     */
    public function update($mangaId, $chapterId)
    {
        $manga = Manga::find($mangaId);
        $chapter = Chapter::find($chapterId);
        if ((($this->auth->id() == $manga->user->id || $this->auth->id() == $chapter->user->id) && $this->auth->hasAccess('manage_my_chapters')) || $this->auth->hasAccess('manga.chapter.edit')) {
            $input = clean(request()->all());
            $slugDiff = false;
            $newSlug = $input['slug'];
            $oldSlug = $chapter->slug;
            if ($newSlug !== $oldSlug) {
                $slugDiff = true;
            }

            if (!$chapter->fill($input)->isValid($mangaId)) {
                return redirect()->back()->withInput()->withErrors($chapter->errors);
            }

            $chapter->user_id = $this->auth->id();
            $chapter->save();

            // rename directory
            if ($slugDiff) {
                FileUploadController::moveChapterDirectory($manga->slug, $oldSlug, $newSlug);
            }

            return redirect()->back()->withSuccess(Lang::get('messages.admin.chapter.update-success'));
        } else {
            abort(403);
        }
    }

    /**
     * Delete chapter
     * 
     * @param type $mangaId   manga identifier
     * @param type $chapterId chapter identifier
     * 
     * @return view
     */
    public function destroy($mangaId, $chapterId)
    {
        $manga = Manga::find($mangaId);
        $chapter = Chapter::find($chapterId);
        if ((($this->auth->id() == $manga->user->id || $this->auth->id() == $chapter->user->id) && $this->auth->hasAccess('manage_my_chapters')) || $this->auth->hasAccess('manga.chapter.destroy')) {
            FileUploadController::cleanChapterDirectory($manga->slug, $chapter->slug);
            $chapter->deleteMe();

            return redirect()->route('admin.manga.show', ['mangaId' => $mangaId]);
        } else {
            abort(403);
        }
    }

    public function destroyChapters($mangaId)
    {
        $ids = Input::get("chapters-ids");
        $manga = Manga::find($mangaId);
                
        if (($this->auth->id() == $manga->user->id && $this->auth->hasAccess('manage_my_chapters')) || $this->auth->hasAccess('manga.chapter.destroy')) {
            if (strlen(trim($ids)) > 0) {
                $tab_ids = explode(',', $ids);

                foreach ($tab_ids as $id) {
                    if ($id != 'all') {
                        $chapter = Chapter::find($id);

                        FileUploadController::cleanChapterDirectory($manga->slug, $chapter->slug);
                        $chapter->deleteMe();
                    }
                }
            }

            return redirect()->route('admin.manga.show', ['mangaId' => $mangaId]);
        } else {
            abort(403);
        }
    }
    
    /**
     * Manually notify users about updates
     */
    public function notifyUsers(){
        $mangaId = filter_input(INPUT_POST, 'mangaId');
        $mangaSlug = filter_input(INPUT_POST, 'mangaSlug');
        
        // queue send notification
        if (is_module_enabled('MySpace')) {
            event(new ChapterWasCreated($mangaId, null, route("front.manga.show", [$mangaSlug])));
        }
        
        return Response::json(
            [
                'status' => 'ok'
            ]
        );
    }
}

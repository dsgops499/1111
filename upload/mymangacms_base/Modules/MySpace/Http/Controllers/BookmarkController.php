<?php

namespace Modules\MySpace\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;
use Modules\Manga\Entities\Chapter;
use Modules\Manga\Entities\Manga;
use Modules\MySpace\Entities\Bookmark;
use Modules\User\Contracts\Authentication;
use Modules\User\Entities\User;

/**
 * Bookmark Controller Class
 * 
 * PHP version 5.4
 *
 * @category PHP
 * @package  Controller
 * @author   cyberziko <cyberziko@gmail.com>
 * @license  commercial http://getcyberworks.com/
 * @link     http://getcyberworks.com/
 */
class BookmarkController extends Controller
{
    private $auth;

    public function __construct(Authentication $auth)
    {
        $this->auth = $auth;
        $this->middleware('auth.admin');
    }
    
    public function index()
    {
        $bookmarkss = Bookmark::myBookmarks($this->auth->id());
        $settings = Cache::get('options');
        $theme = Cache::get('theme');
        $variation = Cache::get('variation');

        $bookmarks = [];
        foreach ($bookmarkss as $bookmark) {
            if (!array_key_exists($bookmark->manga_id, $bookmarks)) {
                $chapters = [
                    [
                        'id' => $bookmark->id,
                        'chapter' => Chapter::find($bookmark->chapter_id),
                        'page_id' => $bookmark->page_id,
                        'created_at' => $bookmark->created_at,
                    ]
                ];
                
                $bookmarks[$bookmark->manga_id] =
                    [
                        'id' => $bookmark->id,
                        'manga_id' => $bookmark->manga_id,
                        'manga_slug' => $bookmark->manga_slug,
                        'manga_name' => $bookmark->manga_name,
                        'created_at' => $bookmark->created_at,
                        'last_chapter' => Manga::find($bookmark->manga_id)->lastChapter(),
                        'chapters' => $chapters
                    ];
            } else {
                array_push($bookmarks[$bookmark->manga_id]['chapters'], [
                    'id' => $bookmark->id,
                    'chapter' => Chapter::find($bookmark->chapter_id),
                    'page_id' => $bookmark->page_id,
                    'created_at' => $bookmark->created_at,
                ]);
            }
        }

        return view(
            'front.themes.' . $theme . '.blocs.bookmark', 
            [
                "theme" => $theme,
                "variation" => $variation,
                "settings" => $settings,
                "bookmarks" => $bookmarks,
            ]
        );
    }

    public function loadTabData()
    {
        $status = filter_input(INPUT_GET, 'status');
        
        $bookmarkss = Bookmark::myBookmarks($this->auth->id(), $status);

        $bookmarks = [];
        foreach ($bookmarkss as $bookmark) {
            if (!array_key_exists($bookmark->manga_id, $bookmarks)) {
                $chapters = [
                    [
                        'id' => $bookmark->id,
                        'chapter' => Chapter::find($bookmark->chapter_id),
                        'page_id' => $bookmark->page_id,
                        'created_at' => $bookmark->created_at,
                    ]
                ];
                
                $bookmarks[$bookmark->manga_id] =
                    [
                        'id' => $bookmark->id,
                        'manga_id' => $bookmark->manga_id,
                        'manga_slug' => $bookmark->manga_slug,
                        'manga_name' => $bookmark->manga_name,
                        'created_at' => $bookmark->created_at,
                        'last_chapter' => Manga::find($bookmark->manga_id)->lastChapter(),
                        'chapters' => $chapters
                    ];
            } else {
                array_push($bookmarks[$bookmark->manga_id]['chapters'], [
                    'id' => $bookmark->id,
                    'chapter' => Chapter::find($bookmark->chapter_id),
                    'page_id' => $bookmark->page_id,
                    'created_at' => $bookmark->created_at,
                ]);
            }
        }

        return view(
            'front.themes.' . Cache::get('theme') . '.blocs.bookmark_frag', 
            [
                "bookmarks" => $bookmarks,
            ]
        )->render();
    }
    
    public function store()
    {
        $manga_id = filter_input(INPUT_POST, 'manga_id');
        $chapter_id = filter_input(INPUT_POST, 'chapter_id');
        $page_slug = filter_input(INPUT_POST, 'page_slug');
        $user_id = $this->auth->id();

        $bookmark = Bookmark::bookmarkExist($this->auth->id(), $manga_id, $chapter_id);
        
        if(is_null($bookmark)){
            $bookmark = new Bookmark();
            $bookmark->user_id = $user_id;
            $bookmark->manga_id = $manga_id;
            $bookmark->chapter_id = isset($chapter_id) ? ($chapter_id=='0'?null:$chapter_id) : null;
            $bookmark->page_id = isset($page_slug) ? ($page_slug=='0'?null:$page_slug) : null;
            
            $tmp = Bookmark::where('user_id', '=', $user_id)
                ->where('manga_id', '=', $manga_id)
                ->first();
            if(is_null($tmp)) {
                $bookmark->status = "currently-reading";
            } else {
                $bookmark->status = $tmp->status;
            }
                
        } else {
            $bookmark->page_id = isset($page_slug) ? ($page_slug=='0'?null:$page_slug) : null;
        }
        
        $bookmark->save();

        return Response::json(
            [
                'status' => 'ok'
            ]
        );
    }
    
    public function destroy($id)
    {
        $rootBookmark = filter_input(INPUT_POST, 'rootBookmark');
        if($rootBookmark == 'true'){
            Bookmark::where('user_id', '=', $this->auth->id())
                ->where('manga_id', '=', $id)
                ->delete();
        } else {
            Bookmark::find($id)->delete();
        }

        return redirect()->back();
    }
    
    public function changeStatus()
    {
        $ids = filter_input(INPUT_POST, 'ids');
        $status = filter_input(INPUT_POST, 'status');

        $bookmarks = Bookmark::where('user_id', '=', $this->auth->id())
            ->whereIn('manga_id', explode(',', $ids))
            ->get();
        
        foreach ($bookmarks as $bookmark){
            $bookmark->status = $status;
            $bookmark->save();
        }

        return Response::json(
            [
                'status' => 'ok'
            ]
        );
    }
    
    public function deleteChecked()
    {
        $ids = filter_input(INPUT_POST, 'ids');

        Bookmark::where('user_id', '=', $this->auth->id())
            ->whereIn('manga_id', explode(',', $ids))
            ->delete();
        
        return Response::json(
            [
                'status' => 'ok'
            ]
        );
    }
    
    public function saveNotificationOption()
    {
        $notify = filter_input(INPUT_POST, 'bookmarks-notify');
        $user = User::find($this->auth->id());

        if($notify == 'true') {
                $user->notify = 1;
        } else {
                $user->notify = 0;
        }

        $user->save();
    }
}

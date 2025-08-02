<?php

namespace Modules\Manga\Http\Controllers\Front;

use Illuminate\Cache\Repository;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;

use Modules\Manga\Entities\Chapter;
use Modules\Manga\Entities\Manga;
use Modules\Manga\Entities\Page;
use Modules\Ads\Entities\Placement;
use Modules\User\Entities\User;

/**
 * Reader Controller Class
 * 
 * PHP version 5.4
 *
 * @category PHP
 * @package  Controller
 * @author   cyberziko <cyberziko@gmail.com>
 * @license  commercial http://getcyberworks.com/
 * @link     http://getcyberworks.com/
 */
class ReaderController extends Controller
{
    protected $cache;
    
    public function __construct()
    {
        $this->cache = app(Repository::class);
    }
    
    /**
     * The Reader
     * 
     * @param type $mangaSlug   manga slug
     * @param type $chapterSlug chapter slug
     * @param type $pageSlug    page slug
     * 
     * @return view
     */
    public function reader($mangaSlug, $chapterSlug, $pageSlug = 1)
    {
        $settings = Cache::get('options');
        $advancedSEO = json_decode($settings['seo.advanced']);
        $captcha = json_decode($settings['site.captcha']);
        $readerTheme = json_decode($settings['site.theme.options'])->reader_theme;
        $cacheTime = json_decode($settings['site.cache'])->reader;

        $currentChapter = $this->cache
            ->remember("reader.$mangaSlug-$chapterSlug.currentChapter", (int)$cacheTime,
                function () use ($mangaSlug, $chapterSlug) {
                    return Chapter::currentChapter($mangaSlug, $chapterSlug);
                }
            );
        
        $sortedChapters = $this->cache
            ->remember("reader.$mangaSlug.chapters", (int)$cacheTime,
                function () use ($mangaSlug) {
                    $chapters = Manga::chaptersByMangaSlug($mangaSlug);
                    $sortedChapters = array();
                    $keys = array();
                    foreach ($chapters as $chapter) {
                        array_push($sortedChapters, $chapter);
                        array_push($keys, $chapter->chapter_number);
                    }

                    array_multisort(
                        $keys,
                        SORT_DESC,
                        SORT_NATURAL,
                        $sortedChapters
                    );
                    
                    return $sortedChapters;
                }
            );

        $allPages = $this->cache
            ->remember("reader.$mangaSlug-$chapterSlug.allPages", (int)$cacheTime,
                function () use ($mangaSlug, $chapterSlug) {
                    return Chapter::currentChapterPages($mangaSlug, $chapterSlug);
                }
            );
        
        $page = null;
        foreach ($allPages as $curpage) {
            if($curpage['page_slug'] == (int)$pageSlug) {
                $page = $curpage;
                break;
            }
        }
        
        $allPagesSorted = $allPages->sortBy('page_slug');
        $allPagesSorted = $allPagesSorted->values();
		
        // sort chapters
        $nextChapter = null;
        $prevChapter = null;
        $prevChapterLastPage = 1;
        
        for ($i = 0; $i < count($sortedChapters); $i++) {
            $chapter = $sortedChapters[$i];
            if ($chapter->chapter_slug == $chapterSlug) {
                if (isset($sortedChapters[$i - 1])) {
                    $nextChapter = $sortedChapters[$i - 1];
                }
                if (isset($sortedChapters[$i + 1])) {
                    $prevChapter = $sortedChapters[$i + 1];
                    $counter = Page::where('chapter_id', '=', $prevChapter->chapter_id)
                            ->count();
                    if ($counter > 0) {
                        $prevChapterLastPage = $counter;
                    }
                }
                break;
            }
        }

        // ad placement
        $reader = Placement::where('page', '=', 'READER')->first();
        $ads = array();

        foreach ($reader->ads()->get() as $key => $ad) {
            $ads[$ad->pivot->placement] = $ad->code;
        }
		
        return View::make(
            'front.reader', 
            [
                'settings' => $settings,
                'page' => $page,
                'current' => $currentChapter,
                'chapters' => $sortedChapters,
                'allPages' => $allPagesSorted,
                'nextChapter' => $nextChapter,
                'prevChapter' => $prevChapter,
                'prevChapterLastPage' => $prevChapterLastPage,
                'ads' => $ads,
                'seo' => $advancedSEO,
                'captcha' => $captcha,
                'readerTheme' => $readerTheme
            ]
        );
    }
    
    public function reportBug()
    {
        $settings = Cache::get('options');
        $captcha = json_decode($settings['site.captcha']);
        if(isset($captcha->form_report) && $captcha->form_report === '1') {
            $rules['g-recaptcha-response'] = 'required|captcha';
            $validation = \Validator::make(Input::all(), $rules);
        }

        if ($validation->passes()) {
            $data = array();
            $data['broken-image'] = filter_input(INPUT_POST, 'broken-image');
            $data['email'] = filter_input(INPUT_POST, 'email');
            $data['subject'] = filter_input(INPUT_POST, 'subject');

            $user = User::find(1);

            Mail::send('manga::emails.report-bug', compact('data'), function($message) use ($data,$user)
            {
              $message->to($user->email, $user->username)
                      ->subject('Report Broken Image');
            });

            return redirect()->back()->with('sentSuccess', 'Message sent');
        } else {
            return redirect()->back()->withErrors($validation->errors());
        }
    }
}

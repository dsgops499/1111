<?php

namespace Modules\Manga\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

use Modules\User\Entities\User;

/**
 * Chapter Model Class
 * 
 * PHP version 5.4
 *
 * @category PHP
 * @package  Controller
 * @author   cyberziko <cyberziko@gmail.com>
 * @license  commercial http://getcyberworks.com/
 * @link     http://getcyberworks.com/
 */
class Chapter extends Model
{

    public $fillable = ['name', 'slug', 'number', 'volume'];
    public static $rules = [
        //'name' => 'required', 
        'number' => 'required|unique:chapter,number,:id,id,manga_id,:mangaid', 
        'slug' => 'required|unique:chapter,slug,:id,id,manga_id,:mangaid'
    ];
    public $errors;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'chapter';

    /**
     * Pages of chapter
     * 
     * @return type
     */
    public function pages()
    {
        return $this->hasMany(Page::class)->orderBy('slug');
    }

    /**
     * Last page
     * 
     * @return int
     */
    public function lastPage()
    {
        if (count($this->pages())) {
            return $this->pages()->getResults()->last();
        } else {
            return 0;
        }
    }

    /**
     * Delete chapter
     * 
     * @return type
     */
    public function deleteMe()
    {
        // delete all related pages 
        $this->pages()->delete();

        // delete the chapter
        return parent::delete();
    }

    /**
     * chapter owner
     * 
     * @return type
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * manga chapter
     * 
     * @return type
     */
    public function manga()
    {
        return $this->belongsTo(Manga::class);
    }
    
    public function scopeLatestAddeddChapter($query, $limit) {
        return Chapter::join('manga', 'manga.id', '=', 'manga_id')
                        ->join('users', 'users.id', '=', 'chapter.user_id')
                        ->select(
                                [
                                    'manga.name as manga_name',
                                    'manga.slug as manga_slug',
                                    'chapter.number',
                                    'chapter.name',
                                    'chapter.created_at',
                                    'chapter.manga_id',
                                    'chapter.id',
                                    'users.username',
                                ]
                        )
                        ->orderBy('created_at', 'desc')->take($limit)->get();
    }

    public function scopeCurrentChapter($query, $mangaSlug, $chapterSlug) {
        return Chapter::join('manga', 'manga.id', '=', 'manga_id')
            ->where('manga.slug', '=', $mangaSlug)
            ->where('chapter.slug', '=', $chapterSlug)
            ->select([
                'manga.id as manga_id',
                'manga.name as manga_name',
                'manga.slug as manga_slug',
                'manga.summary as manga_desc',
                'chapter.id as chapter_id',
                'chapter.name as chapter_name',
                'chapter.number as chapter_number',
                'chapter.slug as chapter_slug',
                'chapter.volume as chapter_volume',
            ])
            ->first();
    }
    
    public function scopeCurrentChapterPages($query, $mangaSlug, $chapterSlug) {
        return Manga::join('chapter', 'chapter.manga_id', '=', 'manga.id')
            ->join('page', 'page.chapter_id', '=', 'chapter.id')
            ->where('manga.slug', '=', $mangaSlug)
            ->where('chapter.slug', '=', $chapterSlug)
            ->select([
                'page.image as page_image',
                'page.slug as page_slug',
                'page.external as external'
            ])
            ->get();
    }
    
    /**
     * Validate chapter
     * 
     * @param type $mangaid manga id
     * 
     * @return boolean
     */
    public function isValid($mangaid)
    {
        static::$rules = str_replace(':mangaid', $mangaid, static::$rules);
        static::$rules = str_replace(':id', $this->id, static::$rules);

        $validation = Validator::make($this->attributes, static::$rules);

        if ($validation->passes()) {
            return true;
        }

        $this->errors = $validation->messages();
        return false;
    }

}

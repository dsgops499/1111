<?php

namespace Modules\Blog\Entities;

use Illuminate\Database\Eloquent\Model;

use Modules\User\Entities\User;
use Modules\Manga\Entities\Manga;

/**
 * Post Model Class
 * 
 * PHP version 5.4
 *
 * @category PHP
 * @package  Controller
 * @author   cyberziko <cyberziko@gmail.com>
 * @license  commercial http://getcyberworks.com/
 * @link     http://getcyberworks.com/
 */
class Post extends Model
{
    public $fillable = ['title', 'slug', 'content', 'status', 'manga_id', 'keywords'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'posts';

    /**
     * chapter owner
     * 
     * @return type
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
	
    public function manga()
    {
        return $this->belongsTo(Manga::class);
    }

}

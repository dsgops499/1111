<?php

namespace Modules\Blog\Entities;

use Illuminate\Database\Eloquent\Model;

use Modules\User\Entities\User;

class Page extends Model {

    protected $table = 'page_cms';
    
    protected $fillable = ['title', 'slug', 'description', 'content', 'keywords', 'status', 'user_id'];

    public function author() {
        return $this->belongsTo(User::class, 'user_id');
    }

}

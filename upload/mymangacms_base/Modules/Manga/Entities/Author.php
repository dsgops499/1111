<?php

namespace Modules\Manga\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Author extends Model {

    protected $table = 'author';
    
    protected $fillable = ['name'];
    public static $rules = [
        'name' => 'required',
    ];
    
    public $errors;

    public function mangaAuthors()
    {
        return $this->belongsToMany(Manga::class)->wherePivot('type', 1);
    }
    
    public function mangaArtists()
    {
        return $this->belongsToMany(Manga::class)->wherePivot('type', 2);
    }
    
    public function isValid() {
        $validation = Validator::make($this->attributes, static::$rules);

        if ($validation->passes()) {
            return true;
        }

        $this->errors = $validation->messages();
        return false;
    }

}

<?php

namespace Modules\Manga\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

/**
 * Category Model Class
 * 
 * PHP version 5.4
 *
 * @category PHP
 * @package  Controller
 * @author   cyberziko <cyberziko@gmail.com>
 * @license  commercial http://getcyberworks.com/
 * @link     http://getcyberworks.com/
 */
class Category extends Model
{

    public $fillable = ['name', 'slug'];
    public static $rules = [
        'name' => 'required', 
        'slug' => 'required|unique:category,slug,:id'
    ];
    public $errors;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'category';

    public function manga()
    {
        return $this->belongsToMany(Manga::class);
    }
    
    /**
     * Validate category
     * 
     * @return boolean
     */
    public function isValid()
    {
        static::$rules = str_replace(':id', $this->id, static::$rules);

        $validation = Validator::make($this->attributes, static::$rules);

        if ($validation->passes()) {
            return true;
        }

        $this->errors = $validation->messages();
        return false;
    }

}

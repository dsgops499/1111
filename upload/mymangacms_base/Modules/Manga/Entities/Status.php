<?php

namespace Modules\Manga\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Status Model Class
 * 
 * PHP version 5.4
 *
 * @category PHP
 * @package  Controller
 * @author   cyberziko <cyberziko@gmail.com>
 * @license  commercial http://getcyberworks.com/
 * @link     http://getcyberworks.com/
 */
class Status extends Model
{

    public $timestamps = false;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'status';
    
}

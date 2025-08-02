<?php 

namespace Modules\Base\Entities;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menus';

    protected $fillable = [
        'title', 'slug', 'status'
    ];

}

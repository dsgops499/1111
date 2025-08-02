<?php

namespace Modules\Notification\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\User\Entities\User;

class Notification extends Model
{
    protected $table = 'notifications';
    protected $fillable = ['user_id', 'type', 'message', 'icon_class', 'link', 'is_read', 'title'];
    protected $appends = ['time_ago'];
    protected $casts = ['is_read' => 'bool'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Return the created time in difference for humans (2 min ago)
     * @return string
     */
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function isRead()
    {
        return $this->is_read === true;
    }
}

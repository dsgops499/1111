<?php

namespace Modules\Notification\Listeners;

use Modules\Blog\Events\PostWasCreated;
use Modules\Notification\Contracts\Notification;
use Modules\User\Entities\User;

class NotifyPostCreation
{
    protected $notification;
    
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Notification $notification)
    {
        $this->notification = $notification;
    }

    /**
     * Handle the event.
     *
     * @param  MangaWasCreated  $event
     * @return void
     */
    public function handle(PostWasCreated $event)
    {
        $users = User::join('notif_settings', 'notif_settings.user_id', '=', 'users.id')
                ->where('post', 1)
                ->select('users.id')
                ->get();
        
        $list = [];
        foreach ($users as $user) {
            if($user->isActivated()) {
                $data = [
                    'user_id' => $user->id,
                    'icon_class' => 'fa fa-2x fa-newspaper-o text-red',
                    'link' => route('front.news', $event->post->slug),
                    'title' => 'New Post!',
                    'message' => $event->post->title,
                    'type' => 'POST',
                    'created_at' => \Carbon\Carbon::now(),
                ];
                array_push($list,$data);
            }
        }

        if(count($list)>0) {
            $this->notification->pushList($list);
        }
    }
}

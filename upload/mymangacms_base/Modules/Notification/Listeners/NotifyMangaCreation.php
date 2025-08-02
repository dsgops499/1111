<?php

namespace Modules\Notification\Listeners;

use Modules\Manga\Events\MangaWasCreated;
use Modules\Notification\Contracts\Notification;
use Modules\User\Entities\User;

class NotifyMangaCreation
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
    public function handle(MangaWasCreated $event)
    {
        $users = User::join('notif_settings', 'notif_settings.user_id', '=', 'users.id')
                ->where('manga', 1)
                ->select('users.id')
                ->get();
        
        $list = [];
        foreach ($users as $user) {
            if($user->isActivated()) {
                $data = [
                    'user_id' => $user->id,
                    'icon_class' => 'fa fa-2x fa-book text-green',
                    'link' => route('front.manga.show', $event->manga->slug),
                    'title' => 'New Manga!',
                    'message' => $event->manga->name,
                    'type' => 'MANGA',
                    'created_at' => \Carbon\Carbon::now(),
                ];
                array_push($list,$data);
            }
        }
            
        $this->notification->pushList($list);
    }
}

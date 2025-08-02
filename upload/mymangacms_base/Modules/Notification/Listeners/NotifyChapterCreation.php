<?php

namespace Modules\Notification\Listeners;

use Modules\Manga\Events\ChapterWasCreated;
use Modules\Notification\Contracts\Notification;
use Modules\Manga\Entities\Manga;
use Modules\User\Entities\User;

class NotifyChapterCreation
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
    public function handle(ChapterWasCreated $event)
    {
        $manga = Manga::find($event->mangaId);
        $users1 = User::join('notif_settings', 'notif_settings.user_id', '=', 'users.id')
                ->where('chapter', 1)
                ->select('users.id')
                ->get();
            
        $users2 = User::join('bookmarks', 'bookmarks.user_id', '=', 'users.id')
                ->join('notif_settings', 'notif_settings.user_id', '=', 'users.id')
                ->where('bookmarks.manga_id', $event->mangaId)
                ->where('notif_settings.chapter', 2)
                ->select('users.id')
                ->get();
        
        $users = $users1->merge($users2);
        
        $list = [];
        foreach ($users as $user) {
            if($user->isActivated()) {
                $data = [
                    'user_id' => $user->id,
                    'icon_class' => 'fa fa-2x fa-file-image-o text-blue',
                    'link' => $event->chapterUrl,
                    'title' => 'New Chapter!',
                    'message' => $manga->name.' #'.$event->chapterNumber,
                    'type' => 'CHAPTER',
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

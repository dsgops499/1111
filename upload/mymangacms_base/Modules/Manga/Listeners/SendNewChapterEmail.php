<?php

namespace Modules\Manga\Listeners;

use Illuminate\Support\Facades\Notification;
use Modules\Manga\Events\ChapterWasCreated;
use Modules\Manga\Notifications\NotifyOnNewChapter;
use Modules\User\Entities\User;
use Modules\Manga\Entities\Manga;

class SendNewChapterEmail 
{
    public function handle(ChapterWasCreated $event) {
        $users = User::join('bookmarks', 'bookmarks.user_id','=', 'users.id')
                ->where('bookmarks.manga_id', $event->mangaId)
                ->where('notify', 1)
                ->get();

        $manga = Manga::find($event->mangaId);

        Notification::send($users, new NotifyOnNewChapter($manga, $event->chapterNumber, $event->chapterUrl));
    }
}

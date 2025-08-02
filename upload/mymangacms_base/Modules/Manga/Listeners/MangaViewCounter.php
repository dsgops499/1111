<?php

namespace Modules\Manga\Listeners;

use Illuminate\Session\Store;
use Modules\Manga\Events\MangaViewed;

class MangaViewCounter 
{
    private $session;

    public function __construct(Store $session)
    {
        $this->session = $session;
    }
    
    public function handle(MangaViewed $event) {
        $manga = $event->manga;
        $mangaTab = $this->getViewedManga();

        if (!is_null($mangaTab)) {
            $mangaTab = $this->cleanExpiredViews($mangaTab);
            $this->storeManga($mangaTab);
        } 
        
        if (!$this->isMangaViewed($manga)) {
            $manga->increment('views');
            $this->storeMangaKey($manga);
        }
    }
    
    private function isMangaViewed($manga)
    {
        $viewed = $this->session->get('viewed_manga', []);

        // Check if the post id exists as a key in the array.
        return array_key_exists($manga->id, $viewed);
    }

    private function storeMangaKey($manga)
    {
        // First make a key that we can use to store the timestamp
        // in the session. Laravel allows us to use a nested key
        // so that we can set the post id key on the viewed_posts
        // array.
        $key = 'viewed_manga.' . $manga->id;

        // Then set that key on the session and set its value
        // to the current timestamp.
        $this->session->put($key, time());
    }
    
    private function getViewedManga()
    {
        // Get all the viewed posts from the session. If no
        // entry in the session exists, default to null.
        return $this->session->get('viewed_manga', null);
    }

    private function cleanExpiredViews($manga)
    {
        $time = time();

        // Let the views expire after one hour.
        $throttleTime = 3600;

        // Filter through the post array. The argument passed to the
        // function will be the value from the array, which is the
        // timestamp in our case.
        return array_filter($manga, function ($timestamp) use ($time, $throttleTime)
        {
            // If the view timestamp + the throttle time is 
            // still after the current timestamp the view  
            // has not expired yet, so we want to keep it.
            return ($timestamp + $throttleTime) > $time;
        });
    }

    private function storeManga($manga)
    {
        $this->session->put('viewed_manga', $manga);
    }
}

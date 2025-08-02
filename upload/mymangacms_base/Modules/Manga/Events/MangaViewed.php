<?php

namespace Modules\Manga\Events;

class MangaViewed
{
    public $manga;

    public function __construct($manga)
    {
        $this->manga = $manga;
    }
}

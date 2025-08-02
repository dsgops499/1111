<?php

namespace Modules\Manga\Events;

class MangaWasCreated
{
    public $manga;

    public function __construct($manga)
    {
        $this->manga = $manga;
    }
}

<?php

namespace Modules\Manga\Events;

class ChapterWasCreated
{
    public $mangaId;
    public $chapterNumber;
    public $chapterUrl;

    public function __construct($mangaId, $chapterNumber, $chapterUrl)
    {
        $this->mangaId = $mangaId;
        $this->chapterNumber = $chapterNumber;
        $this->chapterUrl= $chapterUrl;
    }
}

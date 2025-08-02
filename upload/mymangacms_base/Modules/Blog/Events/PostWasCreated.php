<?php

namespace Modules\Blog\Events;

class PostWasCreated
{
    public $post;

    public function __construct($post)
    {
        $this->post = $post;
    }
}

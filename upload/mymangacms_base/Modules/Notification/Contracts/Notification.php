<?php

namespace Modules\Notification\Contracts;

interface Notification
{
    /**
     * Push a notification on the dashboard
     * @param string $title
     * @param string $message
     * @param string $icon
     * @param string|null $link
     */
    public function push($title, $message, $icon, $type, $link = null);

    public function pushList($list);

    /**
     * Set a user id to set the notification to a specific user
     * @param int $userId
     * @return $this
     */
    public function to($userId);
}

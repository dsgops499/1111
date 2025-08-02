<?php

namespace Modules\Notification\Services;

use Modules\Notification\Contracts\NotificationRepository;
use Modules\Notification\Contracts\Notification;
use Modules\User\Contracts\Authentication;

final class NotificationService implements Notification
{
    /**
     * @var NotificationRepository
     */
    private $notification;
    /**
     * @var Authentication
     */
    private $auth;
    /**
     * @var int
     */
    private $userId;

    public function __construct(NotificationRepository $notification, Authentication $auth)
    {
        $this->notification = $notification;
        $this->auth = $auth;
    }

    /**
     * Push a notification on the dashboard
     * @param string $title
     * @param string $message
     * @param string $icon
     * @param string|null $link
     */
    public function push($title, $message, $icon, $type, $link = null)
    {
        $this->notification->create([
            'user_id' => $this->userId ?: $this->auth->id(),
            'icon_class' => $icon,
            'link' => $link,
            'title' => $title,
            'message' => $message,
            'type' => $type
        ]);
    }

    public function pushList($list)
    {
        $this->notification->insert($list);
    }
    
    /**
     * Set a user id to set the notification to
     * @param int $userId
     * @return $this
     */
    public function to($userId)
    {
        $this->userId = $userId;

        return $this;
    }
}

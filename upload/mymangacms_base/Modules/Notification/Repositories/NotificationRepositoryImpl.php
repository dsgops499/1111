<?php

namespace Modules\Notification\Repositories;

use Modules\Notification\Contracts\NotificationRepository;
use Modules\Notification\Entities\Notification;

final class NotificationRepositoryImpl implements NotificationRepository
{
    protected $model;

    public function __construct(Notification $model)
    {
        $this->model = $model;
    }
    
    /**
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function latestForUser($userId)
    {
        return $this->model->whereUserId($userId)->whereIsRead(false)->orderBy('created_at', 'desc')->take(10)->get();
    }

    /**
     * Mark the given notification id as "read"
     * @param int $notificationId
     * @return bool
     */
    public function markNotificationAsRead($notificationId, $userId)
    {
        $notification = $this->model->whereUserId($userId)->where('id', $notificationId)->first();
        $notification->is_read = true;

        return $notification->save();
    }

    public function all()
    {
        return $this->model->all();
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function create($data)
    {
        return $this->model->created($data);
    }
    
    public function insert($data)
    {
        return $this->model->insert($data);
    }
    
    /**
     * Get all the notifications for the given user id
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function allForUser($userId)
    {
        return $this->model->whereUserId($userId)->orderBy('created_at', 'desc')->get();
    }

    /**
     * Get all the read notifications for the given user id
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function allReadForUser($userId)
    {
        return $this->model->whereUserId($userId)->whereIsRead(true)->orderBy('created_at', 'desc')->get();
    }

    /**
     * Get all the unread notifications for the given user id
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function allUnreadForUser($userId)
    {
        return $this->model->whereUserId($userId)->whereIsRead(false)->orderBy('created_at', 'desc')->get();
    }

    /**
     * Delete all the notifications for the given user
     * @param int $userId
     * @return bool
     */
    public function deleteAllForUser($userId)
    {
        return $this->model->whereUserId($userId)->delete();
    }

    /**
     * Mark all the notifications for the given user as read
     * @param int $userId
     * @return bool
     */
    public function markAllAsReadForUser($userId)
    {
        return $this->model->whereUserId($userId)->update(['is_read' => true]);
    }
    
    public function saveSettings($userId, $data)
    {
        return \DB::table('notif_settings')->updateOrInsert(['user_id' => $userId], $data);
    }
}

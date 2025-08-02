<?php

namespace Modules\Notification\Listeners;

use Modules\User\Events\UserHasRegistered;
use Modules\Notification\Contracts\Notification;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class NotifyUserCreation
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
     * @param  UserHasRegistered  $event
     * @return void
     */
    public function handle(UserHasRegistered $event)
    {
        $role = Sentinel::findRoleBySlug('admin');

        $users = $role->users()->get();
        
        $list = [];
        foreach ($users as $user) {
            if($user->isActivated()) {
                $data = [
                    'user_id' => $user->id,
                    'icon_class' => 'fa fa-2x fa-user text-black',
                    'link' => route('user.show', $event->user->username),
                    'title' => 'New User!',
                    'message' => $event->user->username,
                    'type' => 'POST',
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

<?php

namespace Modules\Notification\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Modules\Notification\DataTables\NotificationDataTable;
use Modules\Notification\Entities\Notification;
use Modules\Notification\Contracts\NotificationRepository;
use Modules\User\Contracts\Authentication;

class NotificationController extends Controller
{
    /**
     * @var NotificationRepository
     */
    private $notification;
    /**
     * @var Authentication
     */
    private $auth;

    public function __construct(NotificationRepository $notification, Authentication $auth)
    {
        $this->notification = $notification;
        $this->auth = $auth;
    }

    public function index(NotificationDataTable $dataTable)
    {
        $settings = Cache::get('options');
        $theme = Cache::get('theme');
        $variation = Cache::get('variation');
        $notifSettings = \DB::table('notif_settings')->where('user_id', $this->auth->id())->first();
        
        return $dataTable->render('notification::front.notifications.index',
                compact('settings','theme','variation','notifSettings'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Notification $notification
     * @return Response
     */
    public function destroy(Notification $notification)
    {
        $this->notification->destroy($notification);

        return redirect()->route('front.notification.index')
            ->withSuccess(trans('core::core.messages.resource deleted', ['name' => 'Notification']));
    }

    public function destroyAll()
    {
        $this->notification->deleteAllForUser($this->auth->id());

        return redirect()->route('front.notification.index')
            ->withSuccess(trans('notification::messages.all notifications deleted'));
    }

    public function markAllAsRead()
    {
        $this->notification->markAllAsReadForUser($this->auth->id());

        return redirect()->route('front.notification.index')
            ->withSuccess(trans('notification::messages.all notifications marked as read'));
    }
    
    public function markAsRead(Request $request)
    {
        $updated = $this->notification->markNotificationAsRead($request->get('id'), $this->auth->id());

        return response()->json(compact('updated'));
    }
    
    public function saveSettings(Request $request)
    {
        $data = [
            'manga' => (int)$request->get('notif_manga'),
            'post' => (int)$request->get('notif_post'),
            'chapter' => (int)$request->get('notif_chapter')
        ];
        
        $this->notification->saveSettings($this->auth->id(), $data);

        return redirect()->route('front.notification.index')
            ->withSuccess(trans('messages.admin.settings.update.success'));
    }
}

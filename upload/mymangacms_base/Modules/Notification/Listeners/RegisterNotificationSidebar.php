<?php

namespace Modules\Notification\Listeners;

use Maatwebsite\Sidebar\Menu;
use Modules\Base\Http\Controllers\Sidebar\AbstractAdminSidebar;

class RegisterNotificationSidebar extends AbstractAdminSidebar
{
    /**
     * Method used to define your sidebar menu groups and items
     *
     * @param \Maatwebsite\Sidebar\Menu $menu
     *
     * @return \Maatwebsite\Sidebar\Menu
     */
    public function extendWith(Menu $menu)
    {
        return $menu;
    }
}

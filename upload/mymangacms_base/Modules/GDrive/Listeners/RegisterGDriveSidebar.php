<?php

namespace Modules\GDrive\Listeners;

use Maatwebsite\Sidebar\Group;
use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;
use Modules\Base\Http\Controllers\Sidebar\AbstractAdminSidebar;

class RegisterGDriveSidebar extends AbstractAdminSidebar
{
    /**
     * @param Menu $menu
     * @return Menu
     */
    public function extendWith(Menu $menu)
    {
        $menu->group(trans('messages.admin.layout.settings'), function (Group $group) {
            $group->item('Configuration', function (Item $item) {
                $item->weight(100);
                $item->icon('fa fa-gear');
                $item->item(trans('gdrive::messages.admin.settings.gdrive'), function (Item $item) {
                    $item->icon('fa fa-circle-o');
                    $item->weight(105);
                    $item->route('admin.settings.gdrive');
                    $item->authorize(
                        $this->auth->hasAccess('gdrive.manage_gdrive')
                    );
                });
            });
        });
        return $menu;
    }
}

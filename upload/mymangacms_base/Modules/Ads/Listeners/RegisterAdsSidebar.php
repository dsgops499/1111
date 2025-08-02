<?php

namespace Modules\Ads\Listeners;

use Maatwebsite\Sidebar\Group;
use Maatwebsite\Sidebar\Menu;
use Maatwebsite\Sidebar\Item;
use Modules\Base\Http\Controllers\Sidebar\AbstractAdminSidebar;

class RegisterAdsSidebar extends AbstractAdminSidebar {

    /**
     * Method used to define your sidebar menu groups and items
     * @param Menu $menu
     * @return Menu
     */
    public function extendWith(Menu $menu) {
        $menu->group(trans('messages.admin.layout.settings'), function(Group $group) {
            $group->weight(100);
            $group->authorize(
                    $this->auth->hasAccess('settings.edit_general')
            );
            $group->item(trans('messages.admin.settings.ads.manage-ads'), function (Item $item) {
                $item->weight(113);
                $item->icon('fa fa-money');
                $item->route('admin.ads.index');
                $item->authorize(
                        $this->auth->hasAccess('settings.edit_general')
                );
            });
        });
        return $menu;
    }

}

<?php

namespace Modules\User\Listeners;

use Maatwebsite\Sidebar\Group;
use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;
use Modules\Base\Http\Controllers\Sidebar\AbstractAdminSidebar;

class RegisterUserSidebar extends AbstractAdminSidebar {

    /**
     * @param Menu $menu
     * @return Menu
     */
    public function extendWith(Menu $menu) {
        $menu->group('Users & ACL', function(Group $group) {
            $group->weight(50);
            $group->authorize(
                    $this->auth->hasAnyAccess(['user.users.index', 'user.roles.index'])
            );
            $group->item('Users', function (Item $item) {
                $item->weight(51);
                $item->icon('fa fa-users');
                $item->route('admin.user.index');
                $item->authorize(
                        $this->auth->hasAccess('user.users.index')
                );
            });
            $group->item('Roles', function (Item $item) {
                $item->weight(52);
                $item->icon('fa fa-lock');
                $item->route('admin.role.index');
                $item->authorize(
                        $this->auth->hasAccess('user.roles.index')
                );
            });
        });
        $menu->group(trans('messages.admin.layout.settings'), function (Group $group) {
            $group->authorize(
                    $this->auth->hasAnyAccess(['settings.edit_general', 'user.profile'])
            );
            $group->item('Configuration', function (Item $item) {
                $item->weight(100);
                $item->icon('fa fa-gear');
                $item->authorize(
                        $this->auth->hasAccess('settings.edit_general')
                );
                $item->item(trans('user::messages.admin.settings.subscription'), function (Item $item) {
                    $item->icon('fa fa-circle-o');
                    $item->weight(110);
                    $item->route('admin.settings.subscription');
                    $item->authorize(
                            $this->auth->hasAccess('settings.edit_general')
                    );
                });
            });
            $group->item(trans('messages.admin.layout.user-profile'), function (Item $item) {
                $item->weight(110);
                $item->icon('fa fa-user');
                $item->route('admin.settings.profile');
                $item->authorize(
                        $this->auth->hasAccess('user.profile')
                );
            });
        });
        return $menu;
    }

}

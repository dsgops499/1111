<?php

namespace Modules\Base\Listeners;

use Maatwebsite\Sidebar\Group;
use Maatwebsite\Sidebar\Menu;
use Maatwebsite\Sidebar\Item;
use Modules\Base\Http\Controllers\Sidebar\AbstractAdminSidebar;

class RegisterDefaultSidebar extends AbstractAdminSidebar {

    /**
     * Method used to define your sidebar menu groups and items
     * @param Menu $menu
     * @return Menu
     */
    public function extendWith(Menu $menu) {
        $menu->group(trans('messages.admin.layout.dashboard'), function (Group $group) {
            $group->weight(0);
            $group->hideHeading();
            $group->item(trans('messages.admin.layout.dashboard'), function (Item $item) {
                $item->weight(100);
                $item->icon('fa fa-dashboard');
                $item->route('admin.index');
                $item->isActiveWhen(route('admin.index', null, false));
                $item->authorize(
                        $this->auth->hasAccess('dashboard.index')
                );
            });
        });
        $menu->group(trans('messages.admin.layout.settings'), function(Group $group) {
            $group->weight(100);
            $group->authorize(
                    $this->auth->hasAnyAccess(['settings.edit_general', 'settings.edit_themes', 'user.profile'])
            );
            $group->item('Configuration', function (Item $item) {
                $item->weight(100);
                $item->icon('fa fa-gear');
                $item->authorize(
                        $this->auth->hasAccess('settings.edit_general')
                );
                $item->item(trans('messages.admin.layout.general'), function (Item $item) {
                    $item->weight(101);
                    $item->icon('fa fa-circle-o');
                    $item->route('admin.settings.general');
                    $item->authorize(
                            $this->auth->hasAccess('settings.edit_general')
                    );
                });
                $item->item(trans('messages.admin.layout.seo'), function (Item $item) {
                    $item->weight(103);
                    $item->icon('fa fa-circle-o');
                    $item->route('admin.settings.seo');
                    $item->authorize(
                            $this->auth->hasAccess('settings.edit_general')
                    );
                });
                $item->item(trans('messages.admin.settings.cache'), function (Item $item) {
                    $item->weight(104);
                    $item->icon('fa fa-circle-o');
                    $item->route('admin.settings.cache');
                    $item->authorize(
                            $this->auth->hasAccess('settings.edit_general')
                    );
                });
            });
            $group->item(trans('messages.admin.layout.themes'), function (Item $item) {
                $item->weight(111);
                $item->icon('fa fa-tint');
                $item->route('admin.settings.theme');
                $item->authorize(
                        $this->auth->hasAccess('settings.edit_themes')
                );
            });
            $group->item('Menu', function (Item $item) {
                $item->weight(112);
                $item->icon('fa fa-list');
                $item->route('admin.settings.menu.index');
                $item->authorize(
                        $this->auth->hasAccess('settings.edit_general')
                );
            });
            $group->item(trans('messages.admin.settings.widgets'), function (Item $item) {
                $item->weight(114);
                $item->icon('fa fa-cubes');
                $item->route('admin.settings.widgets');
                $item->authorize(
                        $this->auth->hasAccess('settings.edit_general')
                );
            });
            $group->item(trans('messages.admin.modules'), function (Item $item) {
                $item->weight(115);
                $item->icon('fa fa-magic');
                $item->route('admin.modules.index');
                $item->authorize(
                        $this->auth->hasAccess('settings.edit_general')
                );
            });
        });
        return $menu;
    }

}

<?php

namespace Modules\Manga\Listeners;

use Maatwebsite\Sidebar\Group;
use Maatwebsite\Sidebar\Menu;
use Maatwebsite\Sidebar\Item;
use Modules\Base\Http\Controllers\Sidebar\AbstractAdminSidebar;

class RegisterMangaSidebar extends AbstractAdminSidebar {

    /**
     * Method used to define your sidebar menu groups and items
     * @param Menu $menu
     * @return Menu
     */
    public function extendWith(Menu $menu) {
        $menu->group(trans('messages.admin.layout.manage-manga'), function (Group $group) {
            $group->weight(10);
            $group->authorize(
                    $this->auth->hasAnyAccess(['manga.*', 'taxonomies.*'])
            );
            $group->item(trans('messages.admin.layout.manga-list'), function (Item $item) {
                $item->weight(10);
                $item->icon('fa fa-list-ul');
                $item->route('admin.manga.index');
                $item->authorize(
                        $this->auth->hasAccess('manga.manga.index')
                );
            });
            $group->item(trans('messages.admin.layout.hotmanga'), function (Item $item) {
                $item->weight(11);
                $item->icon('fa fa-fire');
                $item->route('admin.manga.hot');
                $item->authorize(
                        $this->auth->hasAccess('manga.manga.hot')
                );
            });
            $group->item('Taxonomies', function (Item $item) {
                $item->weight(12);
                $item->icon('fa fa-angle-left');
                $item->authorize(
                        $this->auth->hasAccess('taxonomies.*')
                );
                $item->item(trans('messages.admin.layout.categories'), function (Item $item) {
                    $item->weight(13);
                    $item->icon('fa fa-circle-o');
                    $item->route('admin.category.index');
                    $item->authorize(
                            $this->auth->hasAccess('taxonomies.manage_categories')
                    );
                });
                $item->item(trans('messages.admin.layout.comic-types'), function (Item $item) {
                    $item->weight(14);
                    $item->icon('fa fa-circle-o');
                    $item->route('admin.comictype.index');
                    $item->authorize(
                            $this->auth->hasAccess('taxonomies.manage_types')
                    );
                });
                $item->item(trans('messages.admin.manga.tags'), function (Item $item) {
                    $item->weight(14);
                    $item->icon('fa fa-circle-o');
                    $item->route('admin.tag.index');
                    $item->authorize(
                            $this->auth->hasAccess('taxonomies.manage_tags')
                    );
                });
                $item->item(trans('messages.admin.manga.author_artist'), function (Item $item) {
                    $item->weight(14);
                    $item->icon('fa fa-circle-o');
                    $item->route('admin.author.index');
                    $item->authorize(
                            $this->auth->hasAccess('taxonomies.manage_authors')
                    );
                });
            });
        });
        $menu->group(trans('messages.admin.layout.settings'), function(Group $group) {
            $group->weight(100);
            $group->authorize(
                    $this->auth->hasAccess(['settings.edit_general'])
            );
            $group->item('Configuration', function (Item $item) {
                $item->weight(100);
                $item->icon('fa fa-gear');
                $item->authorize(
                        $this->auth->hasAccess('settings.edit_general')
                );
                $item->item(trans('messages.admin.layout.options'), function (Item $item) {
                    $item->weight(102);
                    $item->icon('fa fa-circle-o');
                    $item->route('admin.manga.options');
                    $item->authorize(
                            $this->auth->hasAccess('settings.edit_general')
                    );
                });
            });
        });
        return $menu;
    }

}

<?php

namespace Modules\Blog\Listeners;

use Maatwebsite\Sidebar\Group;
use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;
use Modules\Base\Http\Controllers\Sidebar\AbstractAdminSidebar;

class RegisterBlogSidebar extends AbstractAdminSidebar {

    /**
     * @param Menu $menu
     * @return Menu
     */
    public function extendWith(Menu $menu) {
        $menu->group(trans('blog::messages.admin.blog'), function (Group $group) {
            $group->weight(20);
            $group->authorize(
                    $this->auth->hasAnyAccess(['blog.manage_posts', 'blog.manage_pages'])
            );
            $group->item(trans('blog::messages.admin.posts.posts'), function (Item $item) {
                $item->icon('fa fa-file-text-o');
                $item->weight(21);
                $item->route('admin.posts.index');
                $item->authorize(
                        $this->auth->hasAccess('blog.manage_posts')
                );
            });
            $group->item(trans('blog::messages.admin.pages.pages'), function (Item $item) {
                $item->icon('fa fa-files-o');
                $item->weight(21);
                $item->route('admin.pages.index');
                $item->authorize(
                        $this->auth->hasAccess('blog.manage_pages')
                );
            });
        });
        return $menu;
    }

}

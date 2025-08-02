<?php

namespace Modules\Base\Http\Controllers;

use Illuminate\Routing\Controller;

use Modules\Base\DataTables\MenuDataTable;
use Illuminate\Support\Facades\Lang;

use Modules\Base\Entities\Menu;
use Modules\Base\Entities\MenuNode;
use Modules\Blog\Entities\Page;

/**
 * Menu Controller Class
 * 
 * PHP version 5.4
 *
 * @category PHP
 * @package  Controller
 * @author   cyberziko <cyberziko@gmail.com>
 * @license  commercial http://getcyberworks.com/
 * @link     http://getcyberworks.com/
 */
class MenuController extends Controller {

    protected $routes;
    
    public function __construct()
    {
        $this->middleware('permission:settings.edit_general');

        $this->routes = [
            'front.index' => Lang::get('messages.front.menu.home'),
            'front.manga.list' => Lang::get('messages.front.menu.manga-list'),
            'front.manga.latestRelease' => Lang::get('messages.front.home.latest-release'),
            'front.manga.latestNews' => Lang::get('messages.front.home.news'),
            'front.manga.random' => Lang::get('messages.front.menu.random-manga'),
            'front.advSearch' => Lang::get('messages.front.home.adv-search')
        ];
    }

    public function index(MenuDataTable $dataTable) {
        return $dataTable->render('base::admin.settings.menu.index');
    }

    public function create() {
        $pages = Page::where('status',1)->pluck('title', 'id')->all();

        return view('base::admin.settings.menu.create', [
            'pages' => $pages,
            'routes' => $this->routes
        ]);
    }

    public function store()
    {
        $menu = new Menu();
        $inputs = clean(request()->all());

        $menuStructure = json_decode($inputs['menu_structure'], true);

        $menu->fill($inputs);
        $result = $menu->save();
        $menuId=$menu->id;
        
        if ($result && count($menuStructure) > 0) {
            foreach ($menuStructure as $order => $node) {
                $this->updateMenuNode($menuId, $node, $order);
            }
        }
        
        return redirect()->route('admin.settings.menu.index');
    }
    
    public function edit($id) {
        $menu = Menu::find($id);
        $menuNode = new MenuNode();
        $menuStructure = json_encode($menuNode->getMenuNodes($id));

        $pages = Page::where('status',1)->pluck('title', 'id')->all();

        return view('base::admin.settings.menu.edit', [
                    'menu' => $menu,
                    'menuStructure' => $menuStructure,
                    'pages' => $pages,
                    'routes' => $this->routes
        ]);
    }

    public function update($id) {
        $menu = Menu::find($id);
        $inputs = clean(request()->all());

        $menuStructure = json_decode($inputs['menu_structure'], true);
        $deletedNodes = json_decode($inputs['deleted_nodes'], true);

        $menu->fill($inputs);
        $result = $menu->save();
        $menuId=$menu->id;

        if(count($deletedNodes) > 0) {
            MenuNode::destroy($deletedNodes);
        }

        if ($result && count($menuStructure) > 0) {
            foreach ($menuStructure as $order => $node) {
                $this->updateMenuNode($menuId, $node, $order);
            }
        }
        
        return redirect()->route('admin.settings.menu.index');
    }
    
    public function destroy($id)
    {
        $menuNodes = (MenuNode::where('menu_id',$id)->pluck('id')->all());

        if(count($menuNodes) > 0) {
            MenuNode::destroy($menuNodes);
        }
        
        Menu::destroy([$id]);

        return redirect()->route('admin.settings.menu.index');
    }
    
    private function updateMenuNode($menuId, array $nodeData, $order, $parentId = null)
    {
        if(strlen($nodeData['id'])==0)
            $menuNode = new MenuNode();
        else
            $menuNode = MenuNode::find($nodeData['id']);

        $menuNode->menu_id = $menuId;
        $menuNode->parent_id = $parentId;
        $menuNode->related_id = array_get($nodeData, 'related_id') ?: null;
        $menuNode->type = array_get($nodeData, 'type');
        $menuNode->title = array_get($nodeData, 'title');
        $menuNode->icon_font = array_get($nodeData, 'icon_font');
        $menuNode->css_class = array_get($nodeData, 'css_class');
        $menuNode->target = array_get($nodeData, 'target');
        $menuNode->url = array_get($nodeData, 'url');
        $menuNode->sort_order = $order;
        $result = $menuNode->save();

        if(!$result) {
            return $result;
        }

        $children = array_get($nodeData, 'children', null);

        /**
         * Save the children
         */
        if($result && is_array($children)) {
            foreach ($children as $key => $child) {
                $this->updateMenuNode($menuId, $child, $key, $menuNode->id);
            }
        }
        return $result;
    }
}

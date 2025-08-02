<?php

namespace Modules\Blog\Http\Controllers;

use Illuminate\Routing\Controller;

use Modules\Blog\Entities\Page;
use Modules\Blog\DataTables\PageDataTable;
use Modules\Blog\Repositories\PageRepository;
use Modules\Blog\Http\Requests\PageRequest;
use Modules\User\Contracts\Authentication;

/**
 * Page CMS Controller Class
 * 
 * PHP version 5.4
 *
 * @category PHP
 * @package  Controller
 * @author   cyberziko <cyberziko@gmail.com>
 * @license  commercial http://getcyberworks.com/
 * @link     http://getcyberworks.com/
 */
class PageController extends Controller 
{
    private $page;
    private $auth;

    /**
     * Constructor
     * 
     */
    public function __construct(PageRepository $page, Authentication $auth) {
        $this->page = $page;
        $this->auth = $auth;
        $this->middleware('permission:blog.manage_pages');
    }
    
    public function index(PageDataTable $dataTable) 
    {
        return $dataTable->render('blog::admin.page.index');
    }

    public function create() 
    {
        return view('blog::admin.page.create');
    }

    public function store(PageRequest $request)
    {
        $this->page->create($request->all());

        return redirect()->route('admin.pages.index')
            ->withSuccess(trans('blog::messages.admin.pages.create-success'));
    }

    public function edit(Page $page)
    {
        return view()->make('blog::admin.page.edit', ['page' => $page]);
    }
    
    public function update(Page $page, PageRequest $request)
    {
        $this->page->update($page, $request->all());

        return redirect()->route('admin.pages.index')
            ->withSuccess(trans('blog::messages.admin.pages.update-success'));
    }
    
    public function destroy(Page $page)
    {
        $this->page->destroy($page);

        return redirect()->route('admin.pages.index')
            ->withSuccess(trans('blog::messages.admin.pages.delete-success'));
    }
}

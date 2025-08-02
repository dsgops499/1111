<?php

namespace Modules\Manga\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Manga\DataTables\CategoryDataTable;

use Modules\Manga\Entities\Category;

/**
 * Category Controller Class
 * 
 * PHP version 5.4
 *
 * @category PHP
 * @package  Controller
 * @author   cyberziko <cyberziko@gmail.com>
 * @license  commercial http://getcyberworks.com/
 * @link     http://getcyberworks.com/
 */
class CategoryController extends Controller
{

    protected $category;

    /**
     * Constructor
     * 
     * @param Category $category current category
     */
    public function __construct(Category $category)
    {
        $this->category = $category;
        $this->middleware('permission:taxonomies.manage_categories');
    }

    /**
     * Category page
     * 
     * @return type
     */
    public function index(CategoryDataTable $dataTable)
    {
        return $dataTable->render('manga::admin.taxo.category.index');
    }

    /**
     * Save category
     * 
     * @return type
     */
    public function store()
    {
        $input = clean(request()->all());

        if (!$this->category->fill($input)->isValid()) {
            return redirect()->back()
                ->withInput()->withErrors($this->category->errors);
        }

        $this->category->save();
        return redirect()->back()
            ->withSuccess(trans('messages.admin.category.create-success'));
    }

    /**
     * Edit page
     * 
     * @param type $id category id
     * 
     * @return type
     */
    public function edit(CategoryDataTable $dataTable,$id)
    {
        return $dataTable->render('manga::admin.taxo.category.edit',['category' => Category::find($id)]);
    }

    /**
     * Update category
     * 
     * @param type $id category id
     * 
     * @return type
     */
    public function update($id)
    {
        $input = clean(request()->all());
        $this->category = Category::find($id);

        if (!$this->category->fill($input)->isValid()) {
            return redirect()->back()
                ->withInput()->withErrors($this->category->errors);
        }

        $this->category->save();
        return redirect()->route('admin.category.index')
            ->withSuccess(trans('messages.admin.category.update-success'));
    }

    /**
     * Delete category
     * 
     * @param type $id category id
     * 
     * @return type
     */
    public function destroy($id)
    {
        $this->category = Category::find($id);

        $this->category->delete();

        return redirect()->route('admin.category.index')
            ->withSuccess(trans('messages.admin.category.delete-success'));
    }
}

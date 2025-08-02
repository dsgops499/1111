<?php

namespace Modules\Manga\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Manga\DataTables\TagDataTable;

use Modules\Manga\Entities\Tag;

/**
 * Tag Controller Class
 * 
 * PHP version 5.4
 *
 * @category PHP
 * @package  Controller
 * @author   cyberziko <cyberziko@gmail.com>
 * @license  commercial http://getcyberworks.com/
 * @link     http://getcyberworks.com/
 */
class TagController extends Controller
{
    /**
     * Constructor
     * 
     */
    public function __construct()
    {
        $this->middleware('permission:taxonomies.manage_tags');
    }
    
    /**
     * Tag page
     * 
     * @return type
     */
    public function index(TagDataTable $dataTable)
    {
        return $dataTable->render('manga::admin.taxo.tag.index');
    }

    /**
     * Save tag
     * 
     * @return type
     */
    public function store()
    {
        $input = clean(request()->all());
        $tag = new Tag();
        
        if (!$tag->fill($input)->isValid()) {
            return redirect()->back()
                ->withInput()->withErrors($tag->errors);
        }

        $tag->save();
        return redirect()->back()
            ->withSuccess(trans('messages.admin.item.create-success'));
    }

    /**
     * Edit page
     * 
     * @param type $id category id
     * 
     * @return type
     */
    public function edit(TagDataTable $dataTable, $id)
    {
        return $dataTable->render('manga::admin.taxo.tag.edit',['tag' => Tag::find($id)]);
    }

    /**
     * Update tag
     * 
     * @param type $id tag id
     * 
     * @return type
     */
    public function update($id)
    {
        $input = clean(request()->all());
        $tag = Tag::find($id);

        if (!$tag->fill($input)->isValid()) {
            return redirect()->back()
                ->withInput()->withErrors($tag->errors);
        }

        $tag->save();
        return redirect()->route('admin.tag.index')
            ->withSuccess(trans('messages.admin.item.update-success'));
    }

    /**
     * Delete item
     * 
     * @param type $id item id
     * 
     * @return type
     */
    public function destroy($id)
    {
        $tag = Tag::find($id);

        $tag->delete();

        return redirect()->route('admin.tag.index')
            ->withSuccess(trans('messages.admin.item.delete-success'));
    }
}

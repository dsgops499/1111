<?php

namespace Modules\Manga\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Manga\DataTables\TypeDataTable;

use Modules\Manga\Entities\Category;
use Modules\Manga\Entities\ComicType;

/**
 * ComicType Controller Class
 * 
 * PHP version 5.4
 *
 * @category PHP
 * @package  Controller
 * @author   cyberziko <cyberziko@gmail.com>
 * @license  commercial http://getcyberworks.com/
 * @link     http://getcyberworks.com/
 */
class ComicTypeController extends Controller
{

    protected $type;

    /**
     * Constructor
     * 
     * @param Category $type current type
     */
    public function __construct(ComicType $type)
    {
        $this->type = $type;
        $this->middleware('permission:taxonomies.manage_types');
    }

    /**
     * Type page
     * 
     * @return type
     */
    public function index(TypeDataTable $dataTable)
    {
        return $dataTable->render('manga::admin.taxo.type.index');
    }

    /**
     * Save type
     * 
     * @return type
     */
    public function store()
    {
        $input = clean(request()->all());

        if (!$this->type->fill($input)->isValid()) {
            return redirect()->back()
                ->withInput()->withErrors($this->type->errors);
        }

        $this->type->save();
        return redirect()->back()
            ->withSuccess(trans('messages.admin.comictype.create-success'));
    }

    /**
     * Edit page
     * 
     * @param type $id type id
     * 
     * @return type
     */
    public function edit(TypeDataTable $dataTable, $id)
    {
        return $dataTable->render('manga::admin.taxo.type.edit',['type' => ComicType::find($id)]);
    }

    /**
     * Update type
     * 
     * @param type $id type id
     * 
     * @return type
     */
    public function update($id)
    {
        $input = clean(request()->all());
        $this->type = ComicType::find($id);

        if (!$this->type->fill($input)->isValid()) {
            return redirect()->back()
                ->withInput()->withErrors($this->type->errors);
        }

        $this->type->save();
        return redirect()->route('admin.taxo.comictype.index')
            ->withSuccess(trans('messages.admin.comictype.update-success'));
    }

    /**
     * Delete type
     * 
     * @param type $id type id
     * 
     * @return type
     */
    public function destroy($id)
    {
        $this->type = ComicType::find($id);

        $this->type->delete();

        return redirect()->route('admin.taxo.comictype.index')
            ->withSuccess(trans('messages.admin.comictype.delete-success'));
    }
}

<?php

namespace Modules\Manga\DataTables;

use Illuminate\Support\Facades\Lang;
use Yajra\Datatables\Services\DataTable;

use Modules\Manga\Entities\Tag;

class TagDataTable extends DataTable
{
    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', function ($item) {
                return '<a href="'.route("admin.tag.edit", $item->id).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</a>'
                        . '<form action="'.route("admin.tag.destroy", $item->id).'" method="POST" class="form-btn">
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="hidden" name="_token" value="'.csrf_token().'">
                            <input class="btn btn-xs btn-danger" type="submit" onclick="if(!confirm(\''. Lang::get('messages.admin.item.confirm-delete') .'\')){return false;}" value="Delete"/>
                         </form>';
            })
            ->editColumn('nitems', function ($item) {
                return $item->manga()->count();
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Get the query object to be processed by dataTables.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    public function query()
    {
        $query = Tag::query();

        return $this->applyScopes($query);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->columns($this->getColumns())
                    ->ajax('')
                    ->addAction(['width' => '120px'])
                    ->parameters($this->getBuilderParameters());
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'name',
            'slug',
            ['data' => 'nitems', 'title' => 'N° items'],
            'created_at',
        ];
    }
    
}

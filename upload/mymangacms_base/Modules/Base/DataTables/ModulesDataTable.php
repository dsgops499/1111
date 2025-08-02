<?php

namespace Modules\Base\DataTables;

use Illuminate\Support\Collection;
use Yajra\Datatables\Services\DataTable;
use Nwidart\Modules\Repository;

class ModulesDataTable extends DataTable
{
    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->of($this->query())
            ->editColumn('name', function ($item) {
                return '<a href="'.route("admin.modules.show", $item->lowerName).'">'.$item->name.'</a>';
            })
            ->editColumn('status', function ($item) {
                return ($item->status == 1?"<span class='label label-success'>enabled</span>":"<span class='label label-danger'>disabled</span>");
            })
            ->rawColumns(['name','status'])
            ->make(true);
    }

    /**
     * Get the query object to be processed by dataTables.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    public function query()
    {
        $repo = app()->make(Repository::class);
        $modules = $repo->all();
        foreach ($modules as $module) {
            $obj = new \stdClass;
            $obj->name = $module->name;
            $obj->lowerName = $module->getLowerName();
            $obj->version = $module->version;
            $obj->status = $module->enabled();
            $data[] = $obj;
        }
        
        return new Collection($data);
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
            'version',
            'status',
        ];
    }
}

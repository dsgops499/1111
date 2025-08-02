<?php

namespace Modules\User\DataTables;

use Illuminate\Support\Facades\Lang;
use Yajra\Datatables\Services\DataTable;

use Cartalyst\Sentinel\Roles\EloquentRole as Role;

class RoleDataTable extends DataTable
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
                return '<a href="'.route("admin.role.edit", $item->id).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</a>'
                        . '<form action="'.route("admin.role.destroy", $item->id).'" method="POST" class="form-btn">
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="hidden" name="_token" value="'.csrf_token().'">
                            <input class="btn btn-xs btn-danger" type="submit" onclick="if(!confirm(\''. Lang::get('messages.admin.roles.confirm-delete') .'\')){return false;}" value="Delete"/>
                         </form>';
            })
            ->editColumn('permissions', function ($item) {
                return count(array_keys($item->permissions, true, true));
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
        $query = Role::query();

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
                    ->addAction(['width' => '100px'])
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
            ['data'=>'name', 'name'=>'name', 'title'=>'Role'],
            ['data'=>'permissions', 'title'=>'N° Permissions', 'orderable' => false, 'searchable' => false],
            ['data'=>'created_at', 'name'=>'created_at', 'title'=>'Created at', 'orderable' => true, 'searchable' => false],
        ];
    }
    
    protected function getBuilderParameters()
    {
        return [
            'order'   => [[2, 'asc']],
            'buttons' => [],
        ];
    }    
}

<?php

namespace Modules\User\DataTables;

use Illuminate\Support\Facades\Lang;
use Yajra\Datatables\Services\DataTable;
use Modules\Base\Supports\HelperController;

use Modules\User\Entities\User;

class UserDataTable extends DataTable
{
    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        $users = $this->query();
        return $this->datatables
            ->eloquent($users)
            ->filter(function ($users) {
                $order = request()->get('order');
                if ($order[0]['column']=='3') {
                    $users->select('users.*')->leftJoin('activations','activations.user_id','=','users.id')->orderBy('activations.user_id', $order[0]['dir']);
                }
            }, true)
            ->addColumn('action', function ($item) {
                return '<a href="'.route("admin.user.edit", $item->id).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</a>'
                        . '<form action="'.route("admin.user.destroy", $item->id).'" method="POST" class="form-btn">
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="hidden" name="_token" value="'.csrf_token().'">
                            <input class="btn btn-xs btn-danger" type="submit" onclick="if(!confirm(\''. Lang::get('messages.admin.users.confirm-delete') .'\')){return false;}" value="Delete"/>
                         </form>';
            })
            ->editColumn('roles', function ($item) {
                return HelperController::listAsString($item->roles, ', ');
            })
            ->editColumn('status', function ($item) {
                return ($item->isActivated()?"<span class='label label-success'>Activated</span>":'<span class="label label-danger">Disabled</span>');
            })
            ->rawColumns(['status','action'])
            ->make(true);
    }

    /**
     * Get the query object to be processed by dataTables.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    public function query()
    {
        $query = User::query();

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
            ['data'=>'username', 'name'=>'username', 'title'=>'Username'],
            ['data'=>'email', 'name'=>'email', 'title'=>'Email'],
            ['data'=>'roles', 'title'=>'Roles', 'orderable' => false, 'searchable' => false],
            ['data'=>'status', 'name'=>'status', 'title'=>'Status', 'orderable' => true, 'searchable' => false],
            ['data'=>'created_at', 'name'=>'created_at', 'title'=>'Created at', 'orderable' => true, 'searchable' => false],
        ];
    }
    
    protected function getBuilderParameters()
    {
        return [
            'order'   => [[4, 'asc']],
            'buttons' => [],
        ];
    } 
}

<?php

namespace Modules\Notification\DataTables;

use Illuminate\Support\Facades\Lang;
use Yajra\Datatables\Services\DataTable;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Modules\Notification\Entities\Notification;

class NotificationDataTable extends DataTable
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
                return  '<form action="'.route("front.notification.destroy", $item->id).'" method="POST" class="form-btn">
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="hidden" name="_token" value="'.csrf_token().'">
                            <input class="btn btn-xs btn-danger" type="submit" onclick="if(!confirm(\''. Lang::get('messages.admin.item.confirm-delete') .'\')){return false;}" value="Delete"/>
                         </form>';
            })
            ->editColumn('time', function ($item) {
                return $item->time_ago;
            })
            ->editColumn('link', function ($item) {
                return "<a href='".$item->link."'>". $item->message ."</a>";
            })
            ->editColumn('read', function ($item) {
                return $item->is_read ? 'Read' : 'Unread';
            })
            ->rawColumns(['link','action'])
            ->make(true);
    }

    /**
     * Get the query object to be processed by dataTables.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    public function query()
    {
        $query = Notification::whereUserId(Sentinel::check()->id);

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
                    ->addAction(['width' => '60px'])
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
            ['data'=>'time', 'title'=>'Time'],
            ['data'=>'title', 'name'=>'title', 'title'=>'Type'],
            ['data'=>'link', 'name'=>'message', 'title'=>'Link'],
            ['data'=>'read', 'name'=>'is_read', 'title'=>'is read?', 'orderable' => true, 'searchable' => false],
        ];
    }
    
}

<?php

namespace Modules\Manga\DataTables;

use Yajra\Datatables\Services\DataTable;
use Modules\Base\Supports\HelperController;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

use Modules\Manga\Entities\Manga;

class MangaDataTable extends DataTable
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
                if((Sentinel::check()->id==$item->user->id && Sentinel::hasAccess('manage_my_manga')) || Sentinel::hasAccess('manga.manga.edit')) {
                    return '<a href="'.route("admin.manga.edit", $item->id).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</a>';
                } 
            })
            ->rawColumns(['name','status_id','cover','hot','action'])
            ->editColumn('hot', function ($item) {
                return ($item->hot == 1?"<i class='fa fa-star fa-2x'></i>":'-');
            })
            ->editColumn('cover', function ($item) {
                if((Sentinel::check()->id==$item->user->id && Sentinel::hasAccess('manage_my_manga')) || Sentinel::hasAnyAccess(['manga.manga.index','manga.manga.edit'])) {
                    return ($item->cover == 1?"<a href='". route('admin.manga.show', $item->id)."'><img width='100' height='100' src='". HelperController::coverUrl("$item->slug/cover/cover_thumb.jpg") ."' alt='". $item->name ."'/></a>":
                        "<a href='". route('admin.manga.show', $item->id)."'><img width='100' height='100' src='". asset("images/no-image.png") ."' alt='". $item->name ."' /></a>");
                } else {
                    return ($item->cover == 1?"<img width='100' height='100' src='". HelperController::coverUrl("$item->slug/cover/cover_thumb.jpg") ."' alt='". $item->name ."'/>":
                        "<img width='100' height='100' src='". asset("images/no-image.png") ."' alt='". $item->name ."' />");
                }
            })
            ->editColumn('status_id', function ($item) {
                if($item->status_id == 1) {
                    return "<span class='label label-success'>Ongoing</span>";
                } else if($item->status_id == 2) {
                    return "<span class='label label-danger'>Complete</span>";
                } else {
                    return "-";
                }
            })
            ->editColumn('name', function ($item) {
                if((Sentinel::check()->id==$item->user->id && Sentinel::hasAccess('manage_my_manga')) || Sentinel::hasAnyAccess(['manga.manga.index','manga.manga.edit'])) {
                    return "<a href='". route('admin.manga.show', $item->id)."'>".$item->name."</a>";
                } else {
                    return $item->name;
                }
            })
            ->make(true);
    }

    /**
     * Get the query object to be processed by dataTables.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    public function query()
    {
//        $mes = $this->request()->get('mes');
//        $anio = $this->request()->get('anio');
    
        if(Sentinel::hasAccess('manga.manga.index')) {
            $query = Manga::query();
        } else if(Sentinel::hasAccess('manage_my_manga')) {
            $query = Manga::where('user_id', Sentinel::check()->id);
        }

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
                    ->addAction(['width' => '80px'])
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
            ['data'=>'cover', 'title'=>'', 'orderable' => false, 'searchable' => false],
            ['data'=>'name', 'name' => 'name', 'title'=>'Title', 'orderable' => true, 'searchable' => true],
            ['data'=>'status_id', 'name' => 'status_id', 'title'=>'Status', 'orderable' => true, 'searchable' => false],
            ['data'=>'hot', 'name' => 'hot', 'title'=>'Featured', 'orderable' => true, 'searchable' => false],
            ['data'=>'views', 'name'=>'views', 'title'=>'Views', 'orderable' => true, 'searchable' => false],
            ['data'=>'created_at', 'name'=>'created_at', 'title'=>'Created at', 'orderable' => true, 'searchable' => false],
        ];
    }
    
    protected function getBuilderParameters()
    {
        return [
            'order'   => [[5, 'desc']],
            'buttons' => [],
        ];
    }
}

@extends('base::layouts.default')

@section('head')
<link rel="stylesheet" href="{{asset('vendor/datatables/jquery.dataTables.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/datatables/buttons.dataTables.min.css')}}">

<script src="{{asset('vendor/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('vendor/datatables/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('vendor/datatables/buttons.server-side.js')}}"></script>
{!! Jraty::js() !!}
@endsection

@section('breadcrumbs')
{!!Breadcrumbs::render()!!}
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-list fa-fw"></i> {{ Lang::get('messages.admin.manga.list') }}
                </h3>
                <div class="box-tools">
                    @if(Sentinel::hasAnyAccess(['manga.manga.create','manage_my_manga']))
                    <a class="btn btn-primary btn-sm"
                       href="{{route('admin.manga.create')}}">
                        <i class="fa fa-plus"></i> {{ Lang::get('messages.admin.manga.create') }}
                    </a>
                    @endif
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                {!! $dataTable->table() !!}
            </div>
            <!-- /.box-body -->
        </div>
    </div>
</div>

{!! $dataTable->scripts() !!}
<script>
    /*$('#dataTableBuilder') .on('preXhr.dt', function (e, settings, data) { data.mes = '1'; data.anio = '11'; });
    $('#search-form').on('submit', function(e) {
        oTable.draw();
        e.preventDefault();
    });*/
</script>
@endsection

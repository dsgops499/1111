@extends('base::layouts.default')

@section('head')
<link rel="stylesheet" href="{{asset('vendor/datatables/jquery.dataTables.min.css')}}">
<link rel="stylesheet" href="{{asset('vendor/datatables/buttons.dataTables.min.css')}}">

<script src="{{asset('vendor/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('vendor/datatables/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('vendor/datatables/buttons.server-side.js')}}"></script>
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
                    <i class="icon-layers font-dark"></i>
                    {{ Lang::get('messages.admin.settings.menu') }}
                </h3>
                <div class="box-tools">
                    <a class="btn btn-primary btn-sm"
                       href="{{route('admin.settings.menu.create')}}">
                        <i class="fa fa-plus"></i> {{ Lang::get('messages.admin.settings.create') }}
                    </a>
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
@endsection

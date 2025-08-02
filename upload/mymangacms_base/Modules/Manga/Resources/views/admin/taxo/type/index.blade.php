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
    <div class="col-md-4">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-edit"></i> {{ Lang::get('messages.admin.comictype.create-type') }}
                </h3>
            </div>
            {{ Form::open(array('route' => 'admin.comictype.store', 'role' => 'form')) }}
            <div class="box-body">
                <div class="form-group">
                    {{Form::label('label', Lang::get('messages.admin.comictype.label'))}}
                    {{Form::text('label','', array('class' => 'form-control'))}}
                    {!! $errors->first('label', '<label class="error" for="name">:message</label>') !!}
                </div>
            </div>
            <div class="box-footer">
                <div class="pull-right">
                    {{ link_to_route('admin.manga.index', Lang::get('messages.admin.category.back'), [], array('class' => 'btn btn-default btn-xs')) }}
                    {{Form::submit(Lang::get('messages.admin.comictype.create-type'), array('class' => 'btn btn-primary btn-xs'))}}
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
    <div class="col-md-8">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-folder-open"></i> {{ Lang::get('messages.admin.comictype.types') }}
                </h3>
            </div>
            <div class="box-body">
                {!! $dataTable->table() !!}
            </div>
        </div>
    </div>
</div>

{!! $dataTable->scripts() !!}
@endsection

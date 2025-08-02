@extends('base::layouts.default')

@section('head')
<style>
    .module-type {text-align: center}
    .module-type span {display: block}
    .module-type i {font-size: 124px}
    form {display: inline}
</style>
@endsection

@section('breadcrumbs')
{!!Breadcrumbs::render('admin.modules.show', $module->getName())!!}
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header">
                @if($module->mandatory!=1)
                <div class="box-tools">
                    {{ Form::open(array('route' => array('admin.modules.update', $module->name), 'method' => 'PUT')) }}
                    <button class="btn btn-box-tool" data-toggle="tooltip" type="submit"
                            title="{{$module->enabled() ? 'disable' : 'enable'}}">
                        <i class="fa fa-toggle-{{ $module->enabled() ? 'on' : 'off' }}"></i>
                        {{ trans($module->enabled() ? 'disable' : 'enable') }}
                    </button>
                    <input type="hidden" name="action" value="{{$module->enabled()?'0':'1'}}"/>
                    {{ Form::close() }}
                </div>
                @endif
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12 module-details">
                        <div class="module-type pull-left">
                            <i class="fa fa-cube"></i>
                        </div>
                        <h2>{{ ucfirst($module->getName()) }} <small>{{ $module->version }}</small></h2>
                        <p>{{ $module->getDescription() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop


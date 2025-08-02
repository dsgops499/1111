@extends('base::layouts.default')

@section('breadcrumbs')
{!!Breadcrumbs::render('admin.settings.cache')!!}
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-tint"></i> {{ Lang::get('messages.admin.settings.cache') }}
                </h3>
            </div>
            <div class="box-body">
                <ul class="nav nav-pills nav-stacked cache">
                    <li>
                        {{ Form::open(array('route' => 'admin.settings.cache.clear', 'role' => 'form')) }}
                        <div class="form-group">
                            <span class="cache-label">{{Lang::get('messages.admin.settings.cache.clear_all_label')}}</span>
                            <span class="pull-right">{{ Form::submit(Lang::get('messages.admin.settings.cache.clear_all'), ['class' => 'btn btn-danger submit']) }}</span>
                        </div>
                        {{ Form::close() }}
                    </li>
                    <li>
                        {{ Form::open(array('route' => 'admin.settings.cache.clear-views', 'role' => 'form')) }}
                        <div class="form-group">
                            <span class="cache-label">{{Lang::get('messages.admin.settings.cache.clear_views_label')}}</span>
                            <span class="pull-right">{{ Form::submit(Lang::get('messages.admin.settings.cache.clear_views'), ['class' => 'btn btn-danger submit']) }}</span>
                        </div>
                        {{ Form::close() }}
                    </li>
                    <li>
                        {{ Form::open(array('route' => 'admin.settings.cache.cache-loader', 'role' => 'form')) }}
                        <div class="form-group">
                            <span class="cache-label">{{Lang::get('messages.admin.settings.cache.optim_autoloaded_label')}}</span>
                            <span class="pull-right">{{ Form::submit(Lang::get('messages.admin.settings.cache.optim_autoloaded'), ['class' => 'btn btn-info submit']) }}</span>
                        </div>
                        {{ Form::close() }}
                    </li>
                    <li>
                        {{ Form::open(array('route' => 'admin.settings.cache.clear-loader-class', 'role' => 'form')) }}
                        <div class="form-group">
                            <span class="cache-label">{{Lang::get('messages.admin.settings.cache.back_autoloaded_label')}}</span>
                            <span class="pull-right">{{ Form::submit(Lang::get('messages.admin.settings.cache.back_autoloaded'), ['class' => 'btn btn-danger submit']) }}</span>
                        </div>
                        {{ Form::close() }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-tint"></i> {{ Lang::get('messages.admin.settings.cache.reader') }}
                </h3>
            </div>
            {{ Form::open(array('route' => 'admin.settings.cache.save', 'role' => 'form')) }}
            <div class="box-body">
                <div class="form-group">
                    {{Form::number('site.cache[reader]', isset($cache->reader)?$cache->reader:60, ['min' => '0', 'aria-describedby' => 'helpReader', 'class' => 'form-control'])}}
                    <span id="helpReader" class="help-block">{{Lang::get('messages.admin.settings.cache.reader.help')}}</span>
                </div>
            </div>
            <div class="box-footer">
                {{ Form::submit(Lang::get('messages.admin.settings.save'), ['class' => 'btn btn-primary submit pull-right']) }}
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection
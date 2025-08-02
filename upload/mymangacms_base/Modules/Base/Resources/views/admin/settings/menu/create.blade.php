@extends('base::layouts.default')

@section('head')
<link rel="stylesheet" href="{{asset('vendor/jquery-nestable/jquery.nestable.css')}}">
<link rel="stylesheet" href="{{asset('css/admin/menu-nestable.css')}}">
@endsection

@section('js')
@include('base::admin._components.nestable-script-renderer')
<script src="{{asset('vendor/underscore/underscore-min.js')}}"></script>
<script src="{{asset('vendor/jquery-nestable/jquery.nestable.min.js')}}"></script>
<script src="{{asset('js/admin/Helpers.js')}}"></script>
<script src="{{asset('js/admin/edit-menu.js')}}"></script>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        @include('base::admin._partials.pages')
        @include('base::admin._partials.routes')
        @include('base::admin._partials.custom-link')
    </div>
    <div class="col-md-8">
        {{ Form::open(['route' => 'admin.settings.menu.store', 'class' => 'js-validate-form', 'novalidate' => 'novalidate']) }}
        <textarea name="menu_structure"
                  id="menu_structure"
                  class="hidden"
                  style="display: none;">{{ old('menu_structure', '[]') }}</textarea>
        <textarea name="deleted_nodes"
                  id="deleted_nodes"
                  class="hidden"
                  style="display: none;">[]</textarea>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="icon-layers font-dark"></i>
                    {{ Lang::get('messages.admin.settings.menu.menu_info') }}
                </h3>
            </div>
            <div class="box-body">
                <div class="form-group">
                    <label class="control-label">
                        <b>{{ Lang::get('messages.admin.settings.title') }}</b>
                    </label>
                    <input required type="text" name="title"
                           class="form-control"
                           value="{{ old('title') }}"
                           autocomplete="off">
                </div>
                <div class="form-group">
                    <label class="control-label">
                        <b>{{ Lang::get('messages.admin.settings.slug') }}</b>
                    </label>
                    <input required type="text" name="slug"
                           class="form-control"
                           value="{{ old('slug') }}"
                           autocomplete="off">
                </div>
                <div class="form-group">
                    <label class="control-label">
                        <b>{{ Lang::get('messages.admin.settings.status') }}</b>
                    </label>
                    {{ Form::select('status', [
                    '1' => 'activated',
                    '0' => 'disabled',
                    ], old('status'), ['class' => 'form-control']) }}
                </div>
                <div class="form-group">
                    <label class="control-label">
                        <b>{{ Lang::get('messages.admin.settings.menu.structure') }}</b>
                    </label>
                    <div class="dd nestable-menu"></div>
                </div>
            </div>
            <div class="box-footer text-right">
                <button class="btn btn-primary"
                        type="submit">
                    <i class="fa fa-check"></i> {{ Lang::get('messages.admin.settings.save') }}
                </button>
                {{ link_to_route('admin.settings.menu.index', Lang::get('messages.admin.settings.cancel'), [], array('class' => 'btn btn-default')) }}
            </div>
        </div>
        {{ Form::close() }}
    </div>
</div>
@endsection

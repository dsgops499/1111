@extends('base::layouts.default')

@section('breadcrumbs')
{!!Breadcrumbs::render()!!}
@endsection

@section('content')
<div class="row">
    <div class="col-md-12" >
        <div class="box box-primary">
            <div class="box-header with-border">
                <i class="fa fa-plus-square-o"></i> {{ Lang::get('messages.admin.users.roles.create') }}
                <div class="box-tools">
                    {{ link_to_route('admin.role.index', Lang::get('messages.admin.manga.back'), [], array('class' => 'btn btn-default btn-xs', 'role' => 'button')) }}
                </div>
            </div>
            {{ Form::open(array('route' => 'admin.role.store')) }}
            <div class="box-body">
                <div class="form-group">
                    {{Form::label('name', Lang::get('messages.admin.users.roles.role-name'))}}
                    {{Form::text('name', '', array('class' => 'form-control'))}}
                    {!! $errors->first('name', '<label class="error" for="name">:message</label>') !!}
                </div>

                <fieldset>
                    <legend>Permissions</legend>
                    @include('user::admin.partials.permissions-create')
                </fieldset>
            </div>
            <div class="box-footer">
                {{ Form::submit(Lang::get('messages.admin.settings.save'), array('class' => 'btn btn-primary pull-right')) }}
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection
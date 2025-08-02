@extends('base::layouts.default')

@section('breadcrumbs')
{!!Breadcrumbs::render()!!}
@endsection

@section('content')
<div class="row">
    <div class="col-md-12" >
        <div class="box box-primary">
            <div class="box-header with-border">
                <i class="fa fa-pencil-square-o"></i> {{ Lang::get('messages.admin.users.roles.edit') }}
                <div class="box-tools">
                    {{ link_to_route('admin.role.index', Lang::get('messages.admin.manga.back'), [], array('class' => 'btn btn-default btn-xs', 'role' => 'button')) }}
                </div>
            </div>
            {{ Form::open(array('route' => array('admin.role.update', $role->id), 'method' => 'PUT')) }}
            <div class="box-body">
                <div class="form-group">
                    {{Form::label('name', Lang::get('messages.admin.users.roles.role-name'))}}
                    {{Form::text('name', $role->name, array('class' => 'form-control'))}}
                    {!! $errors->first('name', '<label class="error" for="name">:message</label>') !!}
                </div>

                <fieldset>
                    <legend>{{ Lang::get('messages.admin.users.roles.select-perms') }}</legend>
                    @include('user::admin.partials.permissions', ['model' => $role])
                </fieldset>
            </div>
            <div class="box-footer">
                {{ Form::submit(Lang::get('messages.admin.settings.update'), array('class' => 'btn btn-primary pull-right')) }}
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection
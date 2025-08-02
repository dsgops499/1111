@extends('base::layouts.default')

@section('breadcrumbs')
{!!Breadcrumbs::render()!!}
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-user fa-fw"></i> {{ Lang::get('messages.admin.settings.profile.header') }}
                </h3>
            </div>
            {{ Form::open(array('route' => 'admin.settings.profile.save', 'role' => 'form')) }}
            <div class="box-body">
                <div class="form-group">
                    {{ Form::label('name', Lang::get('messages.admin.settings.profile.name')) }}
                    {{ Form::text('name', $user->name, array('class' => 'form-control')) }}
                </div>
                <div class="form-group">
                    {{ Form::label('username', Lang::get('messages.admin.settings.profile.username')) }}
                    {{ Form::text('username', $user->username, array('class' => 'form-control')) }}
                    {!! $errors->first('username', '<label class="error" for="username">:message</label>') !!}
                </div>
                <div class="form-group">
                    {{ Form::label('password', Lang::get('messages.admin.settings.profile.pwd')) }}
                    {{ Form::password('password', array('class' => 'form-control')) }}
                    {!! $errors->first('password', '<label class="error" for="password">:message</label>') !!}
                </div>
                <div class="form-group">
                    {{ Form::label('password_confirmation', 'Password Confirmation') }}
                    {{ Form::password('password_confirmation', array('class' => 'form-control')) }}
                    {!! $errors->first('password_confirmation', '<label class="error" for="password_confirmation">:message</label>') !!}
                </div>
                <div class="form-group">
                    {{ Form::label('email', Lang::get('messages.admin.settings.profile.email')) }}
                    {{ Form::text('email', $user->email, ['class' => 'form-control']) }}
                    {!! $errors->first('email', '<label class="error" for="email">:message</label>') !!}
                </div>
            </div>
            <div class="box-footer">
                {{ Form::submit(Lang::get('messages.admin.settings.update'), ['class' => 'btn btn-primary pull-right']) }}
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection
@extends('user::layouts.auth')

@section('title')
Complete reset password
@stop

@section('content')
<div class="login-box">
    <div class="login-logo">
        <a href="{{route('front.index')}}"><b>{{ $sitename }}</b></a>
    </div>
    @include('base::admin._partials.notifications')

    <div class="login-box-body">
        <p class="login-box-msg">{{ trans('user::messages.front.auth.reset_password') }}</p>

        {{ Form::open() }}
        <div class="form-group has-feedback {{ $errors->has('password') ? ' has-error' : '' }}">
            <input type="password" class="form-control" autofocus
                   name="password" placeholder="{{ trans('user::messages.front.auth.password') }}">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            {!! $errors->first('password', '<span class="help-block">:message</span>') !!}
        </div>
        <div class="form-group has-feedback {{ $errors->has('password_confirmation') ? ' has-error has-feedback' : '' }}">
            <input type="password" name="password_confirmation" class="form-control" placeholder="{{ trans('user::messages.front.auth.password_confirmation') }}">
            <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
            {!! $errors->first('password_confirmation', '<span class="help-block">:message</span>') !!}
        </div>

        <div class="row">
            <div class="col-xs-12">
                <button type="submit" class="btn btn-primary btn-block btn-flat pull-right">
                    {{ trans('user::messages.front.auth.reset_password') }}
                </button>
            </div>
        </div>
        {{ Form::close() }}
    </div>
</div>
@stop

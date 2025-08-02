@extends('user::layouts.auth')

@section('title')
Register
@stop

@section('content')
<div class="register-box">
    <div class="register-logo">
        <a href="{{route('front.index')}}"><b>{{ $sitename }}</b></a>
    </div>
    @include('base::admin._partials.notifications')

    <div class="register-box-body">
        <p class="login-box-msg">{{ trans('user::messages.front.auth.register') }}</p>

        {{ Form::open(['route' => 'register.post']) }}
        <div class="form-group has-feedback {{ $errors->has('username') ? ' has-error has-feedback' : '' }}">
            <input type="text" name="username" class="form-control" autofocus
                   placeholder="{{ trans('user::messages.front.auth.username') }}" value="{{ old('username') }}">
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            {!! $errors->first('username', '<span class="help-block">:message</span>') !!}
        </div>
        <div class="form-group has-feedback {{ $errors->has('email') ? ' has-error has-feedback' : '' }}">
            <input type="email" name="email" class="form-control" autofocus
                   placeholder="{{ trans('user::messages.front.auth.email') }}" value="{{ old('email') }}">
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
        </div>
        <div class="form-group has-feedback {{ $errors->has('password') ? ' has-error has-feedback' : '' }}">
            <input type="password" name="password" class="form-control" placeholder="{{ trans('user::messages.front.auth.password') }}">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            {!! $errors->first('password', '<span class="help-block">:message</span>') !!}
        </div>
        <div class="form-group has-feedback {{ $errors->has('password_confirmation') ? ' has-error has-feedback' : '' }}">
            <input type="password" name="password_confirmation" class="form-control" placeholder="{{ trans('user::messages.front.auth.password_confirmation') }}">
            <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
            {!! $errors->first('password_confirmation', '<span class="help-block">:message</span>') !!}
        </div>
        
        @if(isset($captcha->form_register) && $captcha->form_register === '1')
        <div class="form-group has-feedback {{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">
            {!! NoCaptcha::renderJs() !!}
            {!! NoCaptcha::display() !!}
            {!! $errors->first('g-recaptcha-response', '<span class="help-block">:message</span>') !!}
        </div>
        @endif
        
        <div class="row">
            <div class="col-xs-12">
                <button type="submit" class="btn btn-primary btn-block btn-flat">{{ trans('user::messages.front.auth.register_btn') }}</button>
            </div>
        </div>
        {{ Form::close() }}

        <a href="{{ route('login') }}" class="text-center">{{ trans('user::messages.front.auth.already_member') }}</a>
    </div>
    <!-- /.form-box -->
</div>
<!-- /.register-box -->
@stop

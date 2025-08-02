@extends('user::layouts.auth')

@section('title')
Login In
@stop

@section('content')
<div class="login-box">
    <div class="login-logo">
        <a href="{{route('front.index')}}"><b>{{ $sitename }}</b></a>
    </div>
    @include('base::admin._partials.notifications')

    <div class="login-box-body">
        <p class="login-box-msg">{{trans('user::messages.front.auth.sign_in')}}</p>

        {{ Form::open(['route' => 'login.post']) }}
        <div class="form-group has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
            <input type="text" class="form-control" autofocus
                   name="email" placeholder="{{ trans('user::messages.front.auth.email_or_username') }}" value="{{ old('email')}}">
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
        </div>
        <div class="form-group has-feedback {{ $errors->has('password') ? ' has-error' : '' }}">
            <input type="password" class="form-control"
                   name="password" placeholder="{{ trans('user::messages.front.auth.password') }}" value="{{ old('password')}}">
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            {!! $errors->first('password', '<span class="help-block">:message</span>') !!}
        </div>
        
        @if(isset($captcha->form_login) && $captcha->form_login === '1')
        <div class="form-group has-feedback {{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">
            {!! NoCaptcha::renderJs() !!}
            {!! NoCaptcha::display() !!}
            {!! $errors->first('g-recaptcha-response', '<span class="help-block">:message</span>') !!}
        </div>
        @endif
        
        <div class="row">
            <div class="col-xs-8">
                <div class="checkbox icheck">
                    <label>
                        <input type="checkbox" name="remember_me"> {{ trans('user::messages.front.auth.remember_me') }}
                    </label>
                </div>
            </div>
            <!-- /.col -->
            <div class="col-xs-4">
                <button type="submit" class="btn btn-primary btn-block btn-flat">{{ trans('user::messages.front.auth.sign_in_btn') }}</button>
            </div>
            <!-- /.col -->
        </div>
        {{Form::close()}}

        <a href="{{ route('reset')}}">{{ trans('user::messages.front.auth.forgot_password') }}</a><br>
        @if(config('subscribe'))
            <a href="{{ route('register')}}" class="text-center">{{ trans('user::messages.front.auth.register')}}</a>
        @endif
    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->
@stop

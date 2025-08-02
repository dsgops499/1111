@extends('user::layouts.auth')

@section('title')
Reset Password
@stop

@section('content')
<div class="login-box">
    <div class="login-logo">
        <a href="{{route('front.index')}}"><b>{{ $sitename }}</b></a>
    </div>
    @include('base::admin._partials.notifications')

    <div class="login-box-body">
        <p class="login-box-msg">{{ trans('user::messages.front.auth.reset_password_body') }}</p>

        {{ Form::open(['route' => 'reset.post']) }}
        <div class="form-group has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
            <input type="email" class="form-control" autofocus
                   name="email" placeholder="{{ trans('user::messages.front.auth.email') }}" value="{{ old('email')}}">
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
        </div>
        
        @if(isset($captcha->form_reset) && $captcha->form_reset === '1')
        <div class="form-group has-feedback {{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">
            {!! NoCaptcha::renderJs() !!}
            {!! NoCaptcha::display() !!}
            {!! $errors->first('g-recaptcha-response', '<span class="help-block">:message</span>') !!}
        </div>
        @endif

        <div class="row">
            <div class="col-xs-12">
                <button type="submit" class="btn btn-primary btn-block btn-flat pull-right">
                    {{ trans('user::messages.front.auth.reset_password') }}
                </button>
            </div>
        </div>
        {{ Form::close() }}

        <a href="{{ route('login') }}" class="text-center">{{ trans('user::messages.front.auth.i_remembered_password') }}</a>
    </div>
</div>
@stop

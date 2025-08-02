@extends('front.layouts.colorful')

@section('title')
{{$settings['seo.title']}} | {{ Lang::get('messages.front.home.contact-us') }}
@stop

@section('description')
{{ Lang::get('messages.front.home.contact-us') }}
@stop

@section('keywords')
{{$settings['seo.keywords']}}
@stop

@include('front.themes.'.$theme.'.blocs.menu')

@section('allpage')
<h2 class="widget-title">{{ Lang::get('messages.front.home.contact-us') }}</h2>
<br/>
<br/>
<div class="col-md-8 col-md-offset-2 col-xs-12 widget-container boxed">
    @if (Session::has('success'))
    <div class="alert alert-success fade in alert-dismissable" style="border-radius: 0">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        {{ Session::get('success') }}
    </div>
    @endif

    @if (Session::has('error'))
    <div class="alert alert-danger fade in alert-dismissable" style="border-radius: 0">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        {{ Session::get('error') }}
    </div>
    @endif

    <p>
        {{ Lang::get('messages.front.home.contact.info') }}
    </p>
    {{ Form::open(array('route' => 'front.manga.sendMessage', 'role' => 'form')) }}
    <div class="row control-group">
        <div class="form-group col-xs-12">
            <label for="name">{{ Lang::get('messages.front.home.contact.name') }}</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
    </div>
    <div class="row control-group">
        <div class="form-group col-xs-12">
            <label for="email">{{ Lang::get('messages.front.home.contact.email') }}</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
    </div>
    <div class="row control-group">
        <div class="form-group col-xs-12 controls">
            <label for="message">{{ Lang::get('messages.front.home.contact.subject') }}</label>
            <textarea rows="5" class="form-control" id="subject" name="subject" required></textarea>
        </div>
    </div>
    <br/>
    @if(isset($captcha->form_contact) && $captcha->form_contact === '1')
    <div class="form-group has-feedback {{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">
        {!! NoCaptcha::renderJs() !!}
        {!! NoCaptcha::display() !!}
        {!! $errors->first('g-recaptcha-response', '<span class="help-block">:message</span>') !!}
    </div>
    @endif
    <br>
    <div class="row">
        <div class="form-group col-xs-12">
            <button type="submit" class="btn-lg btn-primary">{{ Lang::get('messages.front.home.contact.send') }}</button>
        </div>
    </div>
    {{ Form::close() }}
</div>
@stop

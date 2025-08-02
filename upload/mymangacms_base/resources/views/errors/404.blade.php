<?php
$settings = Cache::get('options');
$theme = Cache::get('theme');
$variation = Cache::get('variation');
?>
@extends('front.layouts.'.$theme)

@section('title')
{{$settings['seo.title']}} | 
@stop

@section('header')
<style>
    .error-page {
        width: 600px;
        margin: 100px auto;
    }
    .error-page>.headline {
        float: left;
        font-size: 100px;
        font-weight: 300;
    }
    .error-page>.error-content {
        margin-left: 190px;
        display: block;
    }
    .error-page>.error-content>h3 {
        font-weight: 300;
        font-size: 25px;
    }
</style>
@stop

@include('front.themes.'.$theme.'.blocs.menu')

@section('allpage')
<div class="error-page">
    <h2 class="headline text-yellow" style="line-height: 0.6; margin-top: 0;"> 404</h2>
    <div class="error-content">
        <h3><i class="fa fa-warning text-yellow"></i> {{ trans('messages.error_404_title') }}</h3>
        <p>{{ trans('messages.error_404_description') }}</p>
    </div>
    <!-- /.error-content -->
</div>
@stop
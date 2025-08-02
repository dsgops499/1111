@extends('front.layouts.default')

@section('title')
{{$page->title}}
@stop

@section('description')
{{$seo['description']}}
@stop

@section('keywords')
{{$seo['keywords']}}
@stop

@include('front.themes.'.$theme.'.blocs.menu')

@section('allpage')
<div class="row">
    <div class="col-xs-12">
        <div>
            {!!$page->content!!}
        </div>
    </div>
</div>
@stop

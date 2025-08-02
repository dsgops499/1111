@extends('base::layouts.default')

@section('head')
<script src="{{asset('js/vendor/ckeditor/ckeditor.js')}}"></script>

<link rel="stylesheet" href="{{asset('css/bootstrap-select.min.css')}}">
<script src="{{asset('js/vendor/bootstrap-select.min.js')}}"></script>
@stop

@section('breadcrumbs')
{!!Breadcrumbs::render()!!}
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-plus-square-o fa-fw"></i> {{ Lang::get('blog::messages.admin.posts.create') }}
                </h3>
                <div class="box-tools">
                    {{ link_to_route('admin.posts.index', Lang::get('messages.admin.manga.back'), [], array('class' => 'btn btn-default btn-xs', 'role' => 'button')) }}
                </div>
            </div>
            {{ Form::open(array('route' => 'admin.posts.store')) }}
            <div class="box-body">
                <div class="form-group">
                    {{ Form::label('title', Lang::get('blog::messages.admin.posts.title')) }}
                    {{ Form::text('title', '', array('class' => 'form-control rtl', 'placeholder' => 'Enter title here')) }}
                    {!! $errors->first('title', '<label class="error" for="title">:message</label>') !!}
                </div>
                <div class="form-group">
                    {{Form::textarea('content', '', array('id'=>'content', 'class' => 'form-control'))}}
                    <script>
                        CKEDITOR.replace('content', {
                            filebrowserImageBrowseUrl: "{{route('admin.posts.browseImage')}}",
                        });
                    </script>
                </div>
                <div class="form-group">
                    {{Form::label('keywords', 'Keywords')}}
                    <br/>
                    {{Form::text('keywords', '', array('placeholder' => 'comma separated', 'class' => 'form-control'))}}
                </div>
                <div class="form-group">
                    {{Form::label('manga_id', Lang::get('blog::messages.admin.posts.related-to'))}}
                    <br/>
                    {{Form::select('manga_id', $categories, '', array('class' => 'status selectpicker', 'data-width' => '100%'))}}
                </div>
            </div>
            <div class="box-footer">
                {{ Form::submit(Lang::get('blog::messages.admin.posts.save-draft'), array('class' => 'btn btn-default pull-right draft')) }}
                {{ Form::submit(Lang::get('blog::messages.admin.posts.save'), array('class' => 'btn btn-primary pull-right save', 'style' => 'margin-right: 10px;')) }}
                {{ Form::hidden('status', '', array('id' => 'status')) }}
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>

<script>
    $('.save').click(function () {
        $('#status').val('1');
    });
    $('.draft').click(function () {
        $('#status').val('0');
    });
</script>
@endsection
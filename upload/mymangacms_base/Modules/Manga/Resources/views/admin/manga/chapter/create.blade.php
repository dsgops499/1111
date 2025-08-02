@extends('base::layouts.default')

@section('breadcrumbs')
{!!Breadcrumbs::render('admin.manga.chapter.create', $manga)!!}
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <i class="fa fa-book"></i> {{ Lang::get('messages.admin.chapter.create.title') }}
            </div>
            <!-- /.panel-heading -->
            {{ Form::open(array('route' => ['admin.manga.chapter.store', $manga->id])) }}
            <div class="box-body">
                <div class="form-group">
                    {{Form::label('name', Lang::get('messages.admin.chapter.create.chapter-name'))}}
                    {{Form::text('name', '', array('class' => 'form-control'))}}
                    {!! $errors->first('name', '<label class="error" for="name">:message</label>') !!}
                </div>

                <div class="form-group">
                    {{Form::label('number', Lang::get('messages.admin.chapter.create.number'))}}
                    {{Form::text('number', '', array('class' => 'form-control'))}}
                    {!! $errors->first('number', '<label class="error" for="number">:message</label>') !!}
                </div>

                <div class="form-group">
                    {{Form::label('slug', Lang::get('messages.admin.chapter.create.slug'))}}
                    {{Form::text('slug', '', array('class' => 'form-control', 'placeholder' => Lang::get('messages.admin.chapter.create.slug-placeholder')))}}
                    {!! $errors->first('slug', '<label class="error" for="slug">:message</label>') !!}
                </div>              

                <div class="form-group">
                    {{Form::label('volume', Lang::get('messages.admin.chapter.create.volume'))}}
                    {{Form::text('volume', '', array('class' => 'form-control'))}}
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <div class="pull-right">
                    {{ link_to_route('admin.manga.show', Lang::get('messages.admin.chapter.back'), $manga->id, array('class' => 'btn btn-default btn-xs')) }}

                    @if((Sentinel::check()->id==$manga->user->id && Sentinel::hasAccess('manage_my_chapters')) || Sentinel::hasAccess('manga.chapter.create'))
                    {{Form::submit(Lang::get('messages.admin.chapter.create.create-chapter'), array('class' => 'btn btn-primary btn-xs'))}}
                    {{Form::hidden('mangaId', $manga->id, array('id' => 'mangaId'))}}
                    @endif
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection

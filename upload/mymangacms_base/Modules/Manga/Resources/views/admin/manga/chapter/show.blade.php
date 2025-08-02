@extends('base::layouts.default')

@section('head')
<script>
    checked = Array();
    $(document).ready(function () {
        $('.box-body').on('click', 'input[type="checkbox"]', function () {
            if ($(this).prop('checked') == true) {
                if ($(this).val() == 'all') {
                    checked = Array();
                    $('table input[type="checkbox"]').each(function () {
                        $(this).prop('checked', 'checked');
                        checked.push($(this).val());
                    });
                } else {
                    checked.push($(this).val());
                    allChecked = true;
                    $('table input[type="checkbox"]').each(function () {
                        if ($(this).prop('checked') != true) {
                            allChecked = false;
                        }
                    });

                    $('.box-body input.all').prop('checked', allChecked);
                }
            } else {
                if ($(this).val() == 'all') {
                    checked = Array();
                    $('table input[type="checkbox"]').each(function () {
                        $(this).prop('checked', '');
                    });
                } else if (checked.indexOf($(this).val()) != -1) {
                    checked.splice(checked.indexOf($(this).val()), 1);
                    $('.box-body input.all').prop('checked', '');
                }
            }

            $("#pages-ids").val(checked.join(','));
        });
    });
</script>
@endsection

@section('breadcrumbs')
{!!Breadcrumbs::render('admin.manga.chapter.show', $manga, $chapter)!!}
@endsection

@section('content')
<div class="row">
    <div class="col-md-4" >
        <div class="box box-primary">
            <div class="box-header with-border">
                <i class="fa fa-book fa-fw"></i> {{ Lang::get('messages.admin.chapter.edit.title') }}
            </div>
            <!-- /.panel-heading -->
            {{ Form::open(array('route' => array('admin.manga.chapter.update', $manga->id, $chapter->id), 'method' => 'PUT')) }}
            <div class="box-body">
                {{ Form::open(array('route' => array('admin.manga.chapter.update', $manga->id, $chapter->id), 'method' => 'PUT')) }}
                <div class="form-group">
                    {{Form::label('name', Lang::get('messages.admin.chapter.create.chapter-name'))}}
                    {{Form::text('name', $chapter->name, array('class' => 'form-control'))}}
                    {!! $errors->first('name', '<label class="error" for="name">:message</label>') !!}
                </div>

                <div class="form-group">
                    {{Form::label('number', Lang::get('messages.admin.chapter.create.number'))}}
                    {{Form::text('number', $chapter->number, array('class' => 'form-control'))}}
                    {!! $errors->first('number', '<label class="error" for="number">:message</label>') !!}
                </div>

                <div class="form-group">
                    {{Form::label('slug', Lang::get('messages.admin.chapter.create.slug'))}}
                    {{Form::text('slug', $chapter->slug, array('class' => 'form-control', 'placeholder' => Lang::get('messages.admin.chapter.create.slug-placeholder')))}}
                    {!! $errors->first('slug', '<label class="error" for="slug">:message</label>') !!}
                </div>              

                <div class="form-group">
                    {{Form::label('volume', Lang::get('messages.admin.chapter.create.volume'))}}
                    {{Form::text('volume', $chapter->volume, array('class' => 'form-control'))}}
                </div>
            </div>
            <!-- /.box-body -->
            @if(((Sentinel::check()->id==$chapter->user->id||Sentinel::check()->id==$manga->user->id) && Sentinel::hasAccess('manage_my_chapters')) 
            || Sentinel::hasAccess('manga.chapter.edit'))
            <div class="box-footer">
                {{Form::submit(Lang::get('messages.admin.chapter.edit.update-chapter'), array('class' => 'btn btn-primary btn-xs pull-right'))}}
            </div>
            @endif
            {{ Form::close() }}
        </div>
    </div>

    <div class="col-md-8">
        <div class="box box-primary">
            <div class="box-header with-border">
                <i class="fa fa-book fa-fw"></i> {{ Lang::get('messages.admin.chapter.edit.chapter-info', array('number' => $chapter->number, 'name' => $chapter->name)) }}
                <div class="box-tools">
                    @if(((Sentinel::check()->id==$chapter->user->id||Sentinel::check()->id==$manga->user->id) && Sentinel::hasAccess('manage_my_chapters')) 
                    || Sentinel::hasAccess('manga.chapter.edit'))
                    <div class="btn-group">
                        <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                            {{ Lang::get('messages.admin.chapter.edit.add-pages') }}
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu pull-right" role="menu">
                            <li>
                                <a href="#" onclick="$('.zipfile').show();
                                        $('#imagesUrlPanel').hide();
                                        return false">
                                    {{ Lang::get('messages.admin.chapter.edit.upload-zip') }}
                                </a>
                            </li>
                            <li>
                                {{ link_to_route('admin.manga.chapter.page.create', Lang::get('messages.admin.chapter.edit.upload-images'), array('manga' => $manga->id, 'chapter' => $chapter->id)) }}
                            </li>
                            <li>
                                <a href="#" onclick="$('#imagesUrlPanel').show();
                                        $('.zipfile').hide();
                                        return false">
                                    Add by Images URL
                                </a>
                            </li>
                        </ul>
                    </div>
                    @endif
                    @if(((Sentinel::check()->id==$chapter->user->id||Sentinel::check()->id==$manga->user->id) && Sentinel::hasAccess('manage_my_chapters')) 
                    || Sentinel::hasAccess('manga.chapter.destroy'))
                    <div style="display: inline-block">
                        {{ Form::open(array('route' => array('admin.manga.chapter.destroy', $manga->id, $chapter->id), 'method' => 'delete')) }}
                        {{ Form::submit(Lang::get('messages.admin.chapter.edit.delete-chapter'), array('class' => 'btn btn-danger btn-xs',  'onclick' => 'if (!confirm("'. Lang::get('messages.admin.chapter.edit.confirm-delete'). '")) {return false;}')) }}
                        {{ Form::close() }}
                    </div>
                    @endif

                    @if(((Sentinel::check()->id==$chapter->user->id||Sentinel::check()->id==$manga->user->id) && Sentinel::hasAccess('manage_my_chapters')) 
                    || Sentinel::hasAccess('manga.chapter.edit'))
                    <div style="display: inline-block;">
                        {{ Form::open(array('route' => array('admin.manga.chapter.page.destroyPages', $manga->id, $chapter->id), 'method' => 'delete')) }}
                        {{ Form::submit(Lang::get('messages.admin.chapter.edit.delete-pages'), array('class' => 'btn btn-danger btn-xs delete',  'onclick' => 'if (!confirm("'. Lang::get('messages.admin.chapter.edit.confirm-delete-pages'). '")) {return false;}')) }}
                        <input type="hidden" name="pages-ids" id="pages-ids"/>
                        {{ Form::close() }}
                    </div>
                    @endif

                    {{ link_to_route('admin.manga.show', Lang::get('messages.admin.chapter.back'), $manga->id, array('class' => 'btn btn-default btn-xs')) }}                
                </div>
            </div>
            <!-- /.panel-heading -->
            <div class="box-body">
                @if (Session::has('uploadSuccess'))
                <div class="alert text-center alert-info alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    {{ Session::get('uploadSuccess') }}
                </div>
                @elseif (Session::has('uploadError'))
                <div class="alert text-center alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    {{ Session::get('uploadError') }}
                </div>
                @endif

                <div class="zipfile well well-sm" style="display: none;">
                    <div class="form-group">
                        @if ($settings['storage.type'] == 'gdrive')
                        <div class="alert alert-info" role="alert">{{ Lang::get('messages.admin.chapter.scraper.storage-mode.gdrive') }}</div>
                        @else
                        <div class="alert alert-info" role="alert">{{ Lang::get('messages.admin.chapter.scraper.storage-mode.server') }}</div>
                        @endif
                    </div>

                    {{ Form::open(array('route' => 'admin.manga.chapter.uploadZIPFile', 'files' => 'true')) }}
                    <div class="form-group">
                        {{Form::label('zipfile', Lang::get('messages.admin.chapter.edit.zip-error'))}}
                        @if(((Sentinel::check()->id==$chapter->user->id||Sentinel::check()->id==$manga->user->id) && Sentinel::hasAccess('manage_my_chapters')) 
                        || Sentinel::hasAccess('manga.chapter.edit'))
                        <button class="btn btn-success btn-xs pull-right" type="submit">
                            <i class="glyphicon glyphicon-upload"></i>
                            <span>{{ Lang::get('messages.admin.chapter.edit.upload') }}</span>
                        </button>
                        @endif
                        {{Form::file('zipfile')}}
                    </div>
                    {{Form::hidden('mangaSlug', $manga->slug, array('id' => 'mangaSlug'))}}
                    {{Form::hidden('chapterId', $chapter->id, array('id' => 'chapterId'))}}
                    {{ Form::close() }}

                    <div id="waiting-upload" style="display: none;">
                        <center><img src="{{ asset('images/ajax-loader.gif') }}" /></center>
                    </div>
                </div>

                <div id="imagesUrlPanel" class="well well-sm" style="display: none;">
                    <button type="button" class="close" aria-label="Close" onclick="$('#imagesUrlPanel').hide();
                            "><span aria-hidden="true">&times;</span></button>
                    <div class="form-group">
                        <label for="imagesUrl">{{ Lang::get('messages.admin.chapter.edit.add-image-urls') }}</label>
                        <textarea id="imagesUrl" class="form-control" rows="7"></textarea>
                    </div>

                    @if(((Sentinel::check()->id==$chapter->user->id||Sentinel::check()->id==$manga->user->id) && Sentinel::hasAccess('manage_my_chapters')) 
                    || Sentinel::hasAccess('manga.chapter.edit'))
                    <button id="startCreatingBtn" type="button" class="btn btn-default" onclick="startCreatingPages()">
                        {{ Lang::get('messages.admin.chapter.edit.create-pages') }}
                    </button>
                    @endif

                    <div id="waiting" style="display: none; float: right;">
                        <img src="{{ asset('images/ajax-loader.gif') }}" />
                    </div>
                </div>

                <div class="clearfix"></div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th><input type="checkbox" name="check-all" class="all" value="all"/></th>
                                <th>{{ Lang::get('messages.admin.chapter.edit.page-number') }}</th>
                                <th>{{ Lang::get('messages.admin.chapter.edit.page-scan') }}</th>
                                <th>{{ Lang::get('messages.admin.chapter.edit.page-filename') }}</th>
                                <th>{{ Lang::get('messages.admin.chapter.edit.page-slug') }}</th>
                                <th>{{ Lang::get('messages.admin.chapter.edit.page-action') }}</th>
                            </tr>
                        </thead>
                        @if (count($chapter->pages) > 0)
                        <tbody>
                            @foreach ($chapter->pages as $key=>$page)
                            <tr>
                                <td><input type="checkbox" value="{{ $page->id }}"/></td>
                                <td>
                                    @if(((Sentinel::check()->id==$chapter->user->id||Sentinel::check()->id==$manga->user->id) && Sentinel::hasAccess('manage_my_chapters')) 
                                    || Sentinel::hasAccess('manga.chapter.edit'))
                                    <span class="btn btn-primary btn-xs move-page" data-position="up"><i class="fa fa-arrow-up"></i></span>
                                    <span class="index">{{ $key+1 }}</span>
                                    <span class="btn btn-primary btn-xs move-page" data-position="down"><i class="fa fa-arrow-down"></i></span>
                                    <input type="hidden" value="{{$page->id}}"/>
                                    @else
                                    <span class="index">{{ $key+1 }}</span>
                                    @endif
                                </td>
                                <td>
                                    <img width="160" alt="{{ $page->image }}" src='@if($page->external == 0) {{HelperController::pageImageUrl("$manga->slug","$chapter->slug","$page->image")}}<?php echo '?' . time() ?>  @else  {{$page->image}} @endif' />
                                </td>
                                <td class="image">
                                    {{ $page->image }}
                                </td>
                                <td class="slug">
                                    {{ $page->slug }}
                                </td>
                                <td>
                                    @if(((Sentinel::check()->id==$chapter->user->id||Sentinel::check()->id==$manga->user->id) && Sentinel::hasAccess('manage_my_chapters')) 
                                    || Sentinel::hasAccess('manga.chapter.edit'))
                                    {{ Form::open(array('route' => array('admin.manga.chapter.page.destroy', $manga->id, $chapter->id, $page->id), 'method' => 'delete')) }}
                                    {{ Form::submit(Lang::get('messages.admin.chapter.edit.delete-page'), array('class' => 'btn btn-danger btn-xs', 'onclick' => 'if (!confirm("'. Lang::get('messages.admin.chapter.edit.confirm-delete-page'). '")) {return false;}')) }}
                                    {{ Form::close() }}
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        @else
                        <tr>
                            <td colspan="6">
                                <div class="center-block">{{ Lang::get('messages.admin.chapter.edit.no-page') }}</div>
                            </td>
                        </tr>
                        @endif
                    </table>
                </div>                       
            </div>
            <!-- /.box-body -->
        </div>
    </div>
</div>
<script>
    var chapterId = '{{$chapter->id}}';
    var mangaId = '{{$manga->id}}';
    var urls = Array();

    $(document).ready(function () {
        $('.zipfile form').on('submit', function () {
            $('#waiting-upload').show();
        });

        $('table .move-page').first().attr('disabled', 'disabled');
        $('table .move-page').last().attr('disabled', 'disabled');

        $(".move-page").click(function () {
            position = $(this).data('position');
            tr = $(this).parents('tr');

            $.ajax({
                method: "POST",
                url: "{{ route('admin.manga.chapter.movePage') }}",
                data: {'index': tr.index(), 'mangaSlug': "{{ $manga->slug }}", 'chapterId': chapterId, 'position': position, '_token': '{{ csrf_token() }}'},
                success: function (response) {
                    tr.find('span.index').text(response.p1.slug);
                    tr.find('td.image').text(response.p1.image);
                    tr.find('td.slug').text(response.p1.slug);

                    if (position == 'up') {
                        tr.prev('tr').find('span.index').text(response.p2.slug);
                        tr.prev('tr').find('td.image').text(response.p2.image);
                        tr.prev('tr').find('td.slug').text(response.p2.slug);

                        tr.prev('tr').before(tr);
                    } else {
                        tr.next('tr').find('span.index').text(response.p2.slug);
                        tr.next('tr').find('td.image').text(response.p2.image);
                        tr.next('tr').find('td.slug').text(response.p2.slug);

                        tr.next('tr').after(tr);
                    }

                    $('table .move-page').removeAttr('disabled');
                    $('table .move-page').first().attr('disabled', 'disabled');
                    $('table .move-page').last().attr('disabled', 'disabled');
                }
            });
        });
    });

    function startCreatingPages() {
        if (!$('#imagesUrl').val().length) {
            alert('Please enter a valid image URL!');
            return;
        }

        urls = $.trim($('#imagesUrl').val()).split('\n');
        var patt = new RegExp(/\.(jpeg|jpg|gif|png|bmp)$/);
        for (i = 0; i < urls.length; i++) {
            if (!patt.test(urls[i])) {
                alert('Some of your URLs are invalid! Only image files are allowed.');
                return;
            }
        }

        if ($('span#errors').length > 0) {
            $('span#errors').remove();
        }

        $('#startCreatingBtn').addClass('disabled');
        $('#waiting').show();

        $.ajax({
            method: "POST",
            url: "{{ route('admin.manga.chapter.createExternalPages') }}",
            data: {'urls': $.trim($('#imagesUrl').val()).replace(/\n/g, ';'), 'mangaId': mangaId, 'chapterId': chapterId, '_token': '{{ csrf_token() }}'},
            success: function (response) {
                $('#waiting').hide();
                window.location = window.location;
            },
            error: function (xhr, status, error) {
                $('#startCreatingBtn').removeClass('disabled');
                $('#waiting').hide();

                var err = xhr.responseJSON;
                if (typeof err !== "undefined") {
                    if ($('span#errors').length > 0) {
                        $('span#errors').remove();
                    }

                    $('#startCreatingBtn').after('<span id="errors"> ' + err.error.type + ' [' + err.error.message + ']</span>');
                }
            }
        });
    }
</script>
@endsection

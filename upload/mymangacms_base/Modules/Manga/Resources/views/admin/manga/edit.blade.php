@extends('base::layouts.default')

@section('head')
<link rel="stylesheet" href="{{asset('css/bootstrap-select.min.css')}}">
<link rel="stylesheet" href="{{asset('css/selectize.css')}}">
<script src="{{asset('js/vendor/bootstrap-select.min.js')}}"></script>
<script src="{{asset('js/vendor/selectize.js')}}"></script>
<script src="{{asset('js/dropzone.js')}}"></script>
@endsection

@section('breadcrumbs')
{!!Breadcrumbs::render('admin.manga.edit', $manga)!!}
@endsection

@section('content')
<div class="row">
    <div class="col-md-9">
        <div class="box box-primary">
            <div class="box-header with-border">
                <i class="fa fa-pencil-square-o"></i> {{ Lang::get('messages.admin.manga.edit.title') }}
            </div>
            <!-- /.panel-heading -->
            {{ Form::open(array('route' => array('admin.manga.update', $manga->id), 'method' => 'PUT')) }}
            <div class="box-body">
                <div class="form-group">
                    {{Form::label('name', Lang::get('messages.admin.manga.create.manga-name'))}}
                    {{Form::text('name', $manga->name, array('class' => 'form-control'))}}
                    {!! $errors->first('name', '<label class="error" for="name">:message</label>') !!}
                </div>

                <div class="form-group">
                    {{Form::label('slug', Lang::get('messages.admin.manga.create.manga-slug'))}}
                    {{Form::text('slug', $manga->slug, array('class' => 'form-control', 'placeholder' => Lang::get('messages.admin.manga.create.slug-placeholder')))}}
                    {!! $errors->first('slug', '<label class="error" for="slug">:message</label>') !!}
                </div>

                <div class="form-group">
                    {{Form::label('otherNames', Lang::get('messages.admin.manga.create.other-names'))}}
                    {{Form::text('otherNames', $manga->otherNames, array('class' => 'form-control'))}}
                </div>

                <div class="form-group">
                    {{Form::label('author', Lang::get('messages.admin.manga.create.author'))}}
                    {{Form::text('author', $authors_id, array('class' => 'authors'))}}
                </div>

                <div class="form-group">
                    {{Form::label('artist', Lang::get('messages.admin.manga.create.artist'))}}
                    {{Form::text('artist', $artists_id, array('class' => 'authors'))}}
                </div>

                <div class="form-group">
                    {{Form::label('releaseDate', Lang::get('messages.admin.manga.create.released'))}}
                    {{Form::text('releaseDate', $manga->releaseDate, array('class' => 'form-control'))}}
                </div>

                <div class="form-group">
                    {{Form::label('status', Lang::get('messages.admin.manga.create.status'))}}
                    {{Form::select('status_id', $status, $manga->status_id, array('class' => 'selectpicker', 'data-width' => '100%'))}}
                </div>

                <div class="form-group">
                    {{Form::label('categories', Lang::get('messages.admin.manga.create.categories'))}}
                    {{Form::select('categories[]', $categories, $categories_id, array('class' => 'categories selectpicker', 'multiple', 'title' => Lang::get('messages.admin.manga.create.categories-title'), 'data-selected-text-format' => 'count>7', 'data-width' => '100%'))}}
                </div>

                <div class="form-group">
                    {{Form::label('tags', Lang::get('messages.admin.manga.create.tags'))}}
                    {{Form::text('tags', $tags_id, array('class' => 'tags'))}}
                </div>

                <div class="form-group">
                    {{Form::label('comicType', 'Comic type')}}
                    {{Form::select('type_id', $comicTypes, $manga->type_id, array('class' => 'status selectpicker', 'data-width' => '100%'))}}
                </div>

                <div class="form-group">
                    {{Form::label('summary', Lang::get('messages.admin.manga.create.summary'))}}
                    {{Form::textarea('summary', $manga->summary, array('class' => 'form-control', 'rows' => '5'))}}
                </div>

                <div class="form-group">
                    {{Form::label('caution', Lang::get('messages.admin.manga.detail.caution'))}}
                    <label class="radio-inline">
                        <input type="radio" name="caution" value="1" <?php if ($manga->caution == 1): ?>
                               checked="checked"<?php endif ?>/
                               >{{ Lang::get('messages.admin.users.options.yes') }} </label>
                    <label class="radio-inline">
                        <input type="radio" name="caution" value="0" <?php if ($manga->caution == 0): ?>
                               checked="checked"<?php endif ?>
                               />{{ Lang::get('messages.admin.users.options.no') }} </label>
                </div>
            </div>
            <!-- /.panel-body -->
            <div class="box-footer">
                <div class="pull-right">
                    {{ link_to_route('admin.manga.show', Lang::get('messages.admin.manga.back'), $manga->id, array('class' => 'btn btn-default btn-xs'))}}

                    @if((Sentinel::check()->id==$manga->user->id && Sentinel::hasAccess('manage_my_manga')) || Sentinel::hasAccess('manga.manga.edit'))
                    {{Form::submit(Lang::get('messages.admin.manga.edit.update-manga'), array('class' => 'btn btn-primary btn-xs'))}}
                    @endif
                    {{Form::hidden('cover', '', array('id' => 'mangacover'))}}
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
    <div class="col-md-3">
        <div id="coverContainer">
            <div class="coverWrapper">
                <div class="previewWrapper">
                    @if (!is_null($manga->cover))
                    <img class="img-responsive img-rounded" src='{{HelperController::coverUrl("{$manga->slug}/cover/cover_250x350.jpg")}}' alt='{{ $manga->name }}'>
                    @else
                    <i class="fa fa-file-image-o"></i>
                    @endif
                    <div id="previews">
                        <div id="previewTemplate">
                            <div class="dz-preview dz-file-preview">
                                <div class="dz-details">
                                    <div class="dz-filename">{{ Lang::get('messages.admin.manga.create.filename') }} <span data-dz-name></span></div>
                                    <div class="dz-size">{{ Lang::get('messages.admin.manga.create.size') }} <span data-dz-size></span></div>
                                    <img data-dz-thumbnail />
                                </div>
                                <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                                    <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if((Sentinel::check()->id==$manga->user->id && Sentinel::hasAccess('manage_my_manga')) || Sentinel::hasAccess('manga.manga.edit'))
                <div class="uploadBtn">
                    <span class="btn btn-success fileinput-upload btn-xs dz-clickable ">
                        <i class="fa fa-plus"></i>
                        <span>{{ Lang::get('messages.admin.manga.create.upload-cover') }}</span>
                    </span>
                    <span class="btn btn-danger fileinput-remove btn-xs data-dz-remove disabled">
                        <i class="fa fa-times"></i>
                        <span>{{ Lang::get('messages.admin.manga.create.remove-cover') }}</span>
                    </span>
                </div>
                @endif

                <div class="dz-error-message" style="color: red"></div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        // generate slug
        $('#name').keyup(function () {
            slug = $(this).val().toLowerCase()
                    .replace(/[^\w ]+/g, '')
                    .replace(/ +/g, '-');
            $('#slug').val(slug);
        });

        // tags
        var tags = "{{$tags}}";
        var data = tags.split(',');
        var items = data.map(function (x) {
            return {item: x};
        });

        $('.tags').selectize({
            plugins: ['remove_button'],
            delimiter: ',',
            persist: false,
            options: items,
            labelField: "item",
            valueField: "item",
            sortField: 'item',
            searchField: 'item',
            create: true
        });
        
        // authors
        var authors = "{{$authors}}";
        var data = authors.split(',');
        var items2 = data.map(function (x) {
            return {item: x};
        });

        $('.authors').selectize({
            plugins: ['remove_button'],
            delimiter: ',',
            persist: false,
            options: items2,
            labelField: "item",
            valueField: "item",
            sortField: 'item',
            searchField: 'item',
            create: true
        });

        @if (!is_null($manga->cover))
                $("span.fileinput-remove").removeClass("disabled");
        $("span.fileinput-upload").addClass("disabled");
        $('#mangacover').val('{{HelperController::coverUrl("{$manga->slug}/cover/cover_250x350.jpg")}}');

        $("span.fileinput-remove").off().on("click", function () {
            $('.previewWrapper').find('img').remove();
            $('.previewWrapper').append('<i class="fa fa-file-image-o"></i>');
            $("span.fileinput-upload").removeClass("disabled");
            $("span.fileinput-remove").addClass("disabled");
            $('#mangacover').val("");
        });
        @else if($('#mangacover').val().length) {
        $(".previewWrapper i").remove();
        $("span.fileinput-upload").addClass("disabled");
        $("span.fileinput-remove").removeClass("disabled");
        $('.previewWrapper').append("<img class='img-responsive img-rounded' width='250' height='350' src='" + $('#mangacover').val() + "' />");
        $("span.fileinput-remove").off().on("click", function () {
            deletefile($('#mangacover').val().replace(/^.*[\\\/]/, ''));
        });
        }
        @endif
    });

    // Get the template HTML and remove it from the document
    var previewTemplate = $('#previews').html();
    $('#previewTemplate').remove();

    var myDropzone = new Dropzone("#coverContainer", {
        url: "{{ route('admin.upload.cover') }}",
        thumbnailWidth: 200,
        thumbnailHeight: 280,
        acceptedFiles: 'image/*',
        previewTemplate: previewTemplate,
        previewsContainer: "#previews", // Define the container to display the previews
        clickable: ".fileinput-upload" // Define the element that should be used as click trigger to select files.
    });

    myDropzone.on("sending", function (file, xhr, formData) {
        formData.append('_token', '{{ csrf_token() }}');

        $(".previewWrapper i").remove();
        $("span.fileinput-upload").addClass("disabled");
    });

    myDropzone.on("success", function (file, response) {
        $('#previewTemplate').remove();
        $('.previewWrapper').append("<img class='img-responsive img-rounded' width='250' height='350' src='" + response.result + "' />");
        $('#mangacover').val(response.result);
        $("span.fileinput-remove").removeClass("disabled");
        $("span.fileinput-remove").off().on("click", function () {
            @if (!is_null($manga->cover))
                    $('.previewWrapper').find('img').remove();
            $('.previewWrapper').append('<i class="fa fa-file-image-o"></i>');
            $("span.fileinput-upload").removeClass("disabled");
            $("span.fileinput-remove").addClass("disabled");
            $('#mangacover').val("");
            @else
                    deletefile($('#mangacover').val().replace(/^.*[\\\/]/, ''));
            @endif
        });
    });

    myDropzone.on("error", function (file, response) {
        $('.dz-error-message').html(response.error.type + ': ' + response.error.message);
    });

    function deletefile(value) {
        $.post(
                "{{ route('admin.delete.cover') }}",
                {filename: value, _token: '{{ csrf_token() }}'}, function () {
            $('.previewWrapper').find('img').remove();
            $('.previewWrapper').append('<i class="fa fa-file-image-o"></i>');
            $("span.fileinput-upload").removeClass("disabled");
            $("span.fileinput-remove").addClass("disabled");
            $('#mangacover').val("");
        });
    }
</script>
@endsection

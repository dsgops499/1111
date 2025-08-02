@extends('base::layouts.default')

@section('head')
<link rel="stylesheet" href="{{asset('css/bootstrap-select.min.css')}}">
<link rel="stylesheet" href="{{asset('css/selectize.css')}}">
<script src="{{asset('js/vendor/bootstrap-select.min.js')}}"></script>
<script src="{{asset('js/vendor/selectize.js')}}"></script>
<script src="{{asset('js/dropzone.js')}}"></script>
@endsection

@section('breadcrumbs')
{!!Breadcrumbs::render()!!}
@endsection

@section('content')
<div class="row">
    <div class="col-md-9">
        <div class="box box-primary">
            <div class="box-header with-border">
                <i class="fa fa-pencil-square-o"></i> {{ Lang::get('messages.admin.dashboard.create-manga') }}
            </div>
            <!-- /.panel-heading -->
            {{ Form::open(array('route' => 'admin.manga.store', 'role' => 'form')) }}
            <div class="box-body">
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-btn">
                            <select id="source-info" class="selectpicker">
                                <option value="mangapanda">MangaPanda</option>
                                <option value="pecinta">PecintaKomik</option>
                            </select>
                        </div><!-- /btn-group -->
                        <input id="url-data" class="form-control" type="text" placeholder="Enter the URL of the Manga Page"/>
                        <div class="input-group-btn">
                            <!-- Button and dropdown menu -->
                            <button id="get-data" class="btn btn-default" type="button" aria-describedby="helpBlock" style="margin-bottom: 10px;">Get Info</button>
                        </div>
                    </div><!-- /input-group -->
                    <span id="helpBlock" class="help-block"></span>
                </div>
                <br/>

                <div class="form-group">
                    {{Form::label('name', Lang::get('messages.admin.manga.create.manga-name'))}}
                    {{Form::text('name','', array('class' => 'form-control'))}}
                    {!! $errors->first('name', '<label class="error" for="name">:message</label>') !!}
                </div>

                <div class="form-group">
                    {{Form::label('slug', Lang::get('messages.admin.manga.create.manga-slug'))}}
                    {{Form::text('slug','', array('class' => 'form-control', 'placeholder' => Lang::get('messages.admin.manga.create.slug-placeholder')))}}
                    {!! $errors->first('slug', '<label class="error" for="slug">:message</label>') !!}
                </div>

                <div class="form-group">
                    {{Form::label('otherNames', Lang::get('messages.admin.manga.create.other-names'))}}
                    {{Form::text('otherNames', '', array('class' => 'form-control'))}}
                </div>

                <div class="form-group">
                    {{Form::label('author', Lang::get('messages.admin.manga.create.author'))}}
                    {{Form::text('author', '', array('class' => 'authors'))}}
                </div>

                <div class="form-group">
                    {{Form::label('artist', Lang::get('messages.admin.manga.create.artist'))}}
                    {{Form::text('artist', '', array('class' => 'authors'))}}
                </div>

                <div class="form-group">
                    {{Form::label('releaseDate', Lang::get('messages.admin.manga.create.released'))}}
                    {{Form::text('releaseDate', '', array('class' => 'form-control'))}}
                </div>

                <div class="form-group">
                    {{Form::label('status', Lang::get('messages.admin.manga.create.status'))}}
                    {{Form::select('status_id', $status, '', array('class' => 'status selectpicker', 'data-width' => '100%'))}}
                </div>

                <div class="form-group">
                    {{Form::label('categories', Lang::get('messages.admin.manga.create.categories'))}}
                    {{Form::select('categories[]', $categories, '', array('class' => 'categorie selectpicker', 'multiple', 'title' => Lang::get('messages.admin.manga.create.categories-title'), 'data-selected-text-format' => 'count>7', 'data-width' => '100%'))}}
                </div>

                <div class="form-group">
                    {{Form::label('tags', Lang::get('messages.admin.manga.create.tags'))}}
                    {{Form::text('tags', '', array('class' => 'tags'))}}
                </div>

                <div class="form-group">
                    {{Form::label('comicType', 'Comic type')}}
                    {{Form::select('type_id', $comicTypes, '', array('class' => 'status selectpicker', 'data-width' => '100%'))}}
                </div>

                <div class="form-group">
                    {{Form::label('summary', Lang::get('messages.admin.manga.create.summary'))}}
                    {{Form::textarea('summary', '', array('class' => 'form-control', 'rows' => '5'))}}
                </div>

                <div class="form-group">
                    {{Form::label('caution', Lang::get('messages.admin.manga.detail.caution'))}}
                    <label class="radio-inline">
                        <input type="radio" name="caution" value="1" />{{ Lang::get('messages.admin.users.options.yes') }}
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="caution" value="0" checked="checked" />{{ Lang::get('messages.admin.users.options.no') }}
                    </label>
                </div>
            </div>
            <!-- /.panel-body -->
            <div class="box-footer">
                <div class="pull-right">
                    {{ link_to_route('admin.manga.index', Lang::get('messages.admin.manga.back'), [], array('class' => 'btn btn-default btn-xs')) }}

                    @if(Sentinel::hasAnyAccess(['manga.manga.create','manage_my_manga']))
                    {{Form::submit(Lang::get('messages.admin.manga.create-manga'), array('class' => 'btn btn-primary btn-xs'))}}
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
                    <i class="fa fa-file-image-o"></i>
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
                                <div class="dz-error-message"><span data-dz-errormessage></span></div>
                            </div>
                        </div>
                    </div>
                </div>

                @if(Sentinel::hasAnyAccess(['manga.manga.create','manage_my_manga']))
                <div class="uploadBtn">
                    <span class="btn btn-success fileinput-upload btn-xs dz-clickable">
                        <i class="fa fa-plus"></i>
                        <span>{{ Lang::get('messages.admin.manga.create.upload-cover') }}</span>
                    </span>
                    <span class="btn btn-danger fileinput-remove btn-xs data-dz-remove disabled">
                        <i class="fa fa-times"></i>
                        <span>{{ Lang::get('messages.admin.manga.create.remove-cover') }}</span>
                    </span>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        // generate slug
        $('#name').keyup(function () {
            generateSlug($(this).val());
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
        
        selectSource();
        $('#source-info').change(function () {
            selectSource();
        });

        if ($('#mangacover').val().length) {
            $(".previewWrapper i").remove();
            $("span.fileinput-upload").addClass("disabled");
            $("span.fileinput-remove").removeClass("disabled");
            $('.previewWrapper').append("<img class='img-responsive img-rounded' width='250' height='350' src='" + $('#mangacover').val() + "' />");

            $("span.fileinput-remove").off().on("click", function () {
                deletefile($('#mangacover').val().replace(/^.*[\\\/]/, ''));
            });
        }
    });

    function generateSlug(name) {
        slug = name.toLowerCase()
                .replace(/[^\w ]+/g, '')
                .replace(/ +/g, '-');
        $('#slug').val(slug);
    }

    var website = "";
    function selectSource() {
        switch ($('#source-info').val()) {
            case "mangapanda":
                $('#helpBlock').html("e.g. http://www.mangapanda.com/naruto");
                website = "mangapanda";
                break;
            case "comicvn":
                $('#helpBlock').html("e.g. http://comicvn.net/truyen-tranh/one-piece-dao-hai-tac/14");
                website = "comicvn";
                break;
            case "pecinta":
                $('#helpBlock').html("e.g. http://www.pecintakomik.com/One_Piece/");
                website = "pecinta";
                break;
            default:
                $('#source-info').selectpicker('val', 'mangapanda');
                $('#helpBlock').html("e.g. http://www.mangapanda.com/naruto");
        }
    }

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
            deletefile($('#mangacover').val().replace(/^.*[\\\/]/, ''));
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

    indexs = {
        "mangapanda": {
            "name": 0,
            "alt_name": 1,
            "release": 2,
            "status": 3,
            "author": 4,
            "artist": 5,
            "genre": 7,
            "genre_s": ' ',
            "summary": 11
        },
        "pecinta": {
            "name": 7,
            "alt_name": 0,
            "release": 1,
            "status": -1,
            "author": 2,
            "artist": 3,
            "genre": 4,
            "genre_s": ', ',
            "summary": 5
        },
        "comicvn": {
            "name": 0,
            "alt_name": -1,
            "release": -1,
            "status": -1,
            "author": 1,
            "artist": 1,
            "genre": -1,
            "genre_s": ', ',
            "summary": 2
        }
    };

    $('#get-data').click(function () {
        if ($.trim($('#url-data').val()).length > 0) {
            $.ajax({
                url: "{{route('admin.manga.autoMangaInfo')}}",
                method: 'POST',
                data: {
                    'url-data': $('#url-data').val(),
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    name = getValueOf(response.contents[indexs[website].name]);
                    alt_name = getValueOf(response.contents[indexs[website].alt_name]);
                    release = getValueOf(response.contents[indexs[website].release]);
                    status = getValueOf(response.contents[indexs[website].status]);
                    author = getValueOf(response.contents[indexs[website].author]);
                    artist = getValueOf(response.contents[indexs[website].artist]);
                    genre = getValueOf(response.contents[indexs[website].genre]);
                    summary = $.trim(response.contents[indexs[website].summary]);

                    $('#name').val(name);
                    $('#otherNames').val(alt_name);
                    $('#author').val(author);
                    $('#artist').val(artist);
                    $('#releaseDate').val(release);
                    $('#summary').val(summary);
                    generateSlug(name);
                    if ('Ongoing' == status || 'En Curso' == status || 'En cours' == status) {
                        $('.status.selectpicker').selectpicker('val', '1');
                    } else {
                        $('.status.selectpicker').selectpicker('val', '2');
                    }

                    categories = Array();
                    tab = genre.split(indexs[website].genre_s);
                    for (i = 0; i < tab.length; i++) {
                        $('.categorie.selectpicker option').each(function () {
                            if ($(this).text() == tab[i]) {
                                categories.push($(this).val());
                            }
                        });
                    }
                    $('.categorie.selectpicker').selectpicker('val', categories);
                },
                error: function (response) {
                    alert("Error when getting Manga Info!");
                }
            });
        } else {
            alert("Enter a valid URL!");
        }
    });

    function getValueOf(value) {
        if (typeof value == 'undefined')
            return '';
        return $.trim(value.replace(/\n/g, '').substr(value.indexOf(':') + 1));
    }
</script>
@endsection

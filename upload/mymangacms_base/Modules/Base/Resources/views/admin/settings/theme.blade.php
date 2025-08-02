@extends('base::layouts.default')

@section('breadcrumbs')
{!!Breadcrumbs::render('admin.settings.theme')!!}
@endsection

@section('head')
<link rel="stylesheet" href="{{asset('css/bootstrap-select.min.css')}}">
<script src="{{asset('js/vendor/bootstrap-select.min.js')}}"></script>
<script src="{{asset('js/dropzone.js')}}"></script>
@endsection

@section('content')
{{ Form::open(array('route' => 'admin.settings.theme.save', 'role' => 'form')) }}
<div class="row">
    <div class="col-md-8">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-tint"></i> {{ Lang::get('messages.admin.settings.theme.header') }}
                </h3>
            </div>
            <div class="box-body">
                <div class="form-group">
                    {{Form::label('site.theme', Lang::get('messages.admin.settings.theme.select-theme'))}}
                    {{Form::select('site.theme', $themes, $options['site.theme'], array('class' => 'selectpicker', 'data-width' => '100%', 'data-size' => 'false'))}}
                </div>
                <div class="form-group">
                    {{Form::label('site.theme.options[reader_theme]', Lang::get('messages.admin.settings.theme.select-theme-reader'))}}
                    {{Form::select('site.theme.options[reader_theme]', $readerThemes, $themeOpts->reader_theme, array('class' => 'selectpicker', 'data-width' => '100%', 'data-size' => 'false'))}}
                </div>
                <hr/>
                <div class="form-group">
                    {{Form::label('site.theme.options[main_menu]', Lang::get('messages.admin.settings.theme.main-menu'))}}
                    {{Form::select('site.theme.options[main_menu]', $menus, $themeOpts->main_menu, array('class' => 'form-control'))}}
                </div>
                <div class="form-group">
                    {{Form::label('site.theme.options[footer_menu]', Lang::get('messages.admin.settings.theme.footer-menu'))}}
                    {{Form::select('site.theme.options[footer_menu]', $menus, $themeOpts->footer_menu, array('class' => 'form-control'))}}
                </div>
                <hr/>
                <div class="form-group">
                    <label class="radio-inline">
                        <input type="checkbox" name="site.theme.options[boxed]" value="1" 
                            <?php if(isset($themeOpts->boxed) && $themeOpts->boxed == 1): ?>
                               checked="checked"<?php endif ?> />
                        {{ Lang::get('messages.admin.settings.theme.boxed') }}
                    </label>
                </div>
            </div>
            <div class="box-footer">
                <div class="form-group">
                    {{ Form::submit(Lang::get('messages.admin.settings.save'), ['class' => 'btn btn-primary']) }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Logo (height max 50px)</h3>
            </div>
            <div class="box-body">
                <div id="logoContainer">
                    <div class="logoWrapper">
                        <div class="previewWrapper1">
                            <img width="100" height="100" class="placeholder" src="{{asset('images/no-image.png')}}" alt="avatar placeholder"/>
                            <div id="previews">
                                <div id="previewTemplate">
                                    <div class="dz-preview dz-file-preview">
                                        <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                                            <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br/>
                        <div class="uploadBtn">
                            <span class="btn btn-success fileinput-upload btn-xs dz-clickable ">
                                <i class="fa fa-plus"></i>
                                <span>upload</span>
                            </span>
                            <span class="btn btn-danger fileinput-remove btn-xs data-dz-remove disabled">
                                <i class="fa fa-times"></i>
                                <span>remove</span>
                            </span>
                        </div>

                        <div class="dz-error-message" style="color: red"></div>
                        {{Form::hidden('site.theme.options[logo]', '', array('id' => 'logo'))}}
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">favicon (16 x 16)</h3>
            </div>
            <div class="box-body">
                <div id="iconContainer">
                    <div class="iconWrapper">
                        <div class="previewWrapper2">
                            <img width="100" height="100" class="placeholder" src="{{asset('images/no-image.png')}}" alt="avatar placeholder"/>
                            <div id="previews2">
                                <div id="previewTemplate2">
                                    <div class="dz-preview dz-file-preview">
                                        <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                                            <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="uploadBtn2">
                            <span class="btn btn-success fileinput-upload2 btn-xs dz-clickable ">
                                <i class="fa fa-plus"></i>
                                <span>upload</span>
                            </span>
                            <span class="btn btn-danger fileinput-remove2 btn-xs data-dz-remove disabled">
                                <i class="fa fa-times"></i>
                                <span>remove</span>
                            </span>
                        </div>

                        <div class="dz-error-message2" style="color: red"></div>
                        {{Form::hidden('site.theme.options[icon]', '', array('id' => 'icon'))}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{ Form::close() }}

<script>
    $(document).ready(function() {
        @if (!is_null($themeOpts->logo))
            $("span.fileinput-remove").removeClass("disabled");
            $("span.fileinput-upload").addClass("disabled");
            $('#logo').val('{{$themeOpts->logo}}');

            $(".previewWrapper1 .placeholder").remove();
            $("span.fileinput-upload").addClass("disabled");
            $("span.fileinput-remove").removeClass("disabled");
            $('.previewWrapper1').append("<img class='img-responsive' src='" + $('#logo').val() + "' />");
            $("span.fileinput-remove").off().on("click", function() {
                deletelogo($('#logo').val().replace(/^.*[\\\/]/, ''));
            });
        @endif
        
        @if (!is_null($themeOpts->icon))
            $("span.fileinput-remove2").removeClass("disabled");
            $("span.fileinput-upload2").addClass("disabled");
            $('#icon').val('{{$themeOpts->icon}}');

            $(".previewWrapper2 .placeholder").remove();
            $("span.fileinput-upload2").addClass("disabled");
            $("span.fileinput-remove2").removeClass("disabled");
            $('.previewWrapper2').append("<img class='img-responsive' src='" + $('#icon').val() + "' />");
            $("span.fileinput-remove2").off().on("click", function() {
                deleteicon($('#icon').val().replace(/^.*[\\\/]/, ''));
            });
        @endif
    });
    
    function deletelogo(value) {
        $.post(
            "{{ route('admin.delete.img') }}",
            {filename: value, _token: '{{csrf_token()}}'}, function() {
                $('.previewWrapper1').find('img').remove();
                $('.previewWrapper1').append('<img width="100" height="100" class="placeholder" src="{{asset("images/no-image.png")}}" alt="placeholder"/>');
                $("span.fileinput-upload").removeClass("disabled");
                $("span.fileinput-remove").addClass("disabled");
                $('#logo').val("");
            });
    }

    // Get the template HTML and remove it from the document
    var previewTemplate = $('#previews').html();
    $('#previewTemplate').remove();
    var myDropzone = new Dropzone("#logoContainer", {
        url: "{{ route('admin.upload.logo') }}",
        thumbnailWidth: 180,
        thumbnailHeight: 50,
        acceptedFiles: 'image/*',
        previewTemplate: previewTemplate,
        previewsContainer: "#previews", 
        clickable: ".fileinput-upload" 
    });
    
    myDropzone.on("sending", function(file, xhr, formData) {
        formData.append('_token', '{{ csrf_token() }}');
        $(".previewWrapper1 .placeholder").remove();
        $("span.fileinput-upload").addClass("disabled");
    });
    
    myDropzone.on("success", function(file, response) {
        $('#previewTemplate').remove();
        $('.previewWrapper1').append("<img class='img-responsive' src='" + response.result + "' />");
        $('#logo').val(response.result);
        $("span.fileinput-remove").removeClass("disabled");
        $("span.fileinput-remove").off().on("click", function() {
            @if (false)
                $('.previewWrapper1').find('img').remove();
                $('.previewWrapper1').append('<img width="100" height="100" class="placeholder" src="{{asset("images/no-image.png")}}" alt="placeholder"/>');
                $("span.fileinput-upload").removeClass("disabled");
                $("span.fileinput-remove").addClass("disabled");
                $('#logo').val("");
            @else
                deletelogo($('#logo').val().replace(/^.*[\\\/]/, ''));
            @endif
        });
    });
    
    myDropzone.on("error", function(file, response) {
        $('.dz-error-message').html(response.error.type + ': ' + response.error.message);
    });
    
    //icon
    function deleteicon(value) {
        $.post(
            "{{ route('admin.delete.img') }}",
            {filename: value, _token: '{{csrf_token()}}'}, function() {
                $('.previewWrapper2').find('img').remove();
                $('.previewWrapper2').append('<img width="100" height="100" class="placeholder" src="{{asset("images/no-image.png")}}" alt="placeholder"/>');
                $("span.fileinput-upload2").removeClass("disabled");
                $("span.fileinput-remove2").addClass("disabled");
                $('#icon').val("");
            });
    }

    // Get the template HTML and remove it from the document
    var previewTemplate2 = $('#previews2').html();
    $('#previewTemplate2').remove();
    var myDropzone2 = new Dropzone("#iconContainer", {
        url: "{{ route('admin.upload.icon') }}",
        thumbnailWidth: 50,
        thumbnailHeight: 50,
        acceptedFiles: 'image/*',
        previewTemplate: previewTemplate2,
        previewsContainer: "#previews2", 
        clickable: ".fileinput-upload2" 
    });
    
    myDropzone2.on("sending", function(file, xhr, formData) {
        formData.append('_token', '{{ csrf_token() }}');
        $(".previewWrapper2 .placeholder").remove();
        $("span.fileinput-upload2").addClass("disabled");
    });
    
    myDropzone2.on("success", function(file, response) {
        $('#previewTemplate2').remove();
        $('.previewWrapper2').append("<img class='img-responsive' src='" + response.result + "' />");
        $('#icon').val(response.result);
        $("span.fileinput-remove2").removeClass("disabled");
        $("span.fileinput-remove2").off().on("click", function() {
            @if (false)
                $('.previewWrapper2').find('img').remove();
                $('.previewWrapper2').append('<img width="50" height="50" class="placeholder" src="{{asset("images/no-image.png")}}" alt="placeholder"/>');
                $("span.fileinput-upload2").removeClass("disabled");
                $("span.fileinput-remove2").addClass("disabled");
                $('#icon').val("");
            @else
                deleteicon($('#icon').val().replace(/^.*[\\\/]/, ''));
            @endif
        });
    });
    
    myDropzone2.on("error", function(file, response) {
        $('.dz-error-message2').html(response.error.type + ': ' + response.error.message);
    });
</script>
@endsection
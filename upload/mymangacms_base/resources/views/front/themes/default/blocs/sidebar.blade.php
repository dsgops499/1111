@section('sidebar')

<!-- ads -->
<div class="row">
    <div class="col-xs-12" style="padding: 0">
        <div style="display: table; margin: 10px auto;">
            {!!isset($ads['RIGHT_SQRE_1'])?$ads['RIGHT_SQRE_1']:''!!}
        </div>
        <div style="display: table; margin: 10px auto;">
            {!!isset($ads['RIGHT_WIDE_1'])?$ads['RIGHT_WIDE_1']:''!!}
        </div>
    </div>
</div>

@foreach($widgets as $index=>$widget)
@if($widget->type == 'site_description')
<!-- About Me -->
<div class="alert alert-success">
    <div class="about">
        <h2>{{$settings['site.name']}}</h2>
        <h6>{{$settings['site.slogan']}}</h6>
        <p>
            {{$settings['site.description']}}
        </p>
    </div>
</div>
<!--/ About Me -->
@elseif($widget->type == 'top_rates')
<!-- Manga Top 10 -->
<?php if (is_module_enabled('Manga')): ?>
    <script>
        $(document).ready(function () {
            $('#waiting').show();

            $.ajax({
                url: "{{route('front.topManga')}}",
            }).done(function (data) {
                $('#waiting').hide();
                $('.top_rating_blade').html(data);
            });
        });
    </script>
    <div class="panel panel-success">
        @if(strlen(trim($widget->title))>0)
        <div class="panel-heading">
            <h3 class="panel-title"><strong>{{ $widget->title }}</strong></h3>
        </div>
        @endif
        <div id="waiting" style="display: none;text-align: center;">
            <img src="{{ asset('images/ajax-loader.gif') }}" />
        </div>
        <ul class="top_rating_blade"></ul>
    </div>
<?php endif; ?>
<!--/ Manga Top 10 -->
@elseif($widget->type == 'top_views')
@if (count($topViewsManga)>0)
<div class="panel panel-success">
    @if(strlen(trim($widget->title))>0)
    <div class="panel-heading">
        <h3 class="panel-title"><strong>{{ $widget->title }}</strong></h3>
    </div>
    @endif
    <ul>
        @foreach ($topViewsManga as $index=>$manga)
        <li class="list-group-item">
            <div class="media">
                <div class="media-left">
                    <a href="{{route('front.manga.show',$manga->slug)}}">
                        @if ($manga->cover)
                        <img width="50" src='{{HelperController::coverUrl("$manga->slug/cover/cover_thumb.jpg")}}' alt='{{ $manga->name }}'>
                        @else
                        <img width="50" src='{{asset("images/no-image.png")}}' alt='{{ $manga->name }}' />
                        @endif
                    </a>
                </div>
                <div class="media-body">
                    <h5 class="media-heading"><a href="{{route('front.manga.show',$manga->slug)}}" class="chart-title"><strong>{{$manga->name}}</strong></a></h5>
                    <i class="fa fa-eye"></i> {{ $manga->views }}
                </div>
            </div>
        </li>
        @endforeach
    </ul>
</div>
@endif
@elseif($widget->type == 'custom_code')
<div class="panel panel-default">
    @if(strlen(trim($widget->title))>0)
    <div class="panel-heading">
        <h3 class="panel-title"><strong>{{ $widget->title }}</strong></h3>
    </div>
    @endif
    <div class="panel-body">
        {!! $widget->code !!}
    </div>
</div>
@elseif($widget->type == 'tags' && count($tags) > 0)
<div class="panel tag-widget" style="box-shadow: none">
    <div class="tag-links">
        @foreach($tags as $slug=>$tag)
        {{ link_to_route('front.manga.list.archive', $tag, ['tag', $slug]) }}
        @endforeach
    </div>
</div>
@endif
@endforeach

<!-- ads -->
<div class="row">
    <div class="col-xs-12" style="padding: 0">
        <div style="display: table; margin: 10px auto;">
            {!!isset($ads['RIGHT_SQRE_2'])?$ads['RIGHT_SQRE_2']:''!!}
        </div>
        <div style="display: table; margin: 10px auto;">
            {!!isset($ads['RIGHT_WIDE_2'])?$ads['RIGHT_WIDE_2']:''!!}
        </div>
    </div>
</div>
@stop

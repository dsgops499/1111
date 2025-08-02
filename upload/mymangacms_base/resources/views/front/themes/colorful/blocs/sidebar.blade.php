@section('sidebar')
@if(env('ALLOW_SUBSCRIBE', false))
<div class="login-box">
    <ol class="login breadcrumb">
        @if(!Sentinel::check())
        <li class="item-link">
            <a href="{{route('register')}}">
                <i class="glyphicon glyphicon-edit"></i>{{Lang::get('messages.front.home.subscribe')}}
            </a>
        </li>
        <li class="item-link">
            <a href="{{route('login')}}">
                <i class="glyphicon glyphicon-log-in"></i>{{Lang::get('messages.front.home.login')}}
            </a>
        </li>
        @else
        <li class="item-link">
            <a href="{{Sentinel::hasAccess('dashboard.index')?route('admin.index'):'#'}}">
                <i class="glyphicon glyphicon-tasks"></i>{{Lang::get('messages.front.home.hello-user', array('user'=> $userCmp->username))}}
            </a>
        </li>
        <li class="item-link">
            <a href="{{route('logout')}}">
                <i class="glyphicon glyphicon-log-out"></i>{{Lang::get('messages.front.home.logout')}}
            </a>
        </li>  
        @endif
    </ol>
</div>
@endif

<?php if (is_module_enabled('MySpace')): ?>
@if(env('ALLOW_SUBSCRIBE', false) && Sentinel::check())
<ol class="login breadcrumb">
        <li class="item-link">
            <a href="{{route('bookmark.index')}}">
                <i class="glyphicon glyphicon-star"></i>{{ Lang::get('messages.front.menu.bookmarks') }}
            </a>
        </li>
        <li class="item-link">
            <a href="{{route('user.show', $userCmp->username)}}">
                <i class="glyphicon glyphicon-user"></i> {{Lang::get('messages.front.myprofil.my-profil')}}
            </a>
        </li>  
    </ol>
@endif
<?php endif; ?>

<div class="login-box">
    <ol class="login breadcrumb">
        <li class="item-link">
            <a href="{{route('front.feed')}}" title="{{Lang::get('messages.front.home.rss-feed')}}">
                <i class="glyphicon glyphicon-menu-hamburger" style="color: #FF9900"></i> {{Lang::get('messages.front.home.rss-feed')}}
            </a>
        </li>
        <li class="item-link">
            <a href="{{route('front.manga.contactUs')}}" title="{{Lang::get('messages.front.home.contact-us')}}">
                <i class="glyphicon glyphicon-envelope"></i> {{Lang::get('messages.front.home.contact-us')}}
            </a>
        </li>
    </ol>
</div>

<!-- search -->
<div role="search">
    <div class="form-group">
        <input id="autocomplete" class="form-control" type="text" placeholder="{{Lang::get('messages.front.menu.search')}}" style="border-radius:0;"/>
    </div>
</div>
<!--/ search -->

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
<div class="price-list style3">
    <div class="price-item boxed">
        <div class="price-content bg-image-home">
            <h3 class="price-title">{{$settings['site.name']}}</h3>
            <h6 class="price-subtitle">{{$settings['site.slogan']}}</h6>
            <p>
                {{$settings['site.description']}}
            </p>
        </div>
    </div>
</div>
<!--/ About Me -->
@elseif($widget->type == 'top_rates')
<!-- Manga Top 10 -->
@if (count($topManga)>0)
<div class="widget-container widget-top3chart boxed">
    <ul class="chart-tab active">
        @foreach ($topManga as $index=>$manga)
        <li class="clearfix">
            <span class="position">{{$index+1}}</span>
            <div class="chart-avatar">
                @if ($manga->manga_cover)
                <img src='{{HelperController::coverUrl("$manga->manga_slug/cover/cover_thumb.jpg")}}' alt='{{ $manga->manga_name }}'>
                @else
                <img src='{{asset("images/no-image.png")}}' alt='{{ $manga->manga_name }}' />
                @endif
            </div>
            <a href="{{route('front.manga.show',$manga->manga_slug)}}" class="chart-title"><strong>{{$manga->manga_name}}</strong></a>
            <a href='{{ route("front.manga.reader", [$manga->manga_slug, $manga->chapter_slug]) }}' class="chart-title"><i>{{"#".$manga->chapter_number.". ".$manga->chapter_name}}</i></a>
        </li>
        @endforeach
    </ul>
    @if(strlen(trim($widget->title))>0)
    <ul class="chart-links green clearfix">
        <li class="text">{{ $widget->title }}</li>
    </ul>
    @endif
</div>
@endif
<!--/ Manga Top 10 -->
@elseif($widget->type == 'top_views')
@if (count($topViewsManga)>0)
<div class="widget-container widget-top3chart boxed">
    <ul class="chart-tab active">
        @foreach ($topViewsManga as $index=>$manga)
        <li class="clearfix">
            <span class="position">{{$index+1}}</span>
            <div class="chart-avatar">
                @if ($manga->cover)
                <img src='{{HelperController::coverUrl("$manga->slug/cover/cover_thumb.jpg")}}' alt='{{ $manga->name }}'>
                @else
                <img src='{{asset("images/no-image.png")}}' alt='{{ $manga->name }}' />
                @endif
            </div>
            <a href="{{route('front.manga.show',$manga->slug)}}" class="chart-title"><strong>{{$manga->name}}</strong></a>
            <i class="glyphicon glyphicon-eye-open"></i> {{ $manga->views }}
        </li>
        @endforeach
    </ul>
    @if(strlen(trim($widget->title))>0)
    <ul class="chart-links green clearfix">
        <li class="text">{{ $widget->title }}</li>
    </ul>
    @endif
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
<div class="price-list style3">
    <div class="price-item boxed">
        <div class="price-content">
            <div class="tag-links">
                @foreach($tags as $slug=>$tag)
                {{ link_to_route('front.manga.list.archive', $tag, ['tag', $slug]) }}
                @endforeach
            </div>
        </div>
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

<style>
    .searching {
        background-image: url('{{asset("images/ajax-loader.gif")}}');
        background-position: 95% 50%;
        background-repeat: no-repeat;
    }
</style>
<script>
    $('#autocomplete').autocomplete({
        serviceUrl: "{{ route('front.search') }}",
        onSearchStart: function (query) {
            $('#autocomplete').addClass('searching');
        },
        onSearchComplete: function (query, suggestions) {
            $('#autocomplete').removeClass('searching');
        },
        onSelect: function (suggestion) {
            showURL = "{{ route('front.manga.show', 'SELECTION') }}";
            window.location.href = showURL.replace('SELECTION', suggestion.data);
        }
    });
</script>
@stop


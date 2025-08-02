@section('menu')
@if(!is_null($settings['site.theme.options']) || "" !== $settings['site.theme.options'])
@php $themeOpts=json_decode($settings['site.theme.options']) @endphp
@endif
<nav class="navbar navbar-default" role="navigation">
    <div class="@if(isset($themeOpts->boxed) && $themeOpts->boxed == 1) container @else container-fluid @endif">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-menu">
                <span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span>
            </button> 
            <h1 class="@if(!is_null($themeOpts) && !is_null($themeOpts->logo))navbar-brand-logo @endif" style="margin:0;">
                <a class="navbar-brand" href="{{route('front.index')}}">
                    @if(!is_null($themeOpts) && !is_null($themeOpts->logo))
                    <img alt="{{$settings['site.name']}}" src="{{$themeOpts->logo}}"/>
                    <span style="display: none">{{$settings['site.name']}}</span>
                    @else
                    {{$settings['site.name']}}
                    @endif
                </a>
            </h1>
        </div>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav @if(config('settings.orientation') === 'rtl') navbar-left @else navbar-right @endif">
                @if(env('ALLOW_SUBSCRIBE', false))
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user"></i> <span class="caret"></span></a>
                    <ul class="dropdown-menu profil-menu">
                        @if(!Sentinel::check())
                        <li>
                            <a href="{{ route('register') }}">
                                <i class="fa fa-pencil-square-o"></i> {{Lang::get('messages.front.home.subscribe')}}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('login') }}">
                                <i class="fa fa-sign-in"></i> {{Lang::get('messages.front.home.login')}}
                            </a>
                        </li>
                        @else
                        <li class="text-center" style="padding: 5px 0">
                            Hi, {{$userCmp->username}}!
                        </li>
                        <?php if (is_module_enabled('MySpace')): ?>
                        <li>
                            <a href="{{route('user.show', $userCmp->username)}}">
                                <i class="fa fa-user"></i> {{Lang::get('messages.front.myprofil.my-profil')}}
                            </a>
                        </li>
                        <li>
                            <a href="{{route('bookmark.index')}}">
                                <i class="fa fa-heart"></i> {{Lang::get('messages.front.bookmarks.title')}}
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php if (is_module_enabled('Notification')): ?>
                            <li>
                                <a href="{{route('front.notification.index')}}">
                                    <i class="fa fa-bell"></i> My Notifications
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if (is_module_enabled('Notification') || is_module_enabled('MySpace')): ?>
                            <li role="separator" class="divider"></li>
                        <?php endif; ?>
                        <?php if (is_module_enabled('Manga')): ?>
                            @if(Sentinel::hasAnyAccess(['manga.manga.create','manga.chapter.create']))
                            <li>
                                <a href="{{route('admin.manga.index')}}">
                                    <i class="fa fa-plus"></i> {{Lang::get('messages.front.myprofil.add-manga-chapter')}}
                                </a>
                            </li>
                            @endif
                        <?php endif; ?>
                        <?php if (is_module_enabled('Blog')): ?>
                            @if(Sentinel::hasAccess('blog.manage_posts'))
                            <li>
                                <a href="{{route('admin.posts.index')}}">
                                    <i class="fa fa-plus"></i> {{Lang::get('messages.front.myprofil.add-post')}}
                                </a>
                            </li>
                            @endif
                        <?php endif; ?>
                        @if(Sentinel::hasAccess('dashboard.index'))
                        <li>
                            <a href="{{route('admin.index')}}">
                                <i class="fa fa-cogs"></i> {{Lang::get('messages.front.home.dashboard')}}
                            </a>
                        </li>
                        <li role="separator" class="divider"></li>
                        @endif
                        <li>
                            <a href="{{ route('logout') }}">
                                <i class="fa fa-sign-out"></i> {{Lang::get('messages.front.home.logout')}}
                            </a>
                        </li>  
                        @endif
                    </ul>
                </li>
                @endif
                <li class="search dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-search"></i></a>
                    <div class="dropdown-menu">
                        <form class="navbar-form">
                            <div class="navbar-form @if(config('settings.orientation') === 'rtl') navbar-left @else navbar-right @endif" role="search">
                                <div class="form-group">
                                    <input id="autocomplete" class="form-control" type="text" placeholder="{{Lang::get('messages.front.menu.search')}}" style="border-radius:0;"/>
                                </div>
                            </div>
                        </form>
                    </div>
                </li>
                <!-- Notifications Menu -->
                <?php if (is_module_enabled('Notification')): ?>
                    @if(Sentinel::check())
                        <?php if (is_module_enabled('Notification')): ?>
                            @include('notification::partials.notifications')
                        <?php endif; ?>
                    @endif
                <?php endif; ?>
            </ul>
        </div>
        <div class="collapse navbar-collapse" id="navbar-menu">
            <!-- menu -->
            <ul class="nav navbar-nav @if(config('settings.orientation') === 'rtl') navbar-left @else navbar-right @endif">
                @if(!is_null($themeOpts) && !is_null($themeOpts->main_menu))
                {!! HelperController::renderMenu($themeOpts->main_menu) !!}
                @endif
            </ul>
        </div>
    </div>
</nav>

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
            showURL = "{{ Route::has('front.manga.show')?route('front.manga.show', 'SELECTION'):'#' }}";
            window.location.href = showURL.replace('SELECTION', suggestion.data);
        }
    });
</script>
@stop


@section('menu')
@if(!is_null($settings['site.theme.options']) || "" !== $settings['site.theme.options'])
@php $themeOpts=json_decode($settings['site.theme.options']) @endphp
@endif
<div style="position:relative">
    <a class="navbar-brand" href="{{route('front.index')}}">
        @if(!is_null($themeOpts) && !is_null($themeOpts->logo))
        <h1 class="navbar-brand-logo" style="margin:0;">
            <img alt="{{$settings['site.name']}}" src="{{$themeOpts->logo}}"/>
            <span style="display: none">{{$settings['site.name']}}</span>
        </h1>
        @else
        <h1>{{$settings['site.name']}}</h1>
        @endif
    </a>

    <!-- menu -->
    <ul class="menu boxed clearfix bg-image-home">
        @if(!is_null($themeOpts) && !is_null($themeOpts->main_menu))
        {!! HelperController::renderMenu($themeOpts->main_menu) !!}
        @endif
    </ul>

</div>
@stop


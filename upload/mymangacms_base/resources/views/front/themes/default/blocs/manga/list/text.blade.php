<div class="type-content">
    <script>
        $(document).ready(function() {
            alphabetFilter();
        });
    </script>

    @if (count($mangaList)>0)
    @foreach ($mangaList as $key=>$group)
    <div class="panel panel-primary tab-header {{ $key=='#'?'unknown':$key }}">
        <div class="panel-heading ">
            <h3 class="panel-title"><a><b>{{ $key }}</b></a></h3>
        </div>
        <div class="panel-body">
            <ul class="manga-list">
                @foreach ($group as $manga)
                <li>
                    @if (count($group)>1)
                    <span class="text-primary separator">/</span>
                    @endif

                    <a href="{{route('front.manga.show',$manga->slug)}}" class="alpha-link">
                        <h6 style="margin: 0;display: inline;">{{ $manga->name }}</h6>
                    </a>
                </li>
                @endforeach
            </ul>
            <div style="clear: both;"/></div>
    </div>
</div>
@endforeach
@else
<div class="center-block">
    <p>{{ Lang::get('messages.front.directory.no-manga') }}</p>
</div>
@endif
</div>

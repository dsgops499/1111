@foreach ($topManga as $index=>$manga)
<li class="list-group-item">
    <div class="media">
        <div class="media-left">
            <a href="{{route('front.manga.show',$manga->manga_slug)}}">
                @if ($manga->manga_cover)
                <img width="50" src='{{HelperController::coverUrl("$manga->manga_slug/cover/cover_thumb.jpg")}}' alt='{{ $manga->manga_name }}'>
                @else
                <img width="50" src='{{asset("images/no-image.png")}}' alt='{{ $manga->manga_name }}' />
                @endif
            </a>
        </div>
        <div class="media-body">
            <h5 class="media-heading"><a href="{{route('front.manga.show',$manga->manga_slug)}}" class="chart-title"><strong>{{$manga->manga_name}}</strong></a></h5>
            <a href='{{ route("front.manga.reader", [$manga->manga_slug, $manga->chapter_slug]) }}' class="chart-title">{{"#".$manga->chapter_number.". ".$manga->chapter_name}}</a>
        </div>
    </div>
</li>
@endforeach

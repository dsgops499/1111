@foreach($menuNodes as $node)
@if($node->children->count()>0)
<li class="dropdown @if($node->css_class) {{ $node->css_class }} @endif">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
        {{ $node->title }} <span class="caret"></span>
    </a>
    <ul class="dropdown-menu">
        @php for($i=0;$i<$node->children->count();$i++) { @endphp
        <li @if($node->children[$i]->css_class) class="{{ $node->children[$i]->css_class }}" @endif>
             <a href="{{ ($node->children[$i]->type === 'route')?(Route::has($node->children[$i]->url)?route($node->children[$i]->url):'#'):
                         (($node->children[$i]->type == 'page')?route('front.index').'/'.$node->children[$i]->url:$node->children[$i]->url) }}"
           @if($node->children[$i]->target) target="{{ $node->children[$i]->target }}" @endif
           title="{{ $node->children[$i]->title }}">
           @if($node->children[$i]->icon_font) <i class="{{ $node->children[$i]->icon_font }}"></i> @endif
                {{ $node->children[$i]->title }}
            </a>
        </li>
        @php } @endphp
    </ul>
</li>
@else
<li @if($node->css_class) class="{{ $node->css_class }}" @endif>
     <a href="{{ ($node->type == 'route')?(Route::has($node->url)?route($node->url):'#'):
                 (($node->type == 'page')?route('front.index').'/'.$node->url:$node->url) }}"
   @if($node->target) target="{{ $node->target }}" @endif
   title="{{ $node->title }}">
   @if($node->icon_font)
   <i class="{{ $node->icon_font }}"></i>
        @endif
        {{ $node->title }}
    </a>
</li>
@endif
@endforeach

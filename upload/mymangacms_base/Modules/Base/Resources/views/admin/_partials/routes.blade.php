<div class="box box-primary box-link-menus"
     data-type="route">
    <div class="box-header with-border">
        <h3 class="box-title">
            <i class="icon-layers font-dark"></i>
            {{ Lang::get('messages.admin.routes') }}
        </h3>
        <div class="box-tools">
            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                <i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="box-body">
        <div class="scroller height-auto"
             style="max-height: 300px;"
             data-rail-visible="1">
            @if(isset($routes) && is_array($routes))
            <ul>
                @foreach($routes as $id=>$value)
                <li>
                    {{ Form::customCheckbox([
                    ['', $id, $value],
                    ]) }}
                </li>
                @endforeach
            </ul>
            @endif
        </div>
    </div>
    <div class="box-footer text-right">
        <button class="btn btn-primary add-item"
                type="submit">
            <i class="fa fa-plus"></i> {{ Lang::get('messages.admin.settings.add') }}
        </button>
    </div>
</div>

@extends('base::layouts.default')

@section('page_title')
Dashboard
@endsection

@section('breadcrumbs')
{!!Breadcrumbs::render()!!}
@endsection

@section('content')
<!-- Small boxes (Stat box) -->
@include('base::admin._partials.boxes')

<?php if (is_module_enabled('Manga')): ?>
    <!-- Hot Manga -->
    <div class="row">
        <!-- Latest Manga -->
        <div class="col-md-12">
            <div class="box box-danger">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-star"></i> {{ Lang::get('messages.admin.dashboard.hotmanga') }}
                    </h3>
                    @if(Sentinel::hasAccess('manga.manga.hot'))
                    <div class="box-tools">
                        {{ link_to_route('admin.manga.hot', Lang::get('messages.admin.dashboard.edit-hotlist'), [], array('class' => 'btn btn-primary btn-xs', 'role' => 'button')) }}
                    </div>
                    @endif
                </div>
                <div class="box-body">
                    @if (count($mangas)>0)
                    <div class="row">
                        @foreach ($hotmanga as $manga)
                        <div class="col-sm-4 col-md-2 text-center">
                            <a href='@if(Sentinel::hasAnyAccess(["manga.manga.index","manga.manga.create","manga.manga.edit","manga.manga.destroy"])) {{route("admin.manga.show",$manga->id)}} @endif'>                                
                                <img class="img-responsive" src='{{HelperController::coverUrl("$manga->slug/cover/cover_250x350.jpg")}}' alt='{{ $manga->name }}' />
                            </a>
                            <a href='@if(Sentinel::hasAnyAccess(["manga.manga.index","manga.manga.create","manga.manga.edit","manga.manga.destroy"])) {{route("admin.manga.show",$manga->id)}} @endif' class="users-list-name">
                                {{ $manga->name }}
                            </a>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-center">{{ Lang::get('messages.admin.dashboard.hotlist-empty') }}</p>
                    @endif
                </div>
                @if(Sentinel::hasAccess('manga.manga.hot'))
                <div class="box-footer text-center">
                    {{ link_to_route('admin.manga.hot', 'View All') }}
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Latest Manga -->
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-pencil-square-o fa-fw"></i> {{ Lang::get('messages.admin.dashboard.latest-added-manga') }}
                    </h3>
                    @if(Sentinel::hasAccess('manga.manga.create'))
                    <div class="box-tools pull-right">
                        {{ link_to_route('admin.manga.create', Lang::get('messages.admin.dashboard.create-manga'), [], array('class' => 'btn btn-primary btn-xs', 'role' => 'button')) }}
                    </div>
                    @endif
                </div>
                <div class="box-body">
                    @if (count($mangas)>0)
                    <ul class="products-list product-list-in-box">
                        @foreach ($mangas as $manga)
                        <li class="item">
                            <div class="product-img">
                                <a href='@if(Sentinel::hasAnyAccess(["manga.manga.index","manga.manga.create","manga.manga.edit","manga.manga.destroy"])) {{route("admin.manga.show",$manga->id)}} @endif'>                                
                                    <img width="50" height="50" class="media-object" src='{{HelperController::coverUrl("$manga->slug/cover/cover_thumb.jpg")}}' alt='{{ $manga->name }}' />
                                </a>
                            </div>
                            <div class="product-info">
                                @if(Sentinel::hasAnyAccess(["manga.manga.index","manga.manga.create","manga.manga.edit","manga.manga.destroy"]))
                                {{ link_to_route("admin.manga.show", $manga->name, $manga->id, array('class' => 'product-title')) }}
                                @else
                                {{ $manga->name }}
                                @endif
                                <div class="pull-right">
                                    <i class="fa fa-user"></i>
                                    <small>{{ $manga->user->username }}</small>
                                </div>
                                <div class="product-description">
                                    <i class="fa fa-calendar-o"></i>
                                    <small>{{ HelperController::formateCreationDate($manga->created_at) }}</small>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    @else
                    <p class="text-center">{{ Lang::get('messages.admin.dashboard.no-manga') }}</p>
                    @endif
                </div>
                @if(Sentinel::hasAnyAccess(["manga.manga.index","manga.manga.create","manga.manga.edit","manga.manga.destroy"]))
                <div class="box-footer text-center">
                    {{ link_to_route('admin.manga.index', Lang::get('messages.admin.dashboard.view-all-manga')) }}
                </div>
                @endif
            </div>
        </div>

        <!-- Latest Chapter -->
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-book fa-fw"></i> {{ Lang::get('messages.admin.dashboard.latest-added-chapter') }}
                    </h3>
                </div>
                <div class="box-body">
                    @if (count($chapters)>0)
                    <ul class="products-list product-list-in-box">
                        @foreach ($chapters as $chapter)
                        <li class="item">
                            <div class="product-img">
                                <a href='@if(Sentinel::hasAnyAccess(["manga.manga.index","manga.manga.create","manga.manga.edit","manga.manga.destroy"])) {{route("admin.manga.show",$chapter->manga_id)}} @endif'>                                
                                    <img width="50" height="50" class="media-object" src='{{HelperController::coverUrl("$chapter->manga_slug/cover/cover_thumb.jpg")}}' alt='{{ $chapter->manga_name }}' />
                                </a>
                            </div>
                            <div class="product-info">
                                @if(Sentinel::hasAnyAccess(["manga.chapter.index","manga.chapter.create","manga.chapter.edit","manga.chapter.destroy"]))
                                {{ link_to_route("admin.manga.chapter.show", $chapter->manga_name. " #". $chapter->number, array($chapter->manga_id, $chapter->id), array('class' => 'product-title')) }}
                                @else
                                {{ $chapter->manga_name. " #". $chapter->number }}
                                @endif
                                <div class="pull-right">
                                    <i class="fa fa-user"></i>
                                    <small>{{ $chapter->username }}</small>
                                </div>
                                <div class="product-description">
                                    <div class="pull-right">
                                        <i class="fa fa-calendar-o"></i>
                                        <small>{{ HelperController::formateCreationDate($chapter->created_at) }}</small>
                                    </div>
                                    <em>{{ $chapter->name }}</em>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    @else
                    <p class="text-center">{{ Lang::get('messages.admin.dashboard.no-chapter') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
@endsection
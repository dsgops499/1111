<div class="row">
    <?php if (is_module_enabled('Manga')): ?>
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3>{{$statistics['manga']}}</h3>

                    <p>{{ Lang::get('messages.admin.manga-plural') }}</p>
                </div>
                <div class="icon">
                    <i class="fa fa-book"></i>
                </div>
                <a href="{{route('admin.manga.index')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>{{$statistics['chapters']}}</h3>

                    <p>Chapters</p>
                </div>
                <div class="icon">
                    <i class="fa fa-image"></i>
                </div>
                <a href="{{route('admin.manga.index')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
    <?php endif; ?>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3>{{$statistics['users']}}</h3>

                <p>Users</p>
            </div>
            <div class="icon">
                <i class="fa fa-users"></i>
            </div>
            <a href="{{route('admin.user.index')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-red">
            <div class="inner">
                <h3>{{$statistics['currentTheme']}}</h3>

                <p>Current theme</p>
            </div>
            <div class="icon">
                <i class="fa fa-tint"></i>
            </div>
            <a href="{{route('admin.settings.theme')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
</div>
<!-- /.row -->
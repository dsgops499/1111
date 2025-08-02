<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar sidebar-offcanvas">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-left image">
                @if($userCmp->avatar == 1)
                <img class="img-circle" src='{{HelperController::avatarUrl($userCmp->id)}}' alt='{{$userCmp->username}}'>
                @else
                <img class="img-circle" src="{{asset('images/placeholder.png')}}" alt='{{$userCmp->username}}'/>
                @endif
            </div>
            <div class="pull-left info">
                <p>{{ ($userCmp->name=='')?$userCmp->username:$userCmp->name }}</p>
                <a href="{{Sentinel::hasAccess('user.profile')?route('admin.settings.profile'):''}}">
                    <i class="fa fa-circle text-success"></i>
                    Online
                </a>
            </div>
        </div>
        
        {!! $sidebar !!}
    </section>
    <!-- /.sidebar -->
</aside>

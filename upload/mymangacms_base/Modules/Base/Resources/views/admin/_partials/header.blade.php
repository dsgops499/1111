<header class="main-header">
    <!-- Logo -->
    <a href="{{ route('admin.index') }}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>A</b>dm</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg">{{ Session::get('sitename') }}</span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- Notifications Menu -->
                <?php if (is_module_enabled('Notification')): ?>
                    @include('notification::partials.notifications')
                <?php endif; ?>
                <!-- Cleaner -->
                @if(Sentinel::hasAccess('settings.edit_general'))
                <li class="dropdown messages-menu">
                    <!-- Menu toggle button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-eraser"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <ul class="menu">
                                <li>
                                    <a href="#" 
                                       onclick="event.preventDefault();
                                               document.getElementById('clear-cache').submit();">
                                        {{Lang::get('messages.admin.settings.cache.clear')}}
                                    </a>
                                    {{ Form::open(array('route' => 'admin.settings.cache.clear', 'id' => 'clear-cache')) }}
                                    {{ Form::close() }}
                                </li>
                                <li>
                                    <a href="#" 
                                       onclick="event.preventDefault();
                                               document.getElementById('clear-downloads').submit();">
                                        {{Lang::get('messages.admin.settings.downloads.clear')}}
                                    </a>
                                    {{ Form::open(array('route' => 'admin.settings.downloads.clear', 'id' => 'clear-downloads')) }}
                                    {{ Form::close() }}
                                </li>
                            </ul>
                        </li>
                        <li class="footer">
                            <a href="{{route('admin.settings.cache')}}">
                                more options
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
                <!-- Front Page -->
                <li>
                    <a href="{{route('front.index')}}" title="Front Page"><i class="fa fa-home"></i></a>
                </li>
                <!-- Change Log -->
                @if(Sentinel::hasAccess('settings.edit_general'))
                <li>
                    <a href="#" title="Change Log" data-toggle="modal" data-target="#changelog">
                        <i class="fa fa-info-circle fa-fw"></i>
                    </a>
                </li>
                @endif
                <!-- logout -->
                <li>
                    <a href="{{ route('logout') }}" title="{{ Lang::get('messages.admin.layout.logout') }}">
                        <i class="fa fa-sign-out"></i> 
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</header>

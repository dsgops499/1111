<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="Administration">
        <title> 
            @if (Session::has('sitename'))
            {{ Lang::get('messages.admin.layout.site-name', array('sitename' => Session::get('sitename'))) }}
            @endif
        </title>
        <!--<link rel="shortcut icon" href="{-- get_setting('favicon', 'favicon.png') --}"/>-->

        <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/admin/AdminLTE.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/admin/skins/skin-blue.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/admin/style.css') }}">

        <script src="{{ asset('js/vendor/jquery-1.11.0.min.js') }}"></script>

        @yield('head')

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <!-- Google Font -->
        <link rel="stylesheet"
              href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>

    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">
            <!-- Main Header -->
            @include('base::admin._partials.header')

            <!-- Left side column. contains the logo and sidebar -->
            @include('base::admin._partials.sidebar')

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">

                <!-- alert messages-->
                <div class="row">
                    <div class="col-md-12">
                        @include('base::admin._partials.notifications')
                    </div>
                </div>

                <!-- Content Header (Page header) -->
                <section class="content-header">
                    @yield('breadcrumbs')
                </section>

                <!-- Main content -->
                <section class="content container-fluid">
                    @yield('content')
                </section>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->

            <!-- Main Footer -->
            @include('base::admin._partials.footer')

            <!-- Add the sidebar's background. This div must be placed
            immediately after the control sidebar -->
            <div class="control-sidebar-bg"></div>
        </div>
        <!-- ./wrapper -->

        <!-- Change Log -->
        @include('base::admin._partials.modals')

        <script src="{{ asset('js/admin/adminlte.min.js') }}"></script>
        <script src="{{ asset('js/vendor/bootstrap.min.js') }}"></script>

        @stack('js')

        @yield('js')

    </body>
</html>

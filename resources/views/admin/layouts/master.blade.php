<!DOCTYPE html>
<html class="loading" lang="{{getLocale()}}" data-textdirection="ltr">
<head>
    <meta name="csrf-token" content="{{csrf_token()}}">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">

    <title>@yield('page.name') - {{__('Admin Panel')}} | {{config('app.name')}}</title>

    <!-- BEGIN FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="{{config('app.shortcut_icon') ?: asset('/images/icon/favicon.ico')}}">
    <!-- END FAVICON -->

    <!-- BEGIN DESCRIPTION -->
    <meta name="description" content="{{config('app.description')}}">
    <!-- END DESCRIPTION -->

    <!-- BEGIN KEYWORDS -->
    <meta name="keywords" content="{{config('app.keywords')}}">
    <!-- END KEYWORDS -->

    @routes('admin')
    @stack('data')

    <!-- BEGIN FONT CSS -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Quicksand:300,400,500,700" rel="stylesheet">
    <link href="https://maxcdn.icons8.com/fonts/line-awesome/1.1/css/line-awesome.min.css" rel="stylesheet">
    <!-- END FONT CSS -->

    <!-- BEGIN VENDOR CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('css/vendors.css')}}">
    <!-- END VENDOR CSS-->

    @stack('css')

    <!-- BEGIN APPLICATION CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('css/app.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/core/menu/menu-types/vertical-menu-modern.css')}}">
    <!-- END APPLICATION CSS-->

    <!-- BEGIN PAGE CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('css/core/colors/palette-callout.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/core/colors/palette-gradient.css')}}">
    <!-- END PAGE CSS-->

    @include('admin.includes.scripts')

</head>
<body class="vertical-layout vertical-menu-modern 2-columns menu-expanded fixed-navbar"
      data-menu="vertical-menu-modern" data-col="2-columns" data-open="click">

    <div class="h-100" id="app">
        <div id="preloader">
            <div class="spinner"></div>
        </div>
        
        @include('admin.layouts.header')

        @include('admin.layouts.menu')


        <div class="app-content content">
            @yield('page.body')
        </div>

        @include('admin.layouts.footer')
    </div>

    <!-- BEGIN APPLICATION LIBRARIES -->
    <script src="{{asset('js/app.js')}}" type="text/javascript"></script>
    <!-- END APPLICATION LIBRARIES -->

{!! toastr()->render() !!}
@include('admin.includes.toastr')

<!-- BEGIN PAGE SCRIPTS -->
@stack('scripts')
<!-- END PAGE SCRIPTS -->

</body>
</html>

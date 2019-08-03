<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">

    <title>{{__('Access Denied')}} | {{config('app.name')}}</title>

    <!-- BEGIN FAVICON -->
    <link rel="shortcut icon" type="image/x-icon" href="{{config('app.shortcut_icon') ?: asset('/images/icon/favicon.ico')}}">
    <!-- END FAVICON -->

    <!-- BEGIN DESCRIPTION -->
    <meta name="description" content="{{config('app.description')}}">
    <!-- END DESCRIPTION -->

    <!-- BEGIN KEYWORDS -->
    <meta name="keywords" content="{{config('app.keywords')}}">
    <!-- END KEYWORDS -->

    <!-- BEGIN VENDOR CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('css/vendors.css')}}">
    <!-- END VENDOR CSS-->

    <!-- BEGIN APPLICATION CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('css/app.css')}}">
    <!-- END APPLICATION CSS-->

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i%7CQuicksand:300,400,500,700" rel="stylesheet">

    <!-- BEGIN PAGE CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('css/pages/error.css')}}">
    <!-- END PAGE CSS-->
</head>

<body class="vertical-layout vertical-menu 1-column   menu-expanded blank-page blank-page" data-open="click" data-menu="vertical-menu" data-col="1-column">
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-body">

                <section class="flexbox-container">
                    <div class="col-12 d-flex align-items-center justify-content-center">
                        <div class="col-md-4 col-10 p-0">

                            <div class="card-header bg-transparent border-0">
                                <h2 class="error-code text-center mb-2">
                                    403
                                </h2>
                                <h3 class="text-uppercase text-center">
                                    {{__('Access Denied!')}}
                                </h3>
                            </div>

                        </div>
                    </div>
                </section>

            </div>
        </div>
    </div>
</body>

</html>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="{{asset('vendor/installer/img/apple-icon.png')}}">
    <link rel="icon" type="image/png" href="{{asset('vendor/installer/img/favicon.png')}}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>@yield('page') | {{config('installer.name')}} by {{config('installer.author.name')}} </title>

    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />

    <!--     Fonts and icons     -->
    <link href="http://netdna.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.css" rel="stylesheet">

    <!-- CSS Files -->
    <link href="{{asset('vendor/installer/css/bootstrap.min.css')}}" rel="stylesheet" />
    <link href="{{asset('vendor/installer/css/gsdk-bootstrap-wizard.css')}}" rel="stylesheet" />

    @stack('css')

<!-- CSS Just for demo purpose, don't include it in your project -->
    <link href="{{asset('vendor/installer/css/demo.css')}}" rel="stylesheet" />
</head>

<body>
    <div class="image-container set-full-height" style="background-image: url('{{asset('vendor/installer/img/wizard.jpg')}}')">
        <!--   Creative Tim Branding   -->
        <a href="{{config('installer.author.portfolio')}}">
            <div class="logo-container">
                <div class="logo">
                    <img src="{{config('installer.author.avatar')}}" width="60" height="60">
                </div>
                <div class="brand">
                    <p><strong>{{config('installer.author.name')}}</strong> <br/> <small>{{__('Developer')}}</small></p>
                </div>
            </div>
        </a>

        <!--  Made With Material Kit  -->
        <a href="{{config('installer.link')}}" class="made-with-mk">
            <div class="brand"><i class="fa fa-shopping-cart"></i></div>
            <div class="made-with">{{__('Purchase Now')}}</div>
        </a>

        <!--   Big container   -->
        <div class="container">
            <div class="row">
                <div class="col-sm-8 col-sm-offset-2">
                    <!-- Wizard container  -->
                    <div class="wizard-container">
                        <div class="card wizard-card" data-color="orange" id="wizardProfile">
                            @yield('content')
                        </div>
                    </div> <!-- wizard container -->
                </div>
            </div><!-- end row -->
        </div> <!--  big container -->


        <div class="footer">
            <div class="container">
                &copy{{\Carbon\Carbon::now()->year}} {{__('Official Script Installation Wizard')}} {{__('by')}} <a href="{{config('installer.link')}}">{{config('installer.author.name')}}</a></a>
            </div>
        </div>

    </div>
</body>

<!--   Core JS Files   -->
<script src="{{asset('vendor/installer/js/jquery-2.2.4.min.js')}}" type="text/javascript"></script>
<script src="{{asset('vendor/installer/js/bootstrap.min.js')}}" type="text/javascript"></script>
<script src="{{asset('vendor/installer/js/jquery.bootstrap.wizard.js')}}" type="text/javascript"></script>
<!--  Plugin for the Wizard -->
<script src="{{asset('vendor/installer/js/gsdk-bootstrap-wizard.js')}}"></script>
<!--  More information about jquery.validate here: http://jqueryvalidation.org/	 -->
<script src="{{asset('vendor/installer/js/jquery.validate.min.js')}}"></script>
@stack('js')
</html>

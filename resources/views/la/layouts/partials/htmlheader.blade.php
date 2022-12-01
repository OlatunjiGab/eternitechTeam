<head>
    @if(Entrust::hasRole("SUPER_ADMIN"))
    <!--Snippet Code For create Task-->
    <script type="text/javascript">
        var dttPluginData = {"token":"U0RRMmIwSTRjMDVJTUdwaFZDOVlRbU5hVFhKWVFUMDlPam82T2tYdVhZejBqR2JlNG9MUGxXTmFLTzQ9","isType":1,"team":"web"};
        (function(w,d,s,j,r,a,m){w["DttObjects"]=r;w[r]=w[r]||function(){(w[r].q=w[r].q||[])[(arguments[0]||"")]=(arguments[1]||"")},w[r].l=1*new Date();if(d.getElementById(r)){return;}a=d.createElement(s),m=d.getElementsByTagName(s)[0];a.id=r;a.async=1;a.src=j;m.parentNode.insertBefore(a,m)})(window,document,"script","https://s3.us-east-1.amazonaws.com/media.dothattask.com/snippet/main.js","dtto");
        dtto("config", dttPluginData);
    </script>
    @endif
    <meta charset="UTF-8">
    <title>@hasSection('htmlheader_title')@yield('htmlheader_title') - @endif{{ LAConfigs::getByKey('sitename') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon" />
    <!-- Bootstrap 3.3.4 -->
    <link href="{{ asset('la-assets/css/bootstrap.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('la-assets/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('la-assets/css/fa-regular.min.css') }}" rel="stylesheet" type="text/css" />
    <!--<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />-->
    <link href="{{ asset('la-assets/css/ionicons.min.css') }}" rel="stylesheet" type="text/css" />
    <!--<link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />-->

    <!-- Theme style -->
    <link href="{{ asset('la-assets/css/AdminLTE.css') }}" rel="stylesheet" type="text/css" />

    <!--
        AdminLTE Skins. We have chosen the skin-blue for this starter
            page. However, you can choose any other skin. Make sure you
        apply the skin class to the body tag so the changes take effect.
    -->

    <link href="{{ asset('la-assets/css/skins/'.LAConfigs::getByKey('skin').'.css') }}" rel="stylesheet" type="text/css" />
    <!-- iCheck -->
    <link href="{{ asset('la-assets/plugins/iCheck/square/blue.css') }}" rel="stylesheet" type="text/css" />
    <!-- wysihtml5 editor -->
    <link href="{{ asset('la-assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="https://www.jquery-az.com/jquery/css/intlTelInput/intlTelInput.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/4.1.5/css/flag-icons.css" integrity="sha512-Sq1qdqbXHnQ3rmftdNCVwU83vZtDzIWc0HVPj6D358xGpXAiFL0/U3KS9KE3bQdxL4Ndk4GEdIsBGOwvqWmikw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    @stack('styles')

    <script type="text/javascript"> var projectUrl = "{{url('/')}}" </script>

    @include('la.layouts.partials.header-tracking')
</head>

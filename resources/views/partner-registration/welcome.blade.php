<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ LAConfigs::getByKey('site_description') }}">
    <meta name="author" content="Dwij IT Solutions">
    <meta name="_token" content="{{csrf_token()}}">
    <link rel="icon" type="image/png" href="{{ asset('/la-assets/welcome_favicon.ico') }}"/>
    <meta property="og:title" content="{{ LAConfigs::getByKey('sitename') }}" />
    <meta property="og:type" content="website" />
    <meta property="og:description" content="{{ LAConfigs::getByKey('site_description') }}" />
    
    <meta property="og:url" content="http://laraadmin.com/" />
    <meta property="og:sitename" content="laraAdmin" />
	<meta property="og:image" content="http://demo.adminlte.acacha.org/img/LaraAdmin-600x600.jpg" />
    
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:site" content="@laraadmin" />
    <meta name="twitter:creator" content="@laraadmin" />
    
    <title>{{ LAConfigs::getByKey('sitename') }}</title>
    
    <!-- Bootstrap core CSS -->
    <link href="{{ asset('/la-assets/css/bootstrap.css') }}" rel="stylesheet">

	<link href="{{ asset('la-assets/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
    
    <!-- Custom styles for this template -->
    <link href="{{ asset('/la-assets/css/main.css') }}" rel="stylesheet">

    <link href='https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Raleway:400,300,700' rel='stylesheet' type='text/css'>

    <script src="{{ asset('/la-assets/plugins/jQuery/jQuery-2.1.4.min.js') }}"></script>
    <script src="{{ asset('/la-assets/js/smoothscroll.js') }}"></script>

    @include('la.layouts.partials.header-tracking')
</head>

<body data-spy="scroll" data-offset="0" data-target="#navigation">

<!-- Fixed navbar -->
<div id="navigation" class="navbar navbar-default navbar-fixed-top" style="min-height: 80px;">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="https://eternitech.com/"><img src="//eternitech.com/wp-content/uploads/2016/10/logo@2x.png" style="width: 50%;"></a>
        </div>
        <div class="navbar-collapse collapse">
                     
        </div><!--/.nav-collapse -->
    </div>
</div>


<section id="home" name="home" style="margin-top: 130px; margin-bottom:250px;">
    <div class="container">
        <div class="jumbotron text-center">
          <h1 class="display-3">Thank You!</h1>
          <p>Thanks for registering with us, we will review your application and get back to you shortly.</p>   
          <p>Check your email, we have send you your Partner CRM access details.</p>
          <hr>          
          <p class="lead">
            <a class="btn btn-primary btn-sm homepage-btn" href="https://crm.eternitech.com/login" role="button">Continue to homepage</a>
          </p>
        </div>
    </div>    
</section>

<div id="c">
    <div class="container">
         <p>
            <strong>Copyright &copy; {{date('Y')}}. Powered by <a href="https://eternitech.com"><b>Eternitech.com</b></a>
        </p>
    </div>
</div>


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="{{ asset('/la-assets/js/bootstrap.min.js') }}" type="text/javascript"></script>
<script>
    $('.carousel').carousel({
        interval: 3500
    });
    $(document).ready(function() {
        setTimeout(function(){
            jQuery.ajax({
                url: "{{ url('/partner-login') }}",
                method: 'post',
                data: {userId: "{{Session::get('userId')}}"},
                success: function(result){
                    document.location.href="/";
                }
            });
        }, 5000);
        $("a.homepage-btn").click(function(event){
            event.preventDefault();
            jQuery.ajax({
                url: "{{ url('/partner-login') }}",
                method: 'post',
                data: {userId: "{{Session::get('userId')}}"},
                success: function(result){
                    document.location.href="/";
                }
            });
        });
    });
</script>
</body>
</html>
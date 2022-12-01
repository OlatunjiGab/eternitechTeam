<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ LAConfigs::getByKey('site_description') }}">
    <meta name="author" content="Eternitech IT Solutions">
    <meta name="_token" content="{{csrf_token()}}">
    <link rel="icon" type="image/png" href="{{ asset('/la-assets/welcome_favicon.ico') }}"/>
    <meta property="og:title" content="{{ LAConfigs::getByKey('sitename') }}" />
    <meta property="og:type" content="website" />
    <meta property="og:description" content="{{ LAConfigs::getByKey('site_description') }}" />
    
    <meta property="og:url" content="https://eternitech.com/" />
    <meta property="og:sitename" content="Eternitech" />
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
    <link href="https://www.jquery-az.com/jquery/css/intlTelInput/intlTelInput.css" rel="stylesheet">
    <style type="text/css">
        #c{
            width: 100%;
            position:fixed;
            bottom:0;
        }
    </style>

    <script src="{{ asset('/la-assets/plugins/jQuery/jQuery-2.1.4.min.js') }}"></script>
    <script src="{{ asset('/la-assets/js/smoothscroll.js') }}"></script>

    @include('la.layouts.partials.header-tracking')
</head>

<body data-spy="scroll" data-offset="0" data-target="#navigation" style="font-family: arial !important;">

<!-- Fixed navbar -->
<div id="navigation" class="navbar navbar-default navbar-fixed-top" style="min-height: 80px;">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href=""><img src="//eternitech.com/wp-content/uploads/2016/10/logo@2x.png" style="width: 50%;"></a>
        </div>
        <div class="navbar-collapse collapse">
                     
        </div><!--/.nav-collapse -->
    </div>
</div>


<section id="home" name="home" style="margin-top: 80px; margin-bottom:80px;">
    <div class="container">
        <div class="jumbotron text-center">
          <h2 class="display-3" style="direction: {{\App\Models\Language::isRTL($project->language) ? 'rtl' : 'ltr'}}"> <span class="fa fa-whatsapp text-success "></span> {{$heading}} </h2>
            <p style="direction: {{\App\Models\Language::isRTL($project->language) ? 'rtl' : 'ltr'}}"> Enter the access code you received for {{$channel}}:</p>
            <form action="{{ url('/set-otp/'.$key) }}" method="post">
                <div class="row">
                    <div class="col-lg-3 col-md-3"></div>
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-group has-feedback">
                            <input type="text" class="form-control" placeholder="Access Code" name="code" value="{{old('code')}}" />
                            <span class="glyphicon glyphicon-phone form-control-feedback"></span>
                            @if ($errors->has('code'))
                                <span class="text-danger"><strong>{{ $errors->first('code') }}</strong></span>
                            @endif
                        </div>

                        <div class="row">
                            <div class="col-xs-12">
                                <button type="submit" class="btn btn-primary btn-flat">Submit</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3"></div>
                </div>
            </form>
        </div>
    </div>    
</section>

<div id="c">
    <div class="container">
         <p>
             <strong>Copyright &copy; {{date('Y')}}. Powered by <a href=""><b>Eternitech.com</b></a></strong>
        </p>
    </div>
</div>


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="{{ asset('/la-assets/js/bootstrap.min.js') }}" type="text/javascript"></script>
</body>
</html>
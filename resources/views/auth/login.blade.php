@extends('la.layouts.auth')

@section('htmlheader_title')
    Log in
@endsection

@section('content')
<style>
    .login-box-body-out {
        padding: 20px;
        border-top: 0;
        color: #666;
    }
</style>
<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href="{{ MAIN_SITE }}"><img src="/la-assets/img/eternitech_logo.png" alt="Eternitech logo" width="350px"></a>
            {{--<a href="{{ url('/home') }}"><b>{{ LAConfigs::getByKey('sitename_part1') }} </b>{{ LAConfigs::getByKey('sitename_part2') }}</a>--}}
            <br/>
            <a href="{{ MAIN_SITE }}"><b>Partners</b> Network</a>
        </div>

    <div class="login-box-body-out">
    <div class="login-about">Welcome to Eternitech's global network of thousands of Technological and Digital agencies around the world, exchanging leads and supporting each other's projects.</div>
    </div>

    <div class="login-box-body">
    @foreach ($errors->all() as $error)
        @if($error == "These credentials do not match our records.")
        <p class="login-about alert alert-danger"> {{ $error }} </p>
        @else
        <p class="login-about"> {{ $error }} </p>
        @endif
    @endforeach
        <p class="login-box-msg"><b>Login</b></p>
    <form action="{{ url('/login') }}" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="form-group has-feedback">
            <input type="email" class="form-control" placeholder="Email" name="email"/>
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
            <input type="password" class="form-control" placeholder="Password" name="password"/>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="row">
            <div class="col-xs-8">
                <div class="checkbox icheck">
                    <label>
                        <input type="checkbox" name="remember"> Remember Me
                    </label>
                </div>
            </div><!-- /.col -->
            <div class="col-xs-4">
                <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
            </div><!-- /.col -->
        </div>
    </form>

    @include('auth.partials.social_login')

    <a href="{{ url('/password/reset') }}">I forgot my password</a>
        <br><br><br>
        <p class="login-box-msg"><b>Not a Registered Partner? <a href="{{ url(MAIN_SITE . PARTNERS_LANDING_PAGE) }}" class="text-center">Register Now</a></b></p>

</div><!-- /.login-box-body -->

</div><!-- /.login-box -->

    @include('la.layouts.partials.scripts_auth')

    <script>
        $(function () {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
            @if(request()->get('is_not_login'))
            /*setTimeout(function(){
                document.location.href="{{ url(MAIN_SITE . PARTNERS_LANDING_PAGE) }}";
            }, 5000);*/
            @endif
        });
    </script>
</body>

@endsection

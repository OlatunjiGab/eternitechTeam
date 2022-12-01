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
        h1, h2, h3, h4, h5, h6 {
            font-family: "Lato" !important;
        }
        p {
            margin-bottom: 0;
        }
        label{
            margin-bottom: 0;
        }
        .mt-5{
            margin-top: 5px;
        }
        .panel-primary > .panel-heading {
            color: #fff;
            background-color: #64a5d7;
            border-color: #64a5d7;
        }
        .panel-footer {
            padding: 10px 15px;
            background-color: #64a5d7;
            border-top: 1px solid #64a5d7;
        }
        .text-bold {
            font-weight: bold;
        }
        .label-primary {
            background-color: #64a5d7;
        }
    </style>

    <script src="{{ asset('/la-assets/plugins/jQuery/jQuery-2.1.4.min.js') }}"></script>
    <script src="{{ asset('/la-assets/js/smoothscroll.js') }}"></script>

    @include('la.layouts.partials.header-tracking')
</head>

<body data-spy="scroll" data-offset="0" data-target="#navigation">

<!-- Fixed navbar -->
<div id="navigation" class="navbar navbar-default navbar-fixed-top" style="min-height: 80px;">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="https://eterni.tech/?projectID={{$projectID}}"><img src="//eternitech.com/wp-content/uploads/2016/10/logo@2x.png" style="width: 50%;"></a>
        </div>
        <div class="navbar-collapse collapse">
                     
        </div><!--/.nav-collapse -->
    </div>
</div>


<section id="home" name="home" style="margin-top: 80px; margin-bottom:80px;">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading text-center"><h2 class="text-white text-bold ">Lead's Details</h2></div>
            <div class="panel-body">
                <p><label>Lead No :</label> {{$id}}</p>
                <p><label> Lead Name :</label></p>
                <p>{{$projectName}}</p>
                <p class="mt-5"><label> Description :</label></p>
                <p> {{$projectDescription}}</p>
                @if(!empty($skills))
                <p class="mt-5"><label> Skills :</label></p>
                <p>
                    @foreach(explode(', ',$skills) as $key => $skill)
                    <span class="label label-primary">{{$skill}}</span>
                    @endforeach
                </p>
                @endif
                <p class="mt-5"><label> Price :</label></p>
                <p>
                    <span class="label label-primary">${{$price}}</span> {{$isHourly}}
                </p>
            </div>
            <div class="panel-footer text-center">
                <a href="https://eternitech.com/partners" target="_blank" class="btn btn-default">Login/Sign Up to see full details</a>
            </div>
        </div>
    </div>    
</section>

<div id="c">
    <div class="container">
         <p>
             <strong>Copyright &copy; {{date('Y')}}. Powered by <a href="https://eterni.tech/?projectID={{$projectID}}"><b>Eternitech.com</b></a></strong>
        </p>
    </div>
</div>


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="{{ asset('/la-assets/js/bootstrap.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('la-assets/js/intlTelInput.js') }}"></script>
<script>
    $('.carousel').carousel({
        interval: 3500
    });
    let phoneNumber = document.getElementsByName('phone');
    $(phoneNumber).intlTelInput({
        nationalMode: false,
        initialCountry: "auto",
        geoIpLookup: function(success, failure) {
            $.get("http://ip-api.com/json/{{Request::ip()}}", function() {}, "jsonp").always(function(resp) {
                var countryCode = (resp && resp.countryCode) ? resp.countryCode : "";
                if(countryCode) {
                    success(countryCode);
                } else {
                    success('us');
                }
            });
        },
    });
    (function (window) {
        {
            var unknown = '-';

            // screen
            var screenSize = '';
            if (screen.width) {
                width = (screen.width) ? screen.width : '';
                height = (screen.height) ? screen.height : '';
                screenSize += '' + width + " x " + height;
            }

            // browser
            var nVer = navigator.appVersion;
            var nAgt = navigator.userAgent;
            var browser = navigator.appName;
            var version = '' + parseFloat(navigator.appVersion);
            var majorVersion = parseInt(navigator.appVersion, 10);
            var nameOffset, verOffset, ix;

            // Opera
            if ((verOffset = nAgt.indexOf('Opera')) != -1) {
                browser = 'Opera';
                version = nAgt.substring(verOffset + 6);
                if ((verOffset = nAgt.indexOf('Version')) != -1) {
                    version = nAgt.substring(verOffset + 8);
                }
            }
            // Opera Next
            if ((verOffset = nAgt.indexOf('OPR')) != -1) {
                browser = 'Opera';
                version = nAgt.substring(verOffset + 4);
            }
            // Legacy Edge
            else if ((verOffset = nAgt.indexOf('Edge')) != -1) {
                browser = 'Microsoft Legacy Edge';
                version = nAgt.substring(verOffset + 5);
            }
            // Edge (Chromium)
            else if ((verOffset = nAgt.indexOf('Edg')) != -1) {
                browser = 'Microsoft Edge';
                version = nAgt.substring(verOffset + 4);
            }
            // MSIE
            else if ((verOffset = nAgt.indexOf('MSIE')) != -1) {
                browser = 'Microsoft Internet Explorer';
                version = nAgt.substring(verOffset + 5);
            }
            // Chrome
            else if ((verOffset = nAgt.indexOf('Chrome')) != -1) {
                browser = 'Chrome';
                version = nAgt.substring(verOffset + 7);
            }
            // Safari
            else if ((verOffset = nAgt.indexOf('Safari')) != -1) {
                browser = 'Safari';
                version = nAgt.substring(verOffset + 7);
                if ((verOffset = nAgt.indexOf('Version')) != -1) {
                    version = nAgt.substring(verOffset + 8);
                }
            }
            // Firefox
            else if ((verOffset = nAgt.indexOf('Firefox')) != -1) {
                browser = 'Firefox';
                version = nAgt.substring(verOffset + 8);
            }
            // MSIE 11+
            else if (nAgt.indexOf('Trident/') != -1) {
                browser = 'Microsoft Internet Explorer';
                version = nAgt.substring(nAgt.indexOf('rv:') + 3);
            }
            // Other browsers
            else if ((nameOffset = nAgt.lastIndexOf(' ') + 1) < (verOffset = nAgt.lastIndexOf('/'))) {
                browser = nAgt.substring(nameOffset, verOffset);
                version = nAgt.substring(verOffset + 1);
                if (browser.toLowerCase() == browser.toUpperCase()) {
                    browser = navigator.appName;
                }
            }
            // trim the version string
            if ((ix = version.indexOf(';')) != -1) version = version.substring(0, ix);
            if ((ix = version.indexOf(' ')) != -1) version = version.substring(0, ix);
            if ((ix = version.indexOf(')')) != -1) version = version.substring(0, ix);

            majorVersion = parseInt('' + version, 10);
            if (isNaN(majorVersion)) {
                version = '' + parseFloat(navigator.appVersion);
                majorVersion = parseInt(navigator.appVersion, 10);
            }

            // mobile version
            var mobile = /Mobile|mini|Fennec|Android|iP(ad|od|hone)/.test(nVer);

            var ua = navigator.userAgent;
            if (/(tablet|ipad|playbook|silk)|(android(?!.*mobi))/i.test(ua)) {
                var deviceType = "tablet";
            } else if (/Mobile|iP(hone|od)|Android|BlackBerry|IEMobile|Kindle|Silk-Accelerated|(hpw|web)OS|Opera M(obi|ini)/.test(ua)) {
                var deviceType = "mobile";
            } else {
                var deviceType = "desktop";
            }

            // cookie
            var cookieEnabled = (navigator.cookieEnabled) ? true : false;

            if (typeof navigator.cookieEnabled == 'undefined' && !cookieEnabled) {
                document.cookie = 'testcookie';
                cookieEnabled = (document.cookie.indexOf('testcookie') != -1) ? true : false;
            }

            // system
            var os = unknown;
            var clientStrings = [
                {s:'Windows 10', r:/(Windows 10.0|Windows NT 10.0)/},
                {s:'Windows 8.1', r:/(Windows 8.1|Windows NT 6.3)/},
                {s:'Windows 8', r:/(Windows 8|Windows NT 6.2)/},
                {s:'Windows 7', r:/(Windows 7|Windows NT 6.1)/},
                {s:'Windows Vista', r:/Windows NT 6.0/},
                {s:'Windows Server 2003', r:/Windows NT 5.2/},
                {s:'Windows XP', r:/(Windows NT 5.1|Windows XP)/},
                {s:'Windows 2000', r:/(Windows NT 5.0|Windows 2000)/},
                {s:'Windows ME', r:/(Win 9x 4.90|Windows ME)/},
                {s:'Windows 98', r:/(Windows 98|Win98)/},
                {s:'Windows 95', r:/(Windows 95|Win95|Windows_95)/},
                {s:'Windows NT 4.0', r:/(Windows NT 4.0|WinNT4.0|WinNT|Windows NT)/},
                {s:'Windows CE', r:/Windows CE/},
                {s:'Windows 3.11', r:/Win16/},
                {s:'Android', r:/Android/},
                {s:'Open BSD', r:/OpenBSD/},
                {s:'Sun OS', r:/SunOS/},
                {s:'Chrome OS', r:/CrOS/},
                {s:'Linux', r:/(Linux|X11(?!.*CrOS))/},
                {s:'iOS', r:/(iPhone|iPad|iPod)/},
                {s:'Mac OS X', r:/Mac OS X/},
                {s:'Mac OS', r:/(Mac OS|MacPPC|MacIntel|Mac_PowerPC|Macintosh)/},
                {s:'QNX', r:/QNX/},
                {s:'UNIX', r:/UNIX/},
                {s:'BeOS', r:/BeOS/},
                {s:'OS/2', r:/OS\/2/},
                {s:'Search Bot', r:/(nuhk|Googlebot|Yammybot|Openbot|Slurp|MSNBot|Ask Jeeves\/Teoma|ia_archiver)/}
            ];
            for (var id in clientStrings) {
                var cs = clientStrings[id];
                if (cs.r.test(nAgt)) {
                    os = cs.s;
                    break;
                }
            }

            var osVersion = unknown;

            if (/Windows/.test(os)) {
                osVersion = /Windows (.*)/.exec(os)[1];
                os = 'Windows';
            }

            switch (os) {
                case 'Mac OS':
                case 'Mac OS X':
                case 'Android':
                    osVersion = /(?:Android|Mac OS|Mac OS X|MacPPC|MacIntel|Mac_PowerPC|Macintosh) ([\.\_\d]+)/.exec(nAgt)[1];
                    break;

                case 'iOS':
                    osVersion = /OS (\d+)_(\d+)_?(\d+)?/.exec(nVer);
                    osVersion = osVersion[1] + '.' + osVersion[2] + '.' + (osVersion[3] | 0);
                    break;
            }

            // flash (you'll need to include swfobject)
            /* script src="//ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js" */
            var flashVersion = 'no check';
            if (typeof swfobject != 'undefined') {
                var fv = swfobject.getFlashPlayerVersion();
                if (fv.major > 0) {
                    flashVersion = fv.major + '.' + fv.minor + ' r' + fv.release;
                }
                else  {
                    flashVersion = unknown;
                }
            }
        }
        window.jscd = {
            screen: screenSize,
            browser: browser,
            browserVersion: version,
            browserMajorVersion: majorVersion,
            mobile: mobile,
            deviceType: deviceType,
            os: os,
            osVersion: osVersion,
            cookies: cookieEnabled,
            flashVersion: flashVersion
        };
    }(this));

    $("#browserName").val(jscd.browser);
    $("#browserVersion").val(jscd.browserMajorVersion);
    $("#osName").val(jscd.os);
    $("#osVersion").val(jscd.osVersion);
    $("#deviceType").val(jscd.deviceType);
</script>
</body>
</html>

<p><h3>{{$channel}} requested a OTP</h3></p>

<p>To be able to automate your {{$channel}} channel - we need access code</p>
<p>You received an Email / Text message from {{$channel}} with the latest access code.</p>
<p>Please look for the access code you have received - copy it and enter it here:</p>
<p><a href="{{ \App\Helpers\ShortLink::SHORTLINK_BASE_DOMAIN . '/get-otp/' . $key }}">Enter Access code here</a></p>


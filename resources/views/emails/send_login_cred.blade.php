<header style="margin-bottom:2%;padding-top: 2%;padding-bottom: 2%;">
	<center><img src="{{ url('/la-assets/eterni_logo.png') }}"></center>
</header>
Dear {{ $user->name }},<br><br>

Thanks for registering with us,<br>

Congratulations! You have been successfully registered on {{ url('/') }}.<br><br>


Your login credentials are as below:<br><br>

Username: {{ $user->email }}<br>
Password: {{ $password }}<br><br>

You can login on {{ url('/login') }}.<br><br>

Best Regards,<br>
Team Eternitech

<footer style="margin-top:3%;padding-top: 2%;padding-bottom: 2%;"></footer>

Dear {{ $toUser->name }},<br><br>
<p>Email reply from user</p>
<p><strong>Name: </strong>{{$fromUser->name}}</p>
<p><strong>Email: </strong>{{$fromUser->email}}</p>
<p><strong>Subject: </strong>{{$subject}}</p>
<p><strong>Reply Message: </strong>{{$replyText}}</p>

Best Regards,<br>
Team Eternitech<br>
<footer style="margin-top:3%;padding-top: 2%;padding-bottom: 2%;">
    <center><img src="{{ url('/la-assets/eterni_logo.png') }}"></center>
</footer>
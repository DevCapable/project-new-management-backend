
Hello {{$emailData['name']}}
<br><br>
Welcome to
<b style="color: green">{{env('APP_NAME')}}</b>
<br>
Please click the below link to verify your email and activate your account!
<br><br>
<a href="http://127.0.0.1:8000/verify?code={{$emailData['verification_code']}}">Click Here!</a>

<br><br>
Thank you!
<br>
still under dev





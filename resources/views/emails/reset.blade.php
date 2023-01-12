<div style="backgound-color: #1b1b1b">
    <img src="{{url('images/web-asset/logo/D_N-Logo.png')}}" alt="" style="width: 100px;">

    <h5>{{$details['title']}}</h5>

    <blockquote class="blockquote-footer text-info">
        <strong>Halo, <br> <strong><b><i>{{$details['username']}}</i></b></strong> silahkan reset password kamu melalui link berikut ini :
    </blockquote>

    <br><br><br>
    <p>
        Reset Password  Account : {{ $details['username'] }}<br>
        <br>
        <small style="color: red; margin-bottom: 1rem;">Please click link below!</small>
        <a href="{{ env('FRONTEND_APP_TEST')}}/auth/reset/{{$details['token']}}" class="btn btn-info btn-block">Reset Password</a>
    </p>
</div>

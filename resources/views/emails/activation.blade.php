<div style="backgound-color: #1b1b1b">
    <img src="{{url('images/web-asset/logo/D_N-Logo.png')}}" alt="" style="width: 100px;">

    <h5>{{$details['title']}}</h5>

    <blockquote class="blockquote-footer text-info">
        <strong>Halo, <br> <strong><b><i>{{$details['username']}}</i></b></strong> silahkan aktivasi akun kamu di link berikut ini :
    </blockquote>

    <br><br><br>
    <p>
        Aktivasi  akun : {{ $details['username'] }} di sini <br>

        <br>
        <a href="{{ env('FRONTEND_APP')}}/activated/user/{{$details['token']}}" class="btn btn-info btn-block">Aktivasi Member</a>
    </p>
</div>

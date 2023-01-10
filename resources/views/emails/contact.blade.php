<div style="background-color: #000; color: #fff;">

    <center>
        <img src="{{url('images/web-asset/logo/D_N-Logo.png')}}" alt="" style="width: 100px;">
        <h5 style="margin-top: 2rem; margin-bottom: 2rem;">{{$details['title']}}</h5>
    </center>


        <blockquote class="blockquote-footer text-info">
            <strong>Halo, {{$details['roles']}} {{env('APP_BRAND')}}<br> <strong><b><i>{{$details['name']}}</i></b> dengan email : {{$details['email']}}</strong>, baru saja mengirim pesan melalui website:
        </blockquote>

        <br><br><br>
        <p>Detail Message  :  </p>
        <ul style="background-color: rgba(252, 252, 252, 0.1); color:#fff;">
            <li>Nama  : {{$details['name']}}</li>
            <li>Email : {{$details['email']}}</li>
            <li>Phone : {{$details['phone']}}</li>
            <li>Messagee : <br>
                {{$details['message']}}
            </li>
        </ul>

        <br>

        <p>
            Respon pesan dari : {{ $details['name'] }} di sini <br>

            <br>
            <a href="{{ env('FRONTEND')}}dashboard/{{$details['roles']}}/{{$details['route']}}/contact" class="btn btn-info btn-block">Response Message</a>
        </p>
</div>

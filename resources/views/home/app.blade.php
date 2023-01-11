
    @extends('layouts.global')
    @section('title'){{ $seo['title'] }}@endsection
    {{-- Meta Seo Tag --}}
    @section('canonical'){{ $seo['canonical'] }}@endsection
    @section('meta_desc'){{ $seo['meta_desc'] }}@endsection
    @section('meta_key'){{ $seo['meta_key'] }}@endsection
    @section('meta_author'){{ $seo['meta_author'] }}@endsection
    @section('og_url'){{ $seo['og_url'] }}@endsection
    @section('og_type'){{ $seo['og_type'] }}@endsection
    @section('og_site_name'){{ $seo['og_site_name'] }}@endsection
    @section('og_title'){{ $seo['og_title'] }}@endsection
    @section('og_desc'){{ $seo['og_desc'] }}@endsection
    @section('og_image'){{ $seo['og_image'] }}@endsection
    @section('og_image_width'){{ $seo['og_image_width'] }}@endsection
    @section('og_image_height'){{ $seo['og_image_height'] }}@endsection


    @section('content')


        <div id="app">
            <app></app>
        </div>


        <script>
            var context = {!! json_encode($context) !!}
        </script>

        <script src="{{ mix('js/app.js') }}" type="text/javascript"></script>
    @endsection

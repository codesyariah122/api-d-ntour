<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">
{{-- Meta Tag Seo --}}
<link rel="canonical" href="@yield('canonical')" />
<meta name="description" content="@yield('meta_desc')">
<meta name="keywords" content="@yield('meta_key')">
<meta name="author" content="@yield('meta_author')">
<meta property="og:url" content="@yield('og_url')">
<meta property="og:type" content="@yield('og_type')" />
<meta property="og:site_name" content="@yield('og_site_name')" />
<meta property="og:title" content="@yield('og_title')">
<meta property="og:description" content="@yield('og_desc')">
<meta property="og:image" content="@yield('og_image')">
<meta property="og:image:width" content="@yield('og_image_width')" />
<meta property="og:image:height" content="@yield('og_image_height')" />

<title>@yield('title')</title>

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="FSU Digitization Academy">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="shortcut icon" type="image/x-icon" href="{{ mix('images/logo/favicon.png') }}">

    <link rel="stylesheet" href="{{ mix('css/app.css') }}">

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-XBKJG01XTP"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-XBKJG01XTP');
    </script>
</head>
<body id="main-wrapper" class="sticky-header">
<!-- BACK TO TOP -->
<a
    href="#main-wrapper"
    id="backto-top"
    class="back-to-top"
    type="button"
    aria-label="back to top"
    aria-hidden="true"
    tabindex="-1"
>
    <i class="far fa-angle-double-up"></i>
</a>
@include('partials.notices')
    <!--=====================================-->
    <!--=             header               	=-->
    <!--=====================================-->
    <x-header-navbar/>
    <!--=====================================-->
    <!--=             Content             	=-->
    <!--=====================================-->
    {{ $slot }}

    <!--=====================================-->
    <!--=        Footer                  	=-->
    <!--=====================================-->
    <x-footer />

    <!--=====================================-->
    <!--=       Right slider mega menu      =-->
    <!--=====================================-->
    @auth
        <x-aside-menu />
    @endauth
@include('sweetalert::alert')
<script src="{{ mix('js/app.js') }}"></script>
@stack('scripts')
</body>
</html>

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Trattoria') }}</title>
    <link rel="shortcut icon" href="{{asset('favicon.png')}}" type="image/x-icon" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, viewport-fit=cover" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <meta http-equiv="x-ua-compatible" content="IE=edge" />
<!--    <link rel="stylesheet" href="{{asset('style/style.css')}}?v={{config('app.version')}}">-->
    <link rel="stylesheet" href="{{asset('style/style.css')}}?v=1.67">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="{{asset('script/jquery-3.5.1.js')}}"></script>
    <script src="{{asset('script/jquery.maskedinput.min.js')}}"></script>
    <script src="{{asset('script/rolldate.min.js')}}"></script>
<!--    <script src="{{asset('script/script.js')}}?v={{config('app.version')}}"></script>-->
    <script src="{{asset('script/script.js')}}?v=1.67"></script>
    <script src="{{asset('script/maps.js')}}"></script>

    @if(!Cookie::has('geos'))
        <script>
            geoFindMe();
        </script>
    @endif
</head>
<body>
    @include('partials.header')
    @yield('content')
    @include('partials.footer')

    @include('partials.modals.message_splash')
    <div class="modal_block modal_add_product_success">
        <span class="close_modal"></span>
        <p class="success_text">{{trans('main.product_add')}} <a href="{{route('order.index')}}" class="cart_link">{{trans('main.in_cart')}}</a>!</p>
    </div>
</body>
</html>

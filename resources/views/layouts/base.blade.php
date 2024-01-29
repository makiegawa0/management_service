<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="robots" content="noindex">

    {{-- @include('layouts.partials.favicons') --}}

    <title>
        @hasSection('title')
            @yield('title') |
        @endif
        {{ config('app.name') }}
    </title>

    <!-- Fonts -->
    {{-- <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet"> --}}

    <!-- Styles -->
    <link href="{{ asset('css/fontawesome-all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('app.css?id=b959b8da49c1c039aff0') }}" rel="stylesheet">
    <link href="{{ asset('css/jquery-ui.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

    {{-- <link href="/css/style.css?v=20200803" rel="stylesheet"> --}}
    {{-- <link href="/css/style_add.css?v=20200803" rel="stylesheet"> --}}
    {{-- <link href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" rel="stylesheet"/> --}}

    {{-- <link href="{{ asset('css/admin.css'.'?v=20220402') }}" rel="stylesheet"> --}}

    <!-- Scripts -->
    {{-- <script src="{{ asset('js/jquery-3.5.0.min.js') }}" defer></script> --}}


    {{-- <script src="{{ asset('js/app.js') }}" defer></script> --}}
    {{-- <script src="{{ asset('js/bootstrap.min.js') }}" defer></script> --}}

    {{-- <script src="{{ asset('js/popper.min.js') }}"></script> --}}

    {{-- <script src="{{ asset('js/input.js') }}?v=20220719" defer></script> --}}
    {{-- <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script> --}}   
    {{-- <script src="{{ asset('js/admin.js'.'?v=20220331') }}" defer></script> --}}
    <script src="{{ asset('js/datepicker/'.__('app.js_datepicker').'?v=20231220') }}" defer></script>
    <script src="{{ asset('js/custom.js'.'?v=20220331') }}" defer></script>
    <script src="{{ asset('js/jquery-ui.min.js') }}" defer></script>

    @stack('css')

</head>
<body>

@yield('htmlBody')

<script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>

<script>
    $('.sidebar-toggle').click(function (e) {
        e.preventDefault();
        toggleElements();
    });

    function toggleElements() {
        $('.sidebar').toggleClass('d-none');
    }
</script>

@stack('js')

</body>
</html>

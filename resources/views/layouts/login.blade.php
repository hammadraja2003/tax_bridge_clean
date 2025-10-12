<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description"
        content="Secureism Invoicing Management System - Manage invoices, clients, and FBR compliance efficiently.">
    <title>{{ config('app.name', 'Secureism | Invoicing Management System') }}</title>
    <link rel="icon" href="{{ asset('assets/images/logo/favicon_sec.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('assets/images/logo/favicon_sec.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('assets/vendor/animation/animate.min.css') }}">
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/style_theme_login.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- latest jquery-->
    <script src="{{ asset('assets/js/jquery-3.6.3.min.js') }}"></script>
</head>

<body>
    {{-- @include('layouts.partials.errors') --}}
    @yield('content')
    @include('layouts.partials.footer')
</body>
<!--customizer-->
<div id="customizer"></div>
<div id="myChart"></div>
<!-- Bootstrap js-->
<script src="{{ asset('assets/vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/formvalidation.js') }}"></script>
<!-- slick-file -->
<script src="{{ asset('assets/vendor/slick/slick.min.js') }}"></script>
<!-- vector map plugin js -->
<script src="{{ asset('assets/vendor/vector-map/jquery-jvectormap-2.0.5.min.js') }}"></script>
<script src="{{ asset('assets/vendor/vector-map/jquery-jvectormap-world-mill.js') }}"></script>
<!--cleave js  -->
<script src="{{ asset('assets/vendor/cleavejs/cleave.min.js') }}"></script>
<script src="{{ asset('assets/vendor/notifications/toastify-js.js') }}"></script>
<!-- sweetalert js-->
<script src="{{ asset('assets/vendor/sweetalert/sweetalert.js') }}"></script>
<!-- js -->
<script src="{{ asset('assets/js/sweet_alert.js') }}"></script>
<script src="{{ asset('assets/js/globalcustom.js') }}"></script>
<!-- scripts end-->

</html>

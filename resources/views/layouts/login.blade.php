<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description"
        content="TaxBridge Invoicing Management System - Manage invoices, clients, and FBR compliance efficiently.">
    <title>{{ config('app.name', 'TaxBridge | Invoicing Management System') }}</title>
    <link rel="icon" href="{{ asset('assets/images/logo/favicon.ico.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('assets/images/logo/favicon.ico.png') }}" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/style_theme_login.css') }}" />
     <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" />
         <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/tabler-icons/tabler-icons.css') }}">
    <script src="{{ asset('assets/js/jquery-3.6.3.min.js') }}"></script>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-8SRPF9LZ7L"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'G-8SRPF9LZ7L');
    </script>
</head>
<body>
    @yield('content')
    @include('layouts.partials.footer')
</body>
<!--customizer-->
<div id="customizer"></div>
<div id="myChart"></div>
<script src="{{ asset('assets/vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/formvalidation.js') }}"></script>
<script src="{{ asset('assets/vendor/vector-map/jquery-jvectormap-2.0.5.min.js') }}"></script>
<script src="{{ asset('assets/vendor/vector-map/jquery-jvectormap-world-mill.js') }}"></script>
 <script src="{{ asset('assets/js/select2.min.js') }}"></script>
<script src="{{ asset('assets/js/globalcustom.js') }}"></script>
</html>

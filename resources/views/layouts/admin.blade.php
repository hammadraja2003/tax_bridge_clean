<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- All meta and title start-->
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Secureism Invoicing Management System - Manage invoices, clients, and FBR compliance efficiently.">
    <meta name="keywords" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('assets/images/logo/favicon_sec.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('assets/images/logo/favicon_sec.png') }}" type="image/x-icon">
    <title>{{ config('app.name', 'Secureism | Invoicing Management System') }}</title>
    <link rel="stylesheet" href="{{ asset('assets/vendor/animation/animate.min.css') }}">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800;900&amp;display=swap"
        rel="stylesheet">
    <!--font-awesome-css-->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fontawesome/css/all.css') }}">
    <!-- Tabler icons-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/tabler-icons/tabler-icons.css') }}">
    <!-- Prism css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/prism/prism.min.css') }}">
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/bootstrap/bootstrap.min.css') }}">
    <!-- Simplebar css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/simplebar/simplebar.css') }}">
    <!-- Font Awesome 6 Free -->

    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">  -->

    <!-- apexcharts css-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/apexcharts/apexcharts.css') }}"> 
    <!-- Toatify css-->
     <link rel="stylesheet" href="{{ asset('assets/vendor/notifications/toastify.min.css') }}">
    <!-- latest jquery-->
    <script src="{{ asset('assets/js/jquery-3.6.3.min.js') }}"></script>
    <!-- data table-->

    {{-- <link rel="stylesheet" href="{{ asset('assets/style_theme.css') }}" /> --}}
    <link rel="stylesheet" href="{{ asset('assets/style-BVr_C8ru.css') }}" />
    <!-- Custom Css -->
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}" />
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" />
</head>
<body>
    <div class="app-wrapper">
        <div class="loader-wrapper">
            <div class="app-loader">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
        @include('layouts.partials.navbar')
        <div class="app-content">
            @include('layouts.partials.header')
            <main>
                @include('layouts.partials.errors')
                @yield('content')
            </main>
        </div>
        <div class="go-top">
            <span class="progress-value">
                <i class="ti ti-arrow-up"></i>
            </span>
        </div>
        @include('layouts.partials.footer')
    </div>
    <!--customizer-->
    <div id="myChart"></div>
    <!-- scripts start-->
    <!-- Bootstrap js-->
    <script src="{{ asset('assets/vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/formvalidation.js') }}"></script>
    <!-- Simple bar js-->
    <script src="{{ asset('assets/vendor/simplebar/simplebar.js') }}"></script>
    <!-- Customizer js-->
    <script src="{{ asset('assets/js/customizer.js') }}"></script>
    <!-- prism js-->
    <script src="{{ asset('assets/vendor/prism/prism.min.js') }}"></script>
    <!-- App js-->
    <script src="{{ asset('assets/js/script.js') }}"></script>
    <!--customizer-->
    <div id="customizer"></div>
    <!-- apexcharts js-->
    <script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
    <!-- slick-file -->
    <script src="{{ asset('assets/vendor/slick/slick.min.js') }}"></script>
    <!-- vector map plugin js -->
    <script src="{{ asset('assets/vendor/vector-map/jquery-jvectormap-2.0.5.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/vector-map/jquery-jvectormap-world-mill.js') }}"></script>
    <!--cleave js  -->
    <script src="{{ asset('assets/vendor/cleavejs/cleave.min.js') }}"></script>
    <!-- Glight js -->
    <script src="{{ asset('assets/vendor/glightbox/glightbox.min.js') }}"></script>
    {{-- <script src="{{asset('assets/js/advance_table.js')}}"></script> --}}
    <!-- List js -->
    <script src="{{ asset('assets/vendor/listJs/list-jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/listJs/list.min.js') }}"></script>
    <!-- list table js -->
    <script src="{{ asset('assets/js/list_js.js') }}"></script>
    <!-- Toatify js-->
    <script src="{{ asset('assets/vendor/notifications/toastify-js.js') }}"></script>
    <!-- sweetalert js-->
    <script src="{{ asset('assets/vendor/sweetalert/sweetalert.js') }}"></script>
    <script src="{{ asset('assets/js/sweet_alert.js') }}"></script>
    <!--Global Custom js -->
    <script src="{{ asset('assets/js/globalcustom.js') }}"></script>
    
    @stack('scripts')
</body>
</html>

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
    <link rel="stylesheet" href="{{ asset('assets/cleaned/style-BVr_C8ru.css') }}" />
    <script src="{{ asset('assets/js/jquery-3.6.3.min.js') }}"></script>
    <style>
        body {
            background: #f5f7fa;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
            background: linear-gradient(to right, #dbfae7, #eef7ff);
        }

        .login-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            padding: 2.5rem;
            width: 600px;
        }

        .form-control {
            border-radius: 8px;
            padding: 10px 14px;
            border: 1px solid #d1d5db;
        }

        .btn-primary {
            background-color: #1d61d8;
            border: none;
            border-radius: 8px;
            padding: 10px 0;
            width: 100%;
            font-weight: 600;
        }

        .login-card h2 {
            text-align: center;
            color: #808080;
            font-weight: 600;
            margin-bottom: 1.5rem;
            border: solid 1px;
            width: fit-content;
            margin: auto;
            padding: 11px;
            border-left: 0;
            border-right: 0;
        }
    </style>
</head>

<body>
    <div class="login-card">
        <div class="text-center mb-3">
            <img src="{{ asset('assets/images/logo/' . config('app.logo')) }}" alt="TaxBridge" width="300">
        </div>
        <h2>Admin Login</h2>
        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="m-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('admin.admin_login.submit') }}">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label fw-semibold">Email</label>
                <input type="email" name="email" id="email"  class="form-control"
                    required autofocus>
            </div>
            <div class="mb-4">
                <label for="password" class="form-label fw-semibold">Password</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>
    {{-- Scripts --}}
    <script src="{{ asset('assets/vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/formvalidation.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const alerts = document.querySelectorAll(".alert");
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    alert.style.transition = "opacity 0.5s ease";
                    alert.style.opacity = "0";
                    setTimeout(function() {
                        if (alert.parentNode) {
                            alert.parentNode.removeChild(alert);
                        }
                    }, 500); 
                }, 3000);
            });
        });
    </script>
</body>

</html>

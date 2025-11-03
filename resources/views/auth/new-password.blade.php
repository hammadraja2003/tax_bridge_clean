@extends('layouts.login')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-7 col-xl-8 d-none d-lg-block p-0">
                <div class="image-contentbox">
                    <img src="{{ asset('assets/images/login/03.png') }}" class="img-fluid" alt="Reset Password">
                </div>
            </div>

            <div class="col-lg-5 col-xl-4 p-0 bg-white">
                <div class="form-container">



                    <form class="app-form" method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="row">
                            <div class="col-12">
                                <div class="mb-5 text-center text-lg-start">
                                    <div class="d-flex justify-content-center align-items-center my-2">
                                        <img src="{{ asset('assets/images/logo/' . config('app.logo')) }}" alt="Logo"
                                            class="dark-logo">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-5 text-center">
                                    <h4 class="text-secureism f-w-600">Forgot Your Password?</h4>
                                    <p class="text-muted">Enter your registered email address and we’ll send you a link to
                                        reset your password.</p>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input id="email" type="email" name="email" class="form-control"
                                        placeholder="Enter your email address" required autofocus>
                                </div>
                            </div>

                            <div class="col-12">
                                {{-- Success Message --}}
                                @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                                @endif

                                {{-- Error Message --}}
                                @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ $errors->first() }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                                @endif
                            </div>

                            <div class="col-12">
                                <button type="submit" class="btn btn-primary w-100">
                                    Send Password Reset Link
                                </button>
                            </div>


                            <div class="col-12 text-center mt-3">
                                <a href="{{ route('login') }}" class="text-decoration-none">
                                    ← Back to Login
                                </a>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    @stack('scripts')
@endsection

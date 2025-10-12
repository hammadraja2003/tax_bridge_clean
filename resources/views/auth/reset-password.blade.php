@extends('layouts.login')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-7 col-xl-8 d-none d-lg-block p-0">
            <div class="image-contentbox">
                <img src="{{ asset('assets/images/login/03.png') }}" class="img-fluid" alt="">
            </div>
        </div>
        <div class="col-lg-5 col-xl-4 p-0 bg-white">
            <div class="form-container">
                <form class="app-form" method="POST" action="{{ route('password.update') }}">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="email" value="{{ $email }}">

                    <div class="row">
                      <div class="col-12">
                                <div class="mb-5 text-center text-lg-start">
                                    <div class="d-flex justify-content-center align-items-center my-2">
                                             <img src="{{ asset('assets/images/logo/' . config('app.logo')) }}" alt="Logo" class="dark-logo">
                                    </div>
                                </div>
                            </div>
                        <div class="col-12">
                            <div class="mb-5 text-center">
                                <h4 class="text-secureism f-w-600">Set New Password</h4>
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <label>New Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Enter new password" required>
                            @error('password')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-12 mb-3">
                            <label>Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm password" required>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary w-100">Reset Password</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

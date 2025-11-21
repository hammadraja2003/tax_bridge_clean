@extends('admin.layouts.adminlayout')

@section('content')
    <div class="container-fluid">
        <div class="row table_setting">
            <div class="col-xxl-12">
                <div class="card">
                    <div class="card-body">
                        <form class="app-form needs-validation" novalidate action="{{ route('admin.businesses.register') }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            <!-- Hidden input for $id -->
                            <input type="hidden" name="id" value="{{ $id ?? '' }}">

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label required">Name</label>
                                    <input type="text" name="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name', $user->name ?? '') }}" required
                                        placeholder="Enter Your Username">
                                    <div class="invalid-feedback">
                                        @error('name')
                                            {{ $message }}
                                        @else
                                            Please Enter Name.
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label required">Email</label>
                                    <input type="email" name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email', $user->email ?? '') }}" required
                                        placeholder="Enter Your Email">
                                    <div class="invalid-feedback">
                                        @error('email')
                                            {{ $message }}
                                        @else
                                            Please Enter Email.
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label required">Password</label>
                                    <div class="input-group">
                                        <input type="password" id="password" name="password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            placeholder="Enter password" required>
                                        <span class="input-group-text toggle-password" data-target="password"
                                            style="cursor:pointer;">
                                            <i class="ti ti-eye"></i>
                                        </span>
                                        <div class="invalid-feedback">
                                            @error('password')
                                                {{ $message }}
                                            @else
                                                Please Enter Password.
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label required">Confirm Password</label>
                                    <div class="input-group">
                                        <input type="password" id="password_confirmation" name="password_confirmation"
                                            class="form-control" placeholder="Confirm password" required>
                                        <span class="input-group-text toggle-password" data-target="password_confirmation"
                                            style="cursor:pointer;">
                                            <i class="ti ti-eye"></i>
                                        </span>
                                        <div class="invalid-feedback">
                                            Passwords do not match.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 text-end">
                                <button type="submit" class="btn btn-primary">Add User</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.admin')
@section('content')
    <div class="container">
        <h2 class="mb-4 text-center">Profile Update</h2>
        @php
            use Illuminate\Support\Facades\Crypt;
            $encryptedId = Crypt::encrypt($user->id);
        @endphp
        <form class="app-form needs-validation" id="updateProfileForm" novalidate method="POST"
            action="{{ route('update-profile', $encryptedId) }}" enctype="multipart/form-data">
            @csrf
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row mb-3">
                        <!-- Username -->
                        <div class="col-md-6">
                            <label class="form-label required">UserName</label>
                            <input type="text" name="name" required
                                class="form-control @error('title', 'post') is-invalid @enderror" id="name"
                                value="{{ $user->name }}" />
                            <input type="hidden" name="id" value="{{ $user->id }}" />
                            <div class="invalid-feedback">Please Enter Name.</div>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- Email -->
                        <div class="col-md-6">
                            <label class="form-label required">Email</label>
                            <input type="text" name="email" required
                                class="form-control @error('title', 'post') is-invalid @enderror" id="email"
                                value="{{ $user->email }}" readonly />
                            <input type="hidden" name="old_email" id="old_email" value="{{ $user->email }}">
                            <input type="hidden" name="old_password" id="old_password" value="{{ $user->password }}">
                            <div class="invalid-feedback">Please Enter Email</div>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <!-- Password -->
                        <div class="col-md-6">
                            <label class="form-label required">Password</label>
                            <small class="text-danger ms-2">Note: (Min 8 characters, 1 uppercase, 1 lowercase, 1 number, 1
                                special
                                character)</small>
                            <div class="input-group">
                                <input type="password" name="password" class="form-control" id="password"
                                    placeholder="Enter new password" />
                                <span class="input-group-text" id="togglePassword" style="cursor: pointer;"><i
                                        class="fa fa-eye"></i></span>
                            </div>
                            @error('password')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- Confirm Password -->
                        <div class="col-md-6">
                            <label class="form-label required">Confirm Password</label>
                            <div class="input-group">
                                <input type="password" name="confirmed_password" class="form-control"
                                    id="confirmed_password" placeholder="Confirm password" />
                                <span class="input-group-text" id="toggleConfirmPassword" style="cursor: pointer;"><i
                                        class="fa fa-eye"></i></span>
                            </div>
                            <div id="passwordMatchError" class="text-danger"></div>
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </div>
                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </form>
        <!-- 2FA Controls (separate form) -->
        <!-- 2FA Controls -->
        <div class="mt-4 text-center">
            @php $user = Auth::user(); @endphp
            @if ($user->twofa_enabled)
                <p class="text-success mb-0">Two-Factor Authentication is <strong>Enabled</strong></p>
                <form method="POST" action="{{ route('2fa.disable') }}"
                    onsubmit="return confirm('Disable 2FA for your account?')">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary">
                        Disable Two-Factor Authentication
                    </button>
                </form>
            @elseif($user->twofa_secret)
                <div class=" d-flex enab">
                    <p class="text-warning mb-0">Two-Factor Authentication is <strong>Disabled</strong></p>
                    <a href="{{ route('2fa.setup') }}" class="btn btn-outline-primary">
                        Enable Two-Factor Authentication
                    </a>
                </div>
            @else
                <p class="text-danger mb-2">You have not set up Two-Factor Authentication yet.</p>
                <a href="{{ route('2fa.setup') }}" class="btn btn-outline-primary">
                    Set up 2FA
                </a>
            @endif
        </div>
    </div> <!-- end container -->
    <script nonce="{{ $nonce }}">
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
        // Toggle confirm password visibility
        document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
            const confirmInput = document.getElementById('confirmed_password');
            const icon = this.querySelector('i');
            if (confirmInput.type === 'password') {
                confirmInput.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                confirmInput.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        });
        // Check password match on form submit
        document.getElementById('updateProfileForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('confirmed_password').value;
            const error = document.getElementById('passwordMatchError');
            if (password !== confirm) {
                e.preventDefault();
                error.textContent = 'Passwords do not match.';
            } else {
                error.textContent = '';
            }
        });
    </script>
@endsection

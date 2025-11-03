@extends('layouts.login')

@section('content')
    <div class="d-flex justify-content-center align-items-center min-vh-100">
        <div class="container" style="max-width: 400px;">
            <h4 class="mb-3 text-center">Two-Factor Authentication</h4>
            <p class="text-muted text-center">Enter the 6-digit code from your authenticator app.</p>

            @if ($errors->any())
                <div class="alert alert-danger py-2">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('2fa.check') }}">
                @csrf
                <div class="mb-3">
                    <label for="code" class="form-label">Authentication Code</label>
                    <input type="text" name="code" id="code" class="form-control" inputmode="numeric"
                        autocomplete="one-time-code" autofocus required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Verify</button>
            </form>
        </div>
    </div>
@endsection

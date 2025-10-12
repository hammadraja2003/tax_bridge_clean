@extends('layouts.admin')

@section('content')
    <div class="container" style="max-width:700px">
        <h4 class="mb-3">Two-Factor Authentication</h4>

        @if (session('status'))
            <div class="alert alert-success py-2">{{ session('status') }}</div>
        @endif

        @if ($enabled)
            <div class="alert alert-info">2FA is currently <strong>enabled</strong> for your account.</div>
            <form method="POST" action="{{ route('2fa.disable') }}"
                onsubmit="return confirm('Disable 2FA for your account?')">
                @csrf
                <button class="btn btn-outline-danger">Disable 2FA</button>
            </form>
        @else
            <p>Scan this QR code with your authenticator app, then enter the 6-digit code to enable.</p>

            <div class="my-3">{!! $qrSvg !!}</div>
            <p><strong>Or enter key manually:</strong> <code>{{ $secret }}</code></p>

            @if ($errors->any())
                <div class="alert alert-danger py-2">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('2fa.enable') }}" class="mt-3">
                @csrf
                <input type="hidden" name="secret" value="{{ $secret }}">
                <div class="mb-3" style="max-width:300px">
                    <label for="code" class="form-label">Enter 6-digit code</label>
                    <input type="text" id="code" name="code" class="form-control" inputmode="numeric" required>
                </div>
                <button class="btn btn-primary">Enable 2FA</button>
            </form>
        @endif
    </div>
@endsection

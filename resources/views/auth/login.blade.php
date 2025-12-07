@extends('layouts.guest')

@section('title', 'Login')

@section('content')

<!-- Login Card -->
<div class="card border-0 shadow-lg">
    <div class="card-body p-5">
        <h5 class="card-title mb-4 ">
          <img src="{{ asset('img/logo-black.svg') }}" alt="MESS Logo" class="img-fluid w-75 ps-5">
        </h5>
        <br>
        <!-- Error Messages -->
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <div>
                        <strong>Login Gagal!</strong>
                        @foreach ($errors->all() as $error)
                            <div class="small">{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" id="loginForm">
            @csrf

            <!-- Email Address -->
            <div class="mb-4">
                <label for="email" class="form-label fw-semibold">
                    <i class="fas fa-envelope"></i> Email Address
                </label>
                <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror"
                       id="email" name="email" value="{{ old('email') }}"
                       placeholder="your@email.com" required autofocus>
                @error('email')
                    <div class="invalid-feedback d-block small">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label for="password" class="form-label fw-semibold">
                    <i class="fas fa-key"></i> Password
                </label>
                <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror"
                       id="password" name="password"
                       placeholder="••••••••" required>
                @error('password')
                    <div class="invalid-feedback d-block small">{{ $message }}</div>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="mb-4">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                    <label class="form-check-label" for="remember_me">
                        Ingat saya di perangkat ini
                    </label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-lg w-100 mb-3 fw-semibold">
                <i class="fas fa-sign-in-alt"></i> Masuk
            </button>
        </form>

    </div>
</div>
@endsection

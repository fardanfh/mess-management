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

            <!-- Username -->
            <div class="mb-4">
                <label for="username" class="form-label fw-semibold">
                    <i class="fas fa-user"></i> Username
                </label>
                <input type="text" class="form-control form-control-lg @error('username') is-invalid @enderror"
                       id="username" name="username" value="{{ old('username') }}"
                       placeholder="your_username" required autofocus>
                @error('username')
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
                        Remember Me 
                    </label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-lg w-100 mb-3 fw-semibold">
                <i class="fas fa-sign-in-alt"></i> Login
            </button>
        </form>

    </div>
</div>
@endsection

@extends('layouts.guest')

@section('title', 'Register')

@section('content')
<!-- Header Logo -->
<div class="text-center mb-5">
    <div class="mb-3">
        <i class="fas fa-user-plus fa-3x text-primary"></i>
    </div>
    <h1 class="h3 fw-bold text-dark">Daftar Akun Baru</h1>
    <p class="text-muted">Sistem Manajemen Mess</p>
</div>

<!-- Register Card -->
<div class="card border-0 shadow-lg">
    <div class="card-body p-5">
        <h5 class="card-title text-center mb-4">
            <i class="fas fa-clipboard-check"></i> Buat Akun
        </h5>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <div>
                        <strong>Registrasi Gagal!</strong>
                        @foreach ($errors->all() as $error)
                            <div class="small">{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" id="registerForm">
            @csrf

            <!-- Name -->
            <div class="mb-3">
                <label for="name" class="form-label fw-semibold">
                    <i class="fas fa-user"></i> Nama Lengkap
                </label>
                <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror"
                       id="name" name="name" value="{{ old('name') }}"
                       placeholder="Nama Anda" required autofocus>
                @error('name')
                    <div class="invalid-feedback d-block small">{{ $message }}</div>
                @enderror
            </div>

            <!-- Email Address -->
            <div class="mb-3">
                <label for="email" class="form-label fw-semibold">
                    <i class="fas fa-envelope"></i> Email Address
                </label>
                <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror"
                       id="email" name="email" value="{{ old('email') }}"
                       placeholder="your@email.com" required>
                @error('email')
                    <div class="invalid-feedback d-block small">{{ $message }}</div>
                @enderror
            </div>

            <!-- Role Selection -->
            <div class="mb-3">
                <label for="role_id" class="form-label fw-semibold">
                    <i class="fas fa-briefcase"></i> Pilih Role
                </label>
                <select class="form-select form-select-lg @error('role_id') is-invalid @enderror"
                        id="role_id" name="role_id" required>
                    <option value="">-- Pilih Role --</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" @if(old('role_id') == $role->id) selected @endif>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
                @error('role_id')
                    <div class="invalid-feedback d-block small">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label fw-semibold">
                    <i class="fas fa-lock"></i> Password
                </label>
                <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror"
                       id="password" name="password"
                       placeholder="••••••••" required>
                <div class="form-text small mt-2">
                    <strong>Requirements:</strong>
                    <ul class="mb-0 mt-1 ms-3">
                        <li>Min. 8 characters</li>
                        <li>Uppercase & lowercase</li>
                        <li>Number & symbol</li>
                    </ul>
                </div>
                @error('password')
                    <div class="invalid-feedback d-block small">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password Confirmation -->
            <div class="mb-4">
                <label for="password_confirmation" class="form-label fw-semibold">
                    <i class="fas fa-check-circle"></i> Konfirmasi Password
                </label>
                <input type="password" class="form-control form-control-lg @error('password_confirmation') is-invalid @enderror"
                       id="password_confirmation" name="password_confirmation"
                       placeholder="••••••••" required>
                @error('password_confirmation')
                    <div class="invalid-feedback d-block small">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary btn-lg w-100 mb-3 fw-semibold">
                <i class="fas fa-user-check"></i> Daftar
            </button>
        </form>

        <!-- Divider -->
        <div class="d-flex align-items-center mb-3">
            <hr class="flex-grow-1">
            <span class="px-3 text-muted small">atau</span>
            <hr class="flex-grow-1">
        </div>

        <!-- Login Link -->
        <div class="text-center">
            <p class="text-muted small">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="fw-semibold text-primary text-decoration-none">
                    Login di sini
                </a>
            </p>
        </div>
    </div>
</div>
@endsection

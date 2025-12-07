@extends('layouts.guest')

@section('title', 'Verify Email')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-lg border-0">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <i class="fas fa-envelope fa-3x text-primary mb-3"></i>
                    <h2 class="fw-bold text-primary">Verifikasi Email</h2>
                    <p class="text-muted">Silakan verifikasi email Anda</p>
                </div>

                @if (session('resent'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        Link verifikasi telah dikirim ke email Anda.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <p class="mb-4">
                    Link verifikasi telah dikirim ke email Anda. Silakan cek email dan klik link untuk memverifikasi.
                </p>

                <p class="mb-3 text-muted">Jika Anda tidak menerima email:</p>

                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-redo"></i> Kirim Ulang Email Verifikasi
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}" class="mt-3">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

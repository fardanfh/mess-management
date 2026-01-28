@extends('layouts.admin')

@section('title', 'Add Driver')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">

        <a href="{{ route('drivers.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8 col-xl-6">
            <!-- Form Card -->
            <div class="card">
                <div class="card-header card-header-primary">
                    <i class="fas fa-edit"></i> Driver Information
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle"></i>
                            <strong>Validation Error!</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('drivers.store') }}" method="POST">
                        @csrf

                        <!-- ID Card Field -->
                        <div class="mb-4">
                            <label for="id_card" class="form-label">
                                <i class="fas fa-id-card text-warning"></i> ID Card <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="text" class="form-control @error('id_card') is-invalid @enderror"
                                       id="id_card" name="id_card" value="{{ old('id_card', request('id_card')) }}" required placeholder="DRV-XXXXX">
                                <button class="btn btn-primary" type="button" id="generateIdCard">
                                    <i class="fas fa-magic"></i> Generate
                                </button>
                            </div>
                            @if(request('id_card'))
                                <small class="text-success d-block mt-2"><i class="fas fa-check-circle"></i> ID Card auto-filled from NFC scan.</small>
                            @endif
                            @error('id_card')
                                <small class="text-danger d-block mt-2"><i class="fas fa-times-circle"></i> {{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Name Field -->
                        <div class="mb-4">
                            <label for="name" class="form-label">
                                <i class="fas fa-user text-warning"></i> Full Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name') }}" required placeholder="Enter driver's name">
                            @error('name')
                                <small class="text-danger d-block mt-2"><i class="fas fa-times-circle"></i> {{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Phone Field -->
                        <div class="mb-4">
                            <label for="phone" class="form-label">
                                <i class="fas fa-phone text-warning"></i> Phone Number
                            </label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                   id="phone" name="phone" value="{{ old('phone') }}" placeholder="0812345678">
                            @error('phone')
                                <small class="text-danger d-block mt-2"><i class="fas fa-times-circle"></i> {{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Email Field -->
                        <div class="mb-4">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope text-warning"></i> Email Address
                            </label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email" value="{{ old('email') }}" placeholder="driver@example.com">
                            @error('email')
                                <small class="text-danger d-block mt-2"><i class="fas fa-times-circle"></i> {{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Address Field -->
                        <div class="mb-4">
                            <label for="address" class="form-label">
                                <i class="fas fa-map-marker-alt text-warning"></i> Address
                            </label>
                            <textarea class="form-control @error('address') is-invalid @enderror"
                                      id="address" name="address" rows="3" placeholder="Enter driver's address">{{ old('address') }}</textarea>
                            @error('address')
                                <small class="text-danger d-block mt-2"><i class="fas fa-times-circle"></i> {{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-grid gap-2 d-sm-flex">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save"></i> Save Driver
                            </button>
                            <a href="{{ route('drivers.index') }}" class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Info Box -->

        </div>
        <div class="col-lg-8 col-xl-6">
            <div class="card" style="border-left: 4px solid #FEC905;">
                <div class="card-body">
                    <h6 class="text-warning mb-2"><i class="fas fa-info-circle"></i> Tips</h6>
                    <ul class="small mb-0">
                        <li>Click <strong>Generate</strong> button to auto-create unique ID card</li>
                        <li>Phone number and email are optional</li>
                        <li>Fill all required fields marked with <span class="text-danger">*</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('generateIdCard').addEventListener('click', function() {
    const button = this;
    const originalText = button.innerHTML;

    // Show loading state
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generating...';

    // Call API to generate ID card
    fetch('{{ route("drivers.generate-id-card") }}')
        .then(response => response.json())
        .then(data => {
            document.getElementById('id_card').value = data.id_card;

            // Show success feedback
            const input = document.getElementById('id_card');
            input.classList.add('is-valid');
            setTimeout(() => input.classList.remove('is-valid'), 2000);
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to generate ID Card');
        })
        .finally(() => {
            button.disabled = false;
            button.innerHTML = originalText;
        });
});
</script>
<script>
// JS fallback: ensure id_card is filled from query string if Blade didn't
(function() {
    try {
        const params = new URLSearchParams(window.location.search);
        const idCardParam = params.get('id_card');
        const el = document.getElementById('id_card');
        if (idCardParam && el && !el.value) {
            el.value = idCardParam;
            el.classList.add('is-valid');
            el.focus();
            el.select();
        }
    } catch (e) {
        // ignore
    }
})();
</script>
@endsection

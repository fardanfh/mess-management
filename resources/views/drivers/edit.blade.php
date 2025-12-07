@extends('layouts.admin')

@section('title', 'Edit Driver')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">

        <a href="{{ route('drivers.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <div class="row">
        <div class="col-lg-12 col-xl-12">
            <!-- Form Card -->
            <div class="card">
                <div class="card-header card-header-primary">
                    <i class="fas fa-edit"></i> Update Driver Information
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

                    <form action="{{ route('drivers.update', $driver) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- ID Card Field -->
                        <div class="mb-4">
                            <label for="id_card" class="form-label">
                                <i class="fas fa-id-card text-warning"></i> ID Card <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('id_card') is-invalid @enderror"
                                   id="id_card" name="id_card" value="{{ old('id_card', $driver->id_card) }}" required placeholder="DRV-XXXXX">
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
                                   id="name" name="name" value="{{ old('name', $driver->name) }}" required placeholder="Enter driver's name">
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
                                   id="phone" name="phone" value="{{ old('phone', $driver->phone) }}" placeholder="0812345678">
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
                                   id="email" name="email" value="{{ old('email', $driver->email) }}" placeholder="driver@example.com">
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
                                      id="address" name="address" rows="3" placeholder="Enter driver's address">{{ old('address', $driver->address) }}</textarea>
                            @error('address')
                                <small class="text-danger d-block mt-2"><i class="fas fa-times-circle"></i> {{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Status Field -->
                        <div class="mb-4">
                            <label for="status" class="form-label">
                                <i class="fas fa-toggle-on text-warning"></i> Status <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('status') is-invalid @enderror"
                                    id="status" name="status" required>
                                <option value="">-- Select Status --</option>
                                <option value="active" {{ old('status', $driver->status) === 'active' ? 'selected' : '' }}>
                                    <i class="fas fa-check-circle"></i> Active
                                </option>
                                <option value="inactive" {{ old('status', $driver->status) === 'inactive' ? 'selected' : '' }}>
                                    <i class="fas fa-times-circle"></i> Inactive
                                </option>
                            </select>
                            @error('status')
                                <small class="text-danger d-block mt-2"><i class="fas fa-times-circle"></i> {{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-grid gap-2 d-sm-flex">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save"></i> Update Driver
                            </button>
                            <a href="{{ route('drivers.index') }}" class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

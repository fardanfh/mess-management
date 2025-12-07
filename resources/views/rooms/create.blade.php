@extends('layouts.admin')

@section('title', 'Add Room')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h2><i class="fas fa-door-open"></i> Add New Room</h2>
        <a href="{{ route('rooms.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8 col-xl-6">
            <!-- Form Card -->
            <div class="card">
                <div class="card-header card-header-primary">
                    <i class="fas fa-plus"></i> Room Information
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

                    <form action="{{ route('rooms.store') }}" method="POST">
                        @csrf

                        <!-- Room Number Field -->
                        <div class="mb-4">
                            <label for="room_number" class="form-label">
                                <i class="fas fa-door-closed text-success"></i> Room Number <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('room_number') is-invalid @enderror"
                                   id="room_number" name="room_number" value="{{ old('room_number') }}" placeholder="e.g., 101, 102A, 201" required>
                            @error('room_number')
                                <small class="text-danger d-block mt-2"><i class="fas fa-times-circle"></i> {{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Capacity Field -->
                        <div class="mb-4">
                            <label for="capacity" class="form-label">
                                <i class="fas fa-bed text-success"></i> Capacity (Number of Beds) <span class="text-danger">*</span>
                            </label>
                            <input type="number" class="form-control @error('capacity') is-invalid @enderror"
                                   id="capacity" name="capacity" value="{{ old('capacity', 1) }}" min="1" max="10" required>
                            @error('capacity')
                                <small class="text-danger d-block mt-2"><i class="fas fa-times-circle"></i> {{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Status Field -->
                        <div class="mb-4">
                            <label for="status" class="form-label">
                                <i class="fas fa-toggle-on text-success"></i> Status <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('status') is-invalid @enderror"
                                    id="status" name="status" required>
                                <option value="">-- Select Status --</option>
                                <option value="tersedia" {{ old('status') === 'tersedia' ? 'selected' : '' }}>
                                    <i class="fas fa-door-open"></i> Available
                                </option>
                                <option value="terisi" {{ old('status') === 'terisi' ? 'selected' : '' }}>
                                    <i class="fas fa-door-closed"></i> Occupied
                                </option>
                                <option value="perbaikan" {{ old('status') === 'perbaikan' ? 'selected' : '' }}>
                                    <i class="fas fa-tools"></i> Maintenance
                                </option>
                            </select>
                            @error('status')
                                <small class="text-danger d-block mt-2"><i class="fas fa-times-circle"></i> {{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Description Field -->
                        <div class="mb-4">
                            <label for="description" class="form-label">
                                <i class="fas fa-file-alt text-success"></i> Description
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="3" placeholder="Room facilities, special notes, etc.">{{ old('description') }}</textarea>
                            @error('description')
                                <small class="text-danger d-block mt-2"><i class="fas fa-times-circle"></i> {{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-grid gap-2 d-sm-flex">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-save"></i> Save Room
                            </button>
                            <a href="{{ route('rooms.index') }}" class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Info Box -->
            <div class="card mt-3" style="border-left: 4px solid #10b981;">
                <div class="card-body">
                    <h6 class="text-success mb-2"><i class="fas fa-info-circle"></i> Guidelines</h6>
                    <ul class="small mb-0">
                        <li>Room number should be unique and descriptive</li>
                        <li>Capacity indicates maximum number of beds</li>
                        <li>Select appropriate status for the room</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

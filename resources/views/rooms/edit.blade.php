@extends('layouts.admin')

@section('title', 'Edit Room')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <a href="{{ route('rooms.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8 col-xl-12">
            <!-- Form Card -->
            <div class="card">
                <div class="card-header card-header-primary">
                    <i class="fas fa-edit"></i> Update Room Information
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

                    <form action="{{ route('rooms.update', $room) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Room Number Field -->
                        <div class="mb-4">
                            <label for="room_number" class="form-label">
                                <i class="fas fa-door-closed text-warning"></i> Room Number <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('room_number') is-invalid @enderror"
                                   id="room_number" name="room_number" value="{{ old('room_number', $room->room_number) }}" required placeholder="e.g., 101">
                            @error('room_number')
                                <small class="text-danger d-block mt-2"><i class="fas fa-times-circle"></i> {{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Capacity Field -->
                        <div class="mb-4">
                            <label for="capacity" class="form-label">
                                <i class="fas fa-bed text-warning"></i> Capacity (Number of Beds) <span class="text-danger">*</span>
                            </label>
                            <input type="number" class="form-control @error('capacity') is-invalid @enderror"
                                   id="capacity" name="capacity" value="{{ old('capacity', $room->capacity) }}" min="1" max="10" required>
                            @error('capacity')
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
                                <option value="tersedia" {{ old('status', $room->status) === 'tersedia' ? 'selected' : '' }}>
                                    <i class="fas fa-door-open"></i> Available
                                </option>
                                <option value="terisi" {{ old('status', $room->status) === 'terisi' ? 'selected' : '' }}>
                                    <i class="fas fa-door-closed"></i> Occupied
                                </option>
                                <option value="perbaikan" {{ old('status', $room->status) === 'perbaikan' ? 'selected' : '' }}>
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
                                <i class="fas fa-file-alt text-warning"></i> Description
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="3" placeholder="Room facilities, notes, etc.">{{ old('description', $room->description) }}</textarea>
                            @error('description')
                                <small class="text-danger d-block mt-2"><i class="fas fa-times-circle"></i> {{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-grid gap-2 d-sm-flex">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save"></i> Update Room
                            </button>
                            <a href="{{ route('rooms.index') }}" class="btn btn-outline-secondary btn-lg">
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

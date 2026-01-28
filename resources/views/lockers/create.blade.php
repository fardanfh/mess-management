@extends('layouts.admin')

@section('title', 'Create New Locker')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-header-primary">
                    <i class="fas fa-plus-circle"></i> Add New Locker
                </div>
                <div class="card-body">
                    <form action="{{ route('lockers.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="locker_number" class="form-label">Locker Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('locker_number') is-invalid @enderror" 
                                   id="locker_number" name="locker_number" 
                                   placeholder="e.g., 101-L1" 
                                   value="{{ old('locker_number') }}" required>
                            @error('locker_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="room_id" class="form-label">Room <span class="text-danger">*</span></label>
                            <select class="form-select @error('room_id') is-invalid @enderror" 
                                    id="room_id" name="room_id" required>
                                <option value="">-- Select Room --</option>
                                @foreach ($rooms as $room)
                                    <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>
                                        {{ $room->room_number }}
                                    </option>
                                @endforeach
                            </select>
                            @error('room_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="capacity" class="form-label">Capacity <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('capacity') is-invalid @enderror" 
                                   id="capacity" name="capacity" 
                                   placeholder="e.g., 2" 
                                   value="{{ old('capacity', 2) }}" 
                                   min="1" max="5" required>
                            <small class="text-muted">Max number of drivers per locker</small>
                            @error('capacity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" name="status" required>
                                <option value="tersedia" {{ old('status') === 'tersedia' ? 'selected' : '' }}>Available</option>
                                <option value="penuh" {{ old('status') === 'penuh' ? 'selected' : '' }}>Full</option>
                                <option value="perbaikan" {{ old('status') === 'perbaikan' ? 'selected' : '' }}>Maintenance</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" 
                                      placeholder="Optional description">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Locker
                            </button>
                            <a href="{{ route('lockers.index') }}" class="btn btn-secondary">
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

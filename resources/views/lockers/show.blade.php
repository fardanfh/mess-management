@extends('layouts.admin')

@section('title', 'Locker Details')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex gap-2">
            <a href="{{ route('lockers.edit', $locker) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('lockers.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Locker Information Card -->
            <div class="card mb-4">
                <div class="card-header card-header-primary">
                    <i class="fas fa-cube"></i> Locker Information
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Locker Number</label>
                            <p class="h6"><i class="fas fa-cube"></i> {{ $locker->locker_number }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Room</label>
                            <p class="h6">
                                <i class="fas fa-door-open"></i>
                                @if ($locker->room)
                                    <a href="{{ route('rooms.show', $locker->room) }}" class="text-decoration-none">
                                        {{ $locker->room->room_number }}
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Capacity</label>
                            <p class="h6"><i class="fas fa-users"></i> {{ $locker->capacity }} drivers</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Current Occupancy</label>
                            <p class="h6">
                                <i class="fas fa-person"></i> {{ $locker->getCurrentOccupancy() }} / {{ $locker->capacity }}
                            </p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Status</label>
                            <p class="h6">
                                @if ($locker->status === 'tersedia')
                                    <span class="badge bg-success" style="font-size: 0.9rem;"><i class="fas fa-check-circle"></i> Available</span>
                                @elseif ($locker->status === 'penuh')
                                    <span class="badge bg-warning" style="font-size: 0.9rem;"><i class="fas fa-exclamation-circle"></i> Full</span>
                                @else
                                    <span class="badge bg-danger" style="font-size: 0.9rem;"><i class="fas fa-tools"></i> Maintenance</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Created Date</label>
                            <p class="h6"><i class="fas fa-calendar"></i> {{ $locker->created_at->format('d M Y') }}</p>
                        </div>
                    </div>

                    @if ($locker->description)
                    <div class="row">
                        <div class="col-12">
                            <label class="form-label text-muted mb-1">Description</label>
                            <p class="h6"><i class="fas fa-note"></i> {{ $locker->description }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Current Occupants Card -->
            <div class="card">
                <div class="card-header card-header-primary">
                    <i class="fas fa-users"></i> Current Occupants
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Driver Name</th>
                                    <th>Check-in Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($locker->checkins()->where('status', 'checked_in')->with('driver')->get() as $checkin)
                                    <tr>
                                        <td>
                                            <a href="{{ route('drivers.show', $checkin->driver) }}" class="text-decoration-none">
                                                <small><strong>{{ $checkin->driver->name }}</strong></small>
                                            </a>
                                        </td>
                                        <td>
                                            <small>{{ $checkin->check_in_time->format('d M Y') }}</small>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center text-muted py-3">
                                            <small>No active occupants</small>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

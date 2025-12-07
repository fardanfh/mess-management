@extends('layouts.admin')

@section('title', 'Room Details')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">

        <div class="d-flex gap-2">
            <a href="{{ route('rooms.edit', $room) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('rooms.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header card-header-primary">
                    <i class="fas fa-info-circle"></i> Room Information
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Room Number:</strong>
                            <h5>{{ $room->room_number }}</h5>
                        </div>
                        <div class="col-md-6">
                            <strong>Capacity:</strong>
                            <h5>{{ $room->capacity }} bed(s)</h5>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Status:</strong>
                            <h5>
                                @if ($room->status === 'tersedia')
                                    <span class="badge bg-success">Available</span>
                                @elseif ($room->status === 'terisi')
                                    <span class="badge bg-warning">Occupied</span>
                                @else
                                    <span class="badge bg-danger">Maintenance</span>
                                @endif
                            </h5>
                        </div>
                        <div class="col-md-6">
                            <strong>Current Occupancy:</strong>
                            <h5>{{ $room->getCurrentOccupancy() }} / {{ $room->capacity }}</h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <strong>Description:</strong>
                            <p>{{ $room->description ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Current Occupants -->
            <div class="card">
                <div class="card-header card-header-primary">
                    <i class="fas fa-users"></i> Current Occupants
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Driver</th>
                                    <th>ID Card</th>
                                    <th>Check-in</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($room->checkins->where('status', 'checked_in') as $checkin)
                                    <tr>
                                        <td>
                                            <a href="{{ route('drivers.show', $checkin->driver) }}">
                                                {{ $checkin->driver->name }}
                                            </a>
                                        </td>
                                        <td>{{ $checkin->driver->id_card }}</td>
                                        <td>{{ $checkin->check_in_time->format('d M Y H:i') }}</td>
                                        <td>
                                            <a href="{{ route('checkins.checkout-form', $checkin) }}" class="btn btn-sm btn-success">
                                                <i class="fas fa-sign-out-alt"></i> Checkout
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-3">No current occupants</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header card-header-primary">
                    <i class="fas fa-chart-bar"></i> Room Statistics
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Total Check-ins</small>
                        <h5>{{ $room->checkins->count() }}</h5>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Total Check-outs</small>
                        <h5>{{ $room->checkouts->count() }}</h5>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Total Revenue</small>
                        <h5>Rp {{ number_format($room->checkouts->sum('total_cost'), 0, ',', '.') }}</h5>
                    </div>
                </div>
            </div>

            <!-- Recent Check-ins/outs -->
            <div class="card mt-4">
                 <div class="card-header card-header-primary">
                    <i class="fas fa-history"></i> Recent Activity
                </div>
                <div class="card-body">
                    @forelse ($room->checkins->sortByDesc('created_at')->take(5) as $checkin)
                        <div class="mb-2 pb-2 border-bottom">
                            <small>
                                <strong>{{ $checkin->driver->name }}</strong><br>
                                <i class="fas fa-sign-in-alt text-success"></i>
                                {{ $checkin->check_in_time->format('d M Y H:i') }}
                                @if ($checkin->check_out_time)
                                    <br>
                                    <i class="fas fa-sign-out-alt text-danger"></i>
                                    {{ $checkin->check_out_time->format('d M Y H:i') }}
                                @endif
                            </small>
                        </div>
                    @empty
                        <p class="text-muted text-center py-3">No activity yet</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.admin')

@section('title', 'Check-ins')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <a href="{{ route('checkins.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> New Check-in
        </a>
    </div>

    <!-- Check-ins Table -->
    <div class="card">
        <div class="card-header card-header-primary">
            <i class="fas fa-list"></i> Check-in Records
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover datatable">
                    <thead>
                        <tr>
                            <th>Driver</th>
                            <th>Room</th>
                            <th>Check-in Time</th>
                            <th>Check-out Time</th>
                            <th>Status</th>
                            <th>Recorded By</th>
                            <th style="width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($checkins as $checkin)
                            <tr>
                                <td>
                                    <a href="{{ route('drivers.show', $checkin->driver) }}" class="text-decoration-none text-black font-weight-bold">
                                        {{ $checkin->driver->name }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('rooms.show', $checkin->room) }}" class="text-decoration-none text-warning">
                                        <strong>{{ $checkin->room->room_number }}</strong>
                                    </a>
                                </td>
                                <td><small>{{ $checkin->check_in_time->format('d M Y H:i') }}</small></td>
                                <td><small>{{ $checkin->check_out_time ? $checkin->check_out_time->format('d M Y H:i') : '-' }}</small></td>
                                <td>
                                    @if ($checkin->status === 'checked_in')
                                        <span class="badge bg-success" style="font-size: 0.85rem;"><i class="fas fa-sign-in-alt"></i> Checked In</span>
                                    @else
                                        <span class="badge bg-danger" style="font-size: 0.85rem;"><i class="fas fa-sign-out-alt"></i> Checked Out</span>
                                    @endif
                                </td>
                                <td><small class="text-muted">{{ $checkin->user->name }}</small></td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('checkins.show', $checkin) }}" class="btn btn-outline-primary" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if ($checkin->status === 'checked_in')
                                            <a href="{{ route('checkins.checkout-form', $checkin) }}" class="btn btn-outline-danger" title="Checkout">
                                                <i class="fas fa-sign-out-alt"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="fas fa-inbox text-muted" style="font-size: 40px;"></i>
                                    <p class="text-muted mt-3 mb-0">No check-ins found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="row mt-4">
        <div class="col-12">
            <nav>
                {{ $checkins->links() }}
            </nav>
        </div>
    </div>
</div>
@endsection

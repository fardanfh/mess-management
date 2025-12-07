@extends('layouts.admin')

@section('title', 'Check-in Details')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">

        </div>
        <div class="col-md-4 text-end">
            @if ($checkin->status === 'checked_in')
                <a href="{{ route('checkins.checkout-form', $checkin) }}" class="btn btn-success">
                    <i class="fas fa-sign-out-alt"></i> Process Checkout
                </a>
            @endif
            <a href="{{ route('checkins.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header card-header-primary">
                    <i class="fas fa-user"></i> Driver Information
                </div>
                <div class="card-body">
                    <p>
                        <strong>Name:</strong><br>
                        <a href="{{ route('drivers.show', $checkin->driver) }}">{{ $checkin->driver->name }}</a>
                    </p>
                    <p>
                        <strong>ID Card:</strong><br>
                        {{ $checkin->driver->id_card }}
                    </p>
                    <p>
                        <strong>Phone:</strong><br>
                        {{ $checkin->driver->phone ?? '-' }}
                    </p>
                </div>
            </div>

            <div class="card">
                <div class="card-header card-header-primary">
                    <i class="fas fa-door-closed"></i> Room Information
                </div>
                <div class="card-body">
                    <p>
                        <strong>Room Number:</strong><br>
                        <a href="{{ route('rooms.show', $checkin->room) }}">{{ $checkin->room->room_number }}</a>
                    </p>
                    <p>
                        <strong>Capacity:</strong><br>
                        {{ $checkin->room->capacity }} bed(s)
                    </p>
                    <p>
                        <strong>Current Occupancy:</strong><br>
                        {{ $checkin->room->getCurrentOccupancy() }} / {{ $checkin->room->capacity }}
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header card-header-primary">
                    <i class="fas fa-clock"></i> Timeline
                </div>
                <div class="card-body">
                    <p>
                        <strong><i class="fas fa-sign-in-alt text-success"></i> Check-in Time:</strong><br>
                        {{ $checkin->check_in_time->format('d M Y H:i:s') }}
                    </p>
                    @if ($checkin->check_out_time)
                        <p>
                            <strong><i class="fas fa-sign-out-alt text-danger"></i> Check-out Time:</strong><br>
                            {{ $checkin->check_out_time->format('d M Y H:i:s') }}
                        </p>
                    @endif
                    <p>
                        <strong>Status:</strong><br>
                        @if ($checkin->status === 'checked_in')
                            <span class="status-checked-in">Checked In</span>
                        @else
                            <span class="status-checked-out">Checked Out</span>
                        @endif
                    </p>
                    <p>
                        <strong>Processed By:</strong><br>
                        {{ $checkin->user->name }}
                    </p>
                </div>
            </div>

            @if ($checkin->checkout)
                <div class="card">
                    <div class="card-header card-header-primary">
                        <i class="fas fa-receipt"></i> Checkout Information
                    </div>
                    <div class="card-body">
                        <p>
                            <strong>Nights Stayed:</strong><br>
                            {{ $checkin->checkout->nights_stayed }} night(s)
                        </p>
                        <p>
                            <strong>Total Cost:</strong><br>
                            <h5>Rp {{ number_format($checkin->checkout->total_cost, 0, ',', '.') }}</h5>
                        </p>
                        <p>
                            <strong>Payment Status:</strong><br>
                            @if ($checkin->checkout->payment_status === 'paid')
                                <span class="badge bg-success">Paid</span>
                            @else
                                <span class="badge bg-warning">Unpaid</span>
                            @endif
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

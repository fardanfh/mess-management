@extends('layouts.admin')

@section('title', 'Checkout Details')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">

        </div>
        <div class="col-md-4 text-end">
            @if ($checkout->payment_status === 'unpaid')
                <form action="{{ route('checkouts.mark-paid', $checkout) }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check"></i> Mark as Paid
                    </button>
                </form>
            @endif
            <a href="{{ route('checkouts.index') }}" class="btn btn-outline-secondary">
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
                        <a href="{{ route('drivers.show', $checkout->driver) }}">{{ $checkout->driver->name }}</a>
                    </p>
                    <p>
                        <strong>ID Card:</strong><br>
                        {{ $checkout->driver->id_card }}
                    </p>
                    <p>
                        <strong>Phone:</strong><br>
                        {{ $checkout->driver->phone ?? '-' }}
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
                        <a href="{{ route('rooms.show', $checkout->room) }}">{{ $checkout->room->room_number }}</a>
                    </p>
                    <p>
                        <strong>Capacity:</strong><br>
                        {{ $checkout->room->capacity }} bed(s)
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header card-header-primary">
                    <i class="fas fa-clock"></i> Duration & Cost
                </div>
                <div class="card-body">
                    <p>
                        <strong>Check-in Time:</strong><br>
                        {{ $checkout->checkin->check_in_time->format('d M Y H:i:s') }}
                    </p>
                    <p>
                        <strong>Check-out Time:</strong><br>
                        {{ $checkout->checkout_time->format('d M Y H:i:s') }}
                    </p>
                    <p>
                        <strong>Nights Stayed:</strong><br>
                        <h5>{{ $checkout->nights_stayed }} night(s)</h5>
                    </p>
                    <hr>
                    <p>
                        <strong>Cost per Night:</strong><br>
                        Rp 2.000
                    </p>
                    <p>
                        <strong>Total Cost:</strong><br>
                        <h4 class="text-success">Rp {{ number_format($checkout->total_cost, 0, ',', '.') }}</h4>
                    </p>
                </div>
            </div>

            <div class="card">
                <div class="card-header card-header-primary">
                    <i class="fas fa-money-bill"></i> Payment Information
                </div>
                <div class="card-body">
                    <p>
                        <strong>Payment Status:</strong><br>
                        @if ($checkout->payment_status === 'paid')
                            <span class="badge bg-success">Paid</span>
                        @else
                            <span class="badge bg-warning">Unpaid</span>
                        @endif
                    </p>
                    @if ($checkout->payment_date)
                        <p>
                            <strong>Payment Date:</strong><br>
                            {{ $checkout->payment_date->format('d M Y H:i:s') }}
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if ($checkout->invoice)
    <br>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <i class="fas fa-file-invoice"></i> Invoice
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <strong>Invoice Number:</strong><br>
                                {{ $checkout->invoice->invoice_number }}
                            </div>
                            <div class="col-md-4">
                                <strong>Invoice Date:</strong><br>
                                {{ $checkout->invoice->invoice_date->format('d M Y') }}
                            </div>
                            <div class="col-md-4">
                                <strong>Invoice Status:</strong><br>
                                <span class="badge
                                    @if ($checkout->invoice->status === 'paid') bg-success
                                    @elseif ($checkout->invoice->status === 'issued') bg-warning
                                    @else bg-danger
                                    @endif
                                ">
                                    {{ ucfirst($checkout->invoice->status) }}
                                </span>
                            </div>
                        </div>
                        @if ($checkout->invoice->notes)
                            <div class="mt-3">
                                <strong>Notes:</strong><br>
                                {{ $checkout->invoice->notes }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

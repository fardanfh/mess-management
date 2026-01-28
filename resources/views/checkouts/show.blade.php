@extends('layouts.admin')

@section('title', 'Checkout Details')

@section('content')
<div class="container-fluid">
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

            @if ($checkout->locker)
                <div class="card mt-4">
                    <div class="card-header card-header-primary">
                        <i class="fas fa-cube"></i> Locker Information
                    </div>
                    <div class="card-body">
                        <p>
                            <strong>Locker Number:</strong><br>
                            <a href="{{ route('lockers.show', $checkout->locker) }}">{{ $checkout->locker->locker_number }}</a>
                        </p>
                        <p>
                            <strong>Locker Capacity:</strong><br>
                            {{ $checkout->locker->capacity }} driver(s)
                        </p>
                        <p>
                            <strong>Locker Status:</strong><br>
                            @if ($checkout->locker->status === 'tersedia')
                                <span class="badge bg-success">Available</span>
                            @elseif ($checkout->locker->status === 'penuh')
                                <span class="badge bg-warning">Full</span>
                            @else
                                <span class="badge bg-danger">Maintenance</span>
                            @endif
                        </p>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header card-header-primary">
                    <i class="fas fa-clock"></i> Duration
                </div>
                <div class="card-body">
                    @if($checkout->checkin)
                    <p>
                        <strong>Check-in Time:</strong><br>
                        {{ $checkout->checkin->check_in_time->format('d M Y H:i:s') }}
                    </p>
                    @else
                    <p>
                        <strong>Check-in Time:</strong><br>
                        <span class="text-muted">-</span>
                    </p>
                    @endif
                    <p>
                        <strong>Check-out Time:</strong><br>
                        {{ $checkout->checkout_time->format('d M Y H:i:s') }}
                    </p>
                    <p>
                        <strong>Nights Stayed:</strong><br>
                        <h5>{{ $checkout->nights_stayed }} night(s)</h5>
                    </p>
                </div>
            </div>

            <!-- Fines Only -->
            <div class="card mb-4">
                <div class="card-header card-header-warning">
                    <i class="fas fa-ban"></i> Total Fines (Denda)
                </div>
                <div class="card-body">
                    @if ($checkout->checkin && $checkout->checkin->fines->count() > 0)
                        <div class="mb-3">
                            @foreach ($checkout->checkin->fines as $fine)
                                <div class="d-flex justify-content-between mb-2">
                                    <span>{{ $fine->getTypeLabel() }}</span>
                                    <span class="fw-bold">Rp {{ number_format($fine->amount, 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <h5><strong>Total:</strong></h5>
                            <h5 class="text-danger"><strong>Rp {{ number_format($checkout->total_cost, 0, ',', '.') }}</strong></h5>
                        </div>
                    @else
                        <p class="text-muted mb-0">
                            <i class="fas fa-check-circle text-success"></i> Tidak ada denda
                        </p>
                        <h5 class="text-success mt-3"><strong>Total: Rp 0</strong></h5>
                    @endif
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

            <!-- Fines Summary -->
            @if ($checkout->checkin && $checkout->checkin->fines->count() > 0)
                <div class="card mt-4">
                    <div class="card-header card-header-warning">
                        <i class="fas fa-ban"></i> Fines (Denda)
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>Fine Type</th>
                                        <th class="text-end">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($checkout->checkin->fines as $fine)
                                        <tr>
                                            <td>{{ $fine->getTypeLabel() }}</td>
                                            <td class="text-end">Rp {{ number_format($fine->amount, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="table-light">
                                        <th>Total Fines:</th>
                                        <th class="text-end text-danger">
                                            <h5>Rp {{ number_format($checkout->checkin->getTotalFines(), 0, ',', '.') }}</h5>
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
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

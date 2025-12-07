@extends('layouts.admin')

@section('title', 'Checkout Report')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <h2><i class="fas fa-file-alt"></i> Checkout Report</h2>
        <a href="{{ route('checkouts.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <!-- Date Range Filter Card -->
    <div class="filter-card">
        <form method="get" class="row g-3">
            <div class="col-md-4">
                <label for="from" class="form-label">
                    <i class="fas fa-calendar text-primary"></i> From Date
                </label>
                <input type="date" class="form-control" name="from" id="from" value="{{ $from }}">
            </div>
            <div class="col-md-4">
                <label for="to" class="form-label">
                    <i class="fas fa-calendar text-primary"></i> To Date
                </label>
                <input type="date" class="form-control" name="to" id="to" value="{{ $to }}">
            </div>
            <div class="col-md-4 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">
                    <i class="fas fa-filter"></i> Apply Filter
                </button>
                <a href="{{ route('checkouts.report') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-redo"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-6 col-lg-3">
            <div class="card">
                <div class="card-body py-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-muted d-block mb-1">Total Checkouts</small>
                            <h4 class="text-primary mb-0">{{ count($checkouts) }}</h4>
                        </div>
                        <div style="font-size: 40px; color: #3b82f6; opacity: 0.1;">
                            <i class="fas fa-sign-out-alt"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card">
                <div class="card-body py-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-muted d-block mb-1">Total Revenue</small>
                            <h5 class="text-success mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h5>
                        </div>
                        <div style="font-size: 40px; color: #10b981; opacity: 0.1;">
                            <i class="fas fa-money-bill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card">
                <div class="card-body py-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-muted d-block mb-1">Paid Amount</small>
                            <h5 class="text-info mb-0">Rp {{ number_format($paidAmount, 0, ',', '.') }}</h5>
                        </div>
                        <div style="font-size: 40px; color: #06b6d4; opacity: 0.1;">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card">
                <div class="card-body py-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <small class="text-muted d-block mb-1">Unpaid Amount</small>
                            <h5 class="text-danger mb-0">Rp {{ number_format($unpaidAmount, 0, ',', '.') }}</h5>
                        </div>
                        <div style="font-size: 40px; color: #dc3545; opacity: 0.1;">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Checkouts Table -->
    <div class="card">
        <div class="card-header card-header-dark">
            <i class="fas fa-table"></i> Checkout Details
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover datatable">
                    <thead>
                        <tr>
                            <th>Driver</th>
                            <th>Room</th>
                            <th>Check-out Date</th>
                            <th>Nights</th>
                            <th>Total Cost</th>
                            <th>Payment Status</th>
                            <th>Invoice</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($checkouts as $checkout)
                            <tr>
                                <td>
                                    <a href="{{ route('drivers.show', $checkout->driver) }}" class="text-decoration-none text-primary font-weight-bold">
                                        {{ $checkout->driver->name }}
                                    </a>
                                </td>
                                <td><strong>{{ $checkout->room->room_number }}</strong></td>
                                <td><small>{{ $checkout->checkout_time->format('d M Y H:i') }}</small></td>
                                <td><i class="fas fa-moon"></i> {{ $checkout->nights_stayed }}</td>
                                <td><strong class="text-dark">Rp {{ number_format($checkout->total_cost, 0, ',', '.') }}</strong></td>
                                <td>
                                    @if ($checkout->payment_status === 'paid')
                                        <span class="badge bg-success" style="font-size: 0.85rem;"><i class="fas fa-check-circle"></i> Paid</span>
                                    @else
                                        <span class="badge bg-warning" style="font-size: 0.85rem;"><i class="fas fa-clock"></i> Unpaid</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($checkout->invoice)
                                        <small class="text-primary font-weight-bold">{{ $checkout->invoice->invoice_number }}</small>
                                    @else
                                        <small class="text-muted">-</small>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="fas fa-inbox text-muted" style="font-size: 40px;"></i>
                                    <p class="text-muted mt-3 mb-0">No checkouts found for the selected period</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

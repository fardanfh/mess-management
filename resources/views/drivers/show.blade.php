@extends('layouts.admin')

@section('title', 'Driver Details')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex gap-2">
            <a href="{{ route('drivers.edit', $driver) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('drivers.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Driver Information Card -->
            <div class="card mb-4">
                <div class="card-header card-header-primary">
                    <i class="fas fa-user-circle"></i> Driver Information
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">ID Card</label>
                            <p class="h6 text-black"><i class="fas fa-id-card"></i> {{ $driver->id_card }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Full Name</label>
                            <p class="h6"><i class="fas fa-user"></i> {{ $driver->name }}</p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Phone Number</label>
                            <p class="h6"><i class="fas fa-phone"></i> {{ $driver->phone ?? '<span class="text-muted">-</span>' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Email Address</label>
                            <p class="h6"><i class="fas fa-envelope"></i> {{ $driver->email ?? '<span class="text-muted">-</span>' }}</p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Status</label>
                            <p class="h6">
                                @if ($driver->status === 'active')
                                    <span class="badge bg-success" style="font-size: 0.9rem;"><i class="fas fa-check-circle"></i> Active</span>
                                @else
                                    <span class="badge bg-danger" style="font-size: 0.9rem;"><i class="fas fa-times-circle"></i> Inactive</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted mb-1">Member Since</label>
                            <p class="h6"><i class="fas fa-calendar"></i> {{ $driver->created_at->format('d M Y') }}</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <label class="form-label text-muted mb-1">Address</label>
                            <p class="h6"><i class="fas fa-map-marker-alt"></i> {{ $driver->address ?? '<span class="text-muted">-</span>' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Check-in History -->
            <div class="card">
                <div class="card-header card-header-primary">
                    <i class="fas fa-history"></i> Check-in History
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Room</th>
                                    <th>Check-in Time</th>
                                    <th>Check-out Time</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($driver->checkins as $checkin)
                                    <tr>
                                        <td><strong>{{ $checkin->room->room_number }}</strong></td>
                                        <td><small>{{ $checkin->check_in_time->format('d M Y H:i') }}</small></td>
                                        <td><small>{{ $checkin->check_out_time ? $checkin->check_out_time->format('d M Y H:i') : '-' }}</small></td>
                                        <td>
                                            @if ($checkin->status === 'checked_in')
                                                <span class="badge bg-success"><i class="fas fa-sign-in-alt"></i> Checked In</span>
                                            @else
                                                <span class="badge bg-secondary"><i class="fas fa-sign-out-alt"></i> Checked Out</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">
                                            <i class="fas fa-inbox text-muted" style="font-size: 30px;"></i>
                                            <p class="text-muted mt-2 mb-0">No check-in history</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Statistics Card -->
            <div class="card mb-4">
                <div class="card-header card-header-primary">
                    <i class="fas fa-chart-bar"></i> Quick Statistics
                </div>
                <div class="card-body">
                    <!-- Total Check-ins -->
                    <div class="mb-4 pb-4" style="border-bottom: 1px solid #e5e7eb;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <small class="text-muted d-block mb-1">Total Check-ins</small>
                                <h4 class="mb-0" style="color: #FEC905;">{{ $driver->checkins->count() }}</h4>
                            </div>
                            <div style="font-size: 40px; color: #FEC905; opacity: 0.15;">
                                <i class="fas fa-sign-in-alt"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Currently Checked In -->
                    <div class="mb-4 pb-4" style="border-bottom: 1px solid #e5e7eb;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <small class="text-muted d-block mb-1">Currently Checked In</small>
                                <h4 class="mb-0">
                                    @if ($driver->isCheckedIn())
                                        <span class="badge bg-success" style="font-size: 0.9rem;"><i class="fas fa-check"></i> Yes</span>
                                    @else
                                        <span class="badge bg-danger" style="font-size: 0.9rem;"><i class="fas fa-times"></i> No</span>
                                    @endif
                                </h4>
                            </div>
                            <div style="font-size: 40px; color: #10b981; opacity: 0.15;">
                                <i class="fas fa-room"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Total Amount Due -->
                    <div>
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <small class="text-muted d-block mb-1">Amount Due</small>
                                <h4 class="text-danger mb-0">Rp {{ number_format($driver->checkouts->where('payment_status', 'unpaid')->sum('total_cost'), 0, ',', '.') }}</h4>
                            </div>
                            <div style="font-size: 40px; color: #dc3545; opacity: 0.15;">
                                <i class="fas fa-money-bill"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Invoices Card -->
            <div class="card">
                <div class="card-header card-header-primary">
                    <i class="fas fa-file-invoice"></i> Recent Invoices
                </div>
                <div class="card-body">
                    @forelse ($driver->invoices->take(5) as $invoice)
                        <div class="mb-3 pb-3" style="border-bottom: 1px solid #e5e7eb; last-child: mb-0; last-child: pb-0;">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="mb-1"><strong class="text-dark">{{ $invoice->invoice_number }}</strong></p>
                                    <small class="text-muted">{{ $invoice->invoice_date->format('d M Y') }}</small>
                                </div>
                                <span class="badge
                                    @if ($invoice->status === 'paid') bg-success
                                    @elseif ($invoice->status === 'issued') bg-warning
                                    @else bg-danger
                                    @endif
                                ">
                                    {{ ucfirst($invoice->status) }}
                                </span>
                            </div>
                            <div class="text-end mt-2">
                                <small class="text-dark"><strong>Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</strong></small>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="fas fa-inbox text-muted" style="font-size: 30px;"></i>
                            <p class="text-muted mt-2 mb-0">No invoices yet</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

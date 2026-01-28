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

            <!-- Check-in & Locker History -->
            <div class="card">
                <div class="card-header card-header-primary">
                    <i class="fas fa-history"></i> Check-in & Locker History
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover datatable mb-0">
                            <thead>
                                <tr>
                                    <th>Room</th>
                                    <th>Locker</th>
                                    <th>Check-in Time</th>
                                    <th>Check-out Time</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($driver->checkins as $checkin)
                                    <tr>
                                        <td><strong>{{ $checkin->room->room_number }}</strong></td>
                                        <td>
                                            @if ($checkin->locker)
                                                <span class="badge bg-info">
                                                    <i class="fas fa-cube"></i> {{ $checkin->locker->locker_number }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
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
                                        <td colspan="5" class="text-center py-4">
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

                    <!-- Total Fines -->
                    <div class="mt-4 pt-4" style="border-top: 1px solid #e5e7eb;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <small class="text-muted d-block mb-1">Total Fines</small>
                                @php
                                    $totalFines = $driver->checkins()
                                        ->with('fines')
                                        ->get()
                                        ->flatMap(fn($checkin) => $checkin->fines)
                                        ->sum('amount');
                                @endphp
                                <h4 class="text-warning mb-0">Rp {{ number_format($totalFines, 0, ',', '.') }}</h4>
                            </div>
                            <div style="font-size: 40px; color: #f59e0b; opacity: 0.15;">
                                <i class="fas fa-ban"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Violation History Card -->
            <div class="card mb-4">
                <div class="card-header card-header-warning">
                    <i class="fas fa-ban"></i> Violation History
                </div>
                <div class="card-body">
                    @php
                        $allFines = $driver->checkins()
                            ->with('fines')
                            ->get()
                            ->flatMap(fn($checkin) => $checkin->fines)
                            ->sortByDesc('created_at');
                        $paginatedFines = $allFines->forPage(request()->get('violation_page', 1), 3);
                    @endphp

                    @forelse ($paginatedFines as $fine)
                        <div class="mb-3 pb-3" style="border-bottom: 1px solid #e5e7eb; last-child: mb-0; last-child: pb-0;">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="mb-1">
                                        <strong class="text-dark">{{ $fine->getTypeLabel() }}</strong>
                                        <span class="badge bg-danger ms-2">Rp {{ number_format($fine->amount, 0, ',', '.') }}</span>
                                    </p>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar"></i> {{ $fine->created_at->format('d M Y H:i') }}
                                        <br>
                                        <i class="fas fa-user"></i> Added by: {{ $fine->addedBy->name ?? 'Unknown' }}
                                        <br>
                                        @if ($fine->checkin && $fine->checkin->room)
                                            <i class="fas fa-door-closed"></i> Room: <strong>{{ $fine->checkin->room->room_number }}</strong>
                                        @endif
                                        @if ($fine->checkin && $fine->checkin->locker)
                                            <br>
                                            <i class="fas fa-cube"></i> Locker: <strong>{{ $fine->checkin->locker->locker_number }}</strong>
                                        @endif
                                    </small>
                                </div>
                            </div>
                            @if ($fine->description)
                                <div class="mt-2 ps-3" style="border-left: 2px solid #f59e0b;">
                                    <small class="text-muted"><em>{{ $fine->description }}</em></small>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="fas fa-check-circle text-success" style="font-size: 30px;"></i>
                            <p class="text-muted mt-2 mb-0">No violations recorded</p>
                        </div>
                    @endforelse

                    @if ($allFines->count() > 3)
                        <div class="mt-4 pt-3" style="border-top: 1px solid #e5e7eb;">
                            <nav aria-label="Violation Pagination">
                                <ul class="pagination mb-0 justify-content-center" style="font-size: 0.85rem;">
                                    @php
                                        $totalPages = ceil($allFines->count() / 3);
                                        $currentPage = request()->get('violation_page', 1);
                                    @endphp
                                    
                                    @if ($currentPage > 1)
                                        <li class="page-item">
                                            <a class="page-link" href="?violation_page=1">First</a>
                                        </li>
                                        <li class="page-item">
                                            <a class="page-link" href="?violation_page={{ $currentPage - 1 }}">Prev</a>
                                        </li>
                                    @endif

                                    @for ($i = max(1, $currentPage - 1); $i <= min($totalPages, $currentPage + 1); $i++)
                                        <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                                            <a class="page-link" href="?violation_page={{ $i }}">{{ $i }}</a>
                                        </li>
                                    @endfor

                                    @if ($currentPage < $totalPages)
                                        <li class="page-item">
                                            <a class="page-link" href="?violation_page={{ $currentPage + 1 }}">Next</a>
                                        </li>
                                        <li class="page-item">
                                            <a class="page-link" href="?violation_page={{ $totalPages }}">Last</a>
                                        </li>
                                    @endif
                                </ul>
                            </nav>
                            <div class="text-center mt-2">
                                <small class="text-muted">Page {{ $currentPage }} of {{ $totalPages }} | Total: {{ $allFines->count() }} violations</small>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Invoices Card -->
            <div class="card">
                <div class="card-header card-header-primary">
                    <i class="fas fa-file-invoice"></i> Recent Invoices
                </div>
                <div class="card-body">
                    @php
                        $allInvoices = $driver->invoices->sortByDesc('created_at');
                        $paginatedInvoices = $allInvoices->forPage(request()->get('invoice_page', 1), 3);
                    @endphp

                    @forelse ($paginatedInvoices as $invoice)
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

                    @if ($allInvoices->count() > 3)
                        <div class="mt-4 pt-3" style="border-top: 1px solid #e5e7eb;">
                            <nav aria-label="Invoice Pagination">
                                <ul class="pagination mb-0 justify-content-center" style="font-size: 0.85rem;">
                                    @php
                                        $totalPages = ceil($allInvoices->count() / 3);
                                        $currentPage = request()->get('invoice_page', 1);
                                    @endphp
                                    
                                    @if ($currentPage > 1)
                                        <li class="page-item">
                                            <a class="page-link" href="?invoice_page=1">First</a>
                                        </li>
                                        <li class="page-item">
                                            <a class="page-link" href="?invoice_page={{ $currentPage - 1 }}">Prev</a>
                                        </li>
                                    @endif

                                    @for ($i = max(1, $currentPage - 1); $i <= min($totalPages, $currentPage + 1); $i++)
                                        <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                                            <a class="page-link" href="?invoice_page={{ $i }}">{{ $i }}</a>
                                        </li>
                                    @endfor

                                    @if ($currentPage < $totalPages)
                                        <li class="page-item">
                                            <a class="page-link" href="?invoice_page={{ $currentPage + 1 }}">Next</a>
                                        </li>
                                        <li class="page-item">
                                            <a class="page-link" href="?invoice_page={{ $totalPages }}">Last</a>
                                        </li>
                                    @endif
                                </ul>
                            </nav>
                            <div class="text-center mt-2">
                                <small class="text-muted">Page {{ $currentPage }} of {{ $totalPages }} | Total: {{ $allInvoices->count() }} invoices</small>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize DataTable for Check-in History
        var checkinTable = document.querySelector('.card .datatable');
        if (checkinTable && typeof jQuery !== 'undefined' && $.fn.dataTable) {
            if (!$.fn.DataTable.isDataTable(checkinTable)) {
                $(checkinTable).DataTable({
                    responsive: true,
                    autoWidth: false,
                    pageLength: 3,
                    lengthMenu: [3, 5, 10, 25, 50],
                    order: [[2, 'desc']], // Sort by check-in time descending
                    language: {
                        emptyTable: 'Tidak ada data',
                        info: 'Menampilkan _START_ hingga _END_ dari _TOTAL_ entri',
                        infoEmpty: 'Menampilkan 0 hingga 0 dari 0 entri',
                        infoFiltered: '(disaring dari _MAX_ total entri)',
                        lengthMenu: 'Tampilkan _MENU_ entri',
                        loadingRecords: 'Memuat...',
                        processing: 'Memproses...',
                        search: 'Cari:',
                        zeroRecords: 'Tidak ada entri yang cocok ditemukan',
                        paginate: {
                            first: 'Pertama',
                            last: 'Terakhir',
                            next: 'Selanjutnya',
                            previous: 'Sebelumnya'
                        }
                    }
                });
            }
        }
    });
</script>
@endpush

@endsection

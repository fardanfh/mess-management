@extends('layouts.admin')

@section('title', 'Driver Report Check Out')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            
        </div>
        <div class="col-md-4 text-end">
            @if ($hasFilter && $reportData->count() > 0)
                <a href="{{ route('driver-report.export', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn btn-success me-2">
                    <i class="fas fa-download"></i> Export Excel
                </a>
            @endif
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card mb-4">
        <div class="card-header card-header-primary">
            <i class="fas fa-filter"></i> Filter Periode
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('driver-report.index') }}" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label for="start_date" class="form-label fw-bold">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" 
                           value="{{ $startDate }}" required>
                </div>
                <div class="col-md-5">
                    <label for="end_date" class="form-label fw-bold">End Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" 
                           value="{{ $endDate }}" required>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Report Table -->
    <div class="card">
        <div class="card-header card-header-primary">
            <div class="d-flex justify-content-between align-items-center">
                <span class="pe-2"
                    <i class="fas fa-table"></i> Data Report
                </span>
                @if ($hasFilter)
                    <span class="badge bg-light text-dark">{{ $reportData->count() }} Driver</span>
                @endif
            </div>
        </div>
        <div class="card-body p-0">
            @if ($hasFilter)
                @if ($reportData->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover datatable">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 5%">No</th>
                                    <th style="width: 20%">
                                        <i class="fas fa-user"></i> Driver Name
                                    </th>
                                    <th style="width: 15%">
                                        <i class="fas fa-id-card"></i> ID Card
                                    </th>
                                    <th class="text-center" style="width: 12%">
                                        <i class="fas fa-door-open"></i> Room Usage
                                    </th>
                                    <th class="text-center" style="width: 12%">
                                        <i class="fas fa-cube"></i> Locker Usage
                                    </th>
                                    <th class="text-end" style="width: 15%">
                                        <i class="fas fa-money-bill"></i> Total
                                    </th>
                                    <th class="text-center" style="width: 12%">
                                        <i class="fas fa-ban"></i> Violations
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reportData as $index => $data)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <span>
                                                <a href="{{ route('drivers.show', $data['driver_id']) }}" class="text-decoration-none text-dark">{{ $data['name'] }}</a>
                                            </span>  
                                        </td>
                                        <td>
                                            <span >{{ $data['id_card'] }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span>{{ $data['room_usages'] }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span>{{ $data['locker_usages'] }}</span>
                                        </td>
                                        <td class="text-end">
                                            <h6 class="mb-0 badge bg-success">
                                                <strong class="text-success">
                                                    Rp {{ number_format($data['total_nominal'], 0, ',', '.') }}
                                                </strong>
                                            </h6>
                                        </td>
                                        <td class="text-center">
                                            @if ($data['violation_count'] > 0)
                                                <button class="btn btn-sm btn-danger p-2 violation-btn" 
                                                        data-driver-id="{{ $data['driver_id'] }}"
                                                        data-driver-name="{{ $data['name'] }}"
                                                        data-start-date="{{ $startDate }}"
                                                        data-end-date="{{ $endDate }}"
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#violationModal">
                                                    <i class="fas fa-exclamation-circle"></i> 
                                                </button>
                                            @else
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check-circle"></i> 0
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Summary Cards -->
                    {{-- <div class="p-4">
                        <div class="row mt-4">
                            <div class="col-md-3">
                                <div class="card border-warning">
                                    <div class="card-body text-center">
                                        <h6 class="text-muted mb-2">Total Driver</h6>
                                        <h3 class="text-warning mb-0">{{ $reportData->count() }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card border-warning">
                                    <div class="card-body text-center">
                                        <h6 class="text-muted mb-2">Total Penggunaan Kamar</h6>
                                        <h3 class="text-warning mb-0">{{ $reportData->sum('room_usages') }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card border-warning">
                                    <div class="card-body text-center">
                                        <h6 class="text-muted mb-2">Total Penggunaan Locker</h6>
                                        <h3 class="text-warning mb-0">{{ $reportData->sum('locker_usages') }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card border-warning">
                                    <div class="card-body text-center">
                                        <h6 class="text-muted mb-2">Total Pelanggaran</h6>
                                        <h3 class="text-warning mb-0">{{ $reportData->sum('violation_count') }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="card border-warning">
                                    <div class="card-body text-center">
                                        <h6 class="text-muted mb-2">Total Nominal Periode</h6>
                                        <h2 class="text-warning mb-0">
                                            Rp {{ number_format($reportData->sum('total_nominal'), 0, ',', '.') }}
                                        </h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                @else
                    <div class="alert alert-info m-4" role="alert">
                        <i class="fas fa-info-circle"></i>
                        <strong>Tidak ada data</strong> - Tidak ada driver dengan aktivitas pada periode yang dipilih.
                    </div>
                @endif
            @else
                <div class="alert alert-warning m-4" role="alert">
                    <i class="fas fa-search"></i>
                    <strong>Filter Periode Diperlukan</strong> - Silakan pilih tanggal mulai dan tanggal akhir di atas untuk menampilkan laporan.
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Violation Modal -->
<div class="modal fade" id="violationModal" tabindex="-1" aria-labelledby="violationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="violationModalLabel">
                    <i class="fas fa-exclamation-triangle"></i> Pelanggaran - <span id="driverNameModal"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <strong class="text-muted">Periode:</strong>
                    <span id="periodModal"></span>
                </div>
                <div id="violationTableContainer" class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal Checkout</th>
                                <th>Jenis Pelanggaran</th>
                                <th>Denda (Rp)</th>
                            </tr>
                        </thead>
                        <tbody id="violationTableBody">
                            <tr>
                                <td colspan="3" class="text-center text-muted">
                                    <i class="fas fa-spinner fa-spin"></i> Loading...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<style>
    .table-light {
        background-color: #f8f9fa;
    }

    .badge {
        padding: 0.5rem 0.75rem;
        font-size: 0.85rem;
    }

    .card-header-primary {
        background-color: #0066cc;
        color: white;
    }

    .border-primary {
        border-left: 4px solid #0066cc !important;
    }

    .border-success {
        border-left: 4px solid #28a745 !important;
    }

    .border-info {
        border-left: 4px solid #17a2b8 !important;
    }

    .border-warning {
        border-left: 4px solid #ffc107 !important;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const violationBtns = document.querySelectorAll('.violation-btn');
    
    violationBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const driverId = this.dataset.driverId;
            const driverName = this.dataset.driverName;
            const startDate = this.dataset.startDate;
            const endDate = this.dataset.endDate;
            
            // Set modal header and period
            document.getElementById('driverNameModal').textContent = driverName;
            document.getElementById('periodModal').textContent = `${startDate} hingga ${endDate}`;
            
            // Fetch violations data
            fetch(`/api/violations/${driverId}?start_date=${startDate}&end_date=${endDate}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    const tbody = document.getElementById('violationTableBody');
                    
                    if (data.violations && data.violations.length > 0) {
                        tbody.innerHTML = data.violations.map(violation => `
                            <tr>
                                <td>${violation.checkout_date}</td>
                                <td>
                                    <span class="badge bg-warning text-dark">
                                        ${violation.violation_name}
                                    </span>
                                </td>
                                <td><strong>Rp ${new Intl.NumberFormat('id-ID').format(violation.fine_amount)}</strong></td>
                            </tr>
                        `).join('');
                    } else {
                        tbody.innerHTML = `
                            <tr>
                                <td colspan="3" class="text-center text-muted">
                                    <i class="fas fa-check-circle"></i> Tidak ada pelanggaran pada periode ini
                                </td>
                            </tr>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error fetching violations:', error);
                    document.getElementById('violationTableBody').innerHTML = `
                        <tr>
                            <td colspan="3" class="text-center text-danger">
                                <i class="fas fa-exclamation-circle"></i> Error: ${error.message}
                            </td>
                        </tr>
                    `;
                });
        });
    });
});
</script>
@endsection

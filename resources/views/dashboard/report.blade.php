@extends('layouts.admin')

@section('title', 'Dashboard Report')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">

        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Date Range Filter -->
    <div class="kpi-card mb-4">
        <div class="card-body">
            <form method="get" class="row g-3">
                <div class="col-md-4">
                    <label for="from" class="form-label">From Date</label>
                    <input type="date" class="form-control" name="from" id="from" value="{{ $from }}">
                </div>
                <div class="col-md-4">
                    <label for="to" class="form-label">To Date</label>
                    <input type="date" class="form-control" name="to" id="to" value="{{ $to }}">
                </div>
                <div class="col-md-4 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <button type="button" class="btn btn-outline-danger" id="downloadPdfBtn" title="Download as PDF">
                        <i class="fas fa-file-pdf"></i> PDF
                    </button>
                    <a href="{{ route('dashboard.export-excel', ['from' => $from, 'to' => $to]) }}" class="btn btn-outline-success" title="Export as Excel/CSV">
                        <i class="fas fa-file-excel"></i> Excel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="kpi-card">
                <h6>Total Check-ins</h6>
                <h3>{{ count($checkins) }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="kpi-card">
                <h6>Total Check-outs</h6>
                <h3>{{ count($checkouts) }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="kpi-card ">
                <h6>Total Nights</h6>
                <h3>{{ $totalNights }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="kpi-card">
                <h6>Average Occupancy</h6>
                <h3>
                    @if (count($checkouts) > 0)
                        {{ round($totalNights / count($checkouts), 1) }}
                    @else
                        0
                    @endif
                </h3>
            </div>
        </div>
    </div>

    <!-- Financial Summary -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header card-header-primary">
                    <i class="fas fa-money-bill"></i> Revenue Summary
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Total Revenue</small>
                        <h5>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h5>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Paid Amount</small>
                        <h5 class="text-success">Rp {{ number_format($paidAmount, 0, ',', '.') }}</h5>
                    </div>
                    <div>
                        <small class="text-muted">Unpaid Amount</small>
                        <h5 class="text-danger">Rp {{ number_format($unpaidAmount, 0, ',', '.') }}</h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header card-header-primary">
                    <i class="fas fa-chart-pie"></i> Payment Status
                </div>
                <div class="card-body">
                    <canvas id="paymentChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header card-header-primary">
                    <i class="fas fa-chart-bar"></i> Daily Revenue
                </div>
                <div class="card-body">
                    <canvas id="dailyRevenueChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Check-ins Table -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-header-primary">
                    <i class="fas fa-sign-in-alt"></i> Check-ins ({{ count($checkins) }} records)
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>Driver</th>
                                    <th>Room</th>
                                    <th>Check-in Time</th>
                                    <th>User</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($checkins->take(10) as $checkin)
                                    <tr>
                                        <td>{{ $checkin->driver->name }}</td>
                                        <td>{{ $checkin->room->room_number }}</td>
                                        <td>{{ $checkin->check_in_time->format('d M Y H:i') }}</td>
                                        <td>{{ $checkin->user->name }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-3">No check-ins for selected period</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Check-outs Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-header-primary">
                    <i class="fas fa-sign-out-alt"></i> Check-outs ({{ count($checkouts) }} records)
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>Driver</th>
                                    <th>Room</th>
                                    <th>Check-out Time</th>
                                    <th>Nights</th>
                                    <th>Total Cost</th>
                                    <th>Payment Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($checkouts->take(10) as $checkout)
                                    <tr>
                                        <td>{{ $checkout->driver->name }}</td>
                                        <td>{{ $checkout->room->room_number }}</td>
                                        <td>{{ $checkout->checkout_time->format('d M Y H:i') }}</td>
                                        <td>{{ $checkout->nights_stayed }}</td>
                                        <td><strong>Rp {{ number_format($checkout->total_cost, 0, ',', '.') }}</strong></td>
                                        <td>
                                            @if ($checkout->payment_status === 'paid')
                                                <span class="badge bg-success">Paid</span>
                                            @else
                                                <span class="badge bg-warning">Unpaid</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-3">No check-outs for selected period</td>
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

@push('js')
<script>
    // Payment Status Chart
    const paidAmount = {{ $paidAmount }};
    const unpaidAmount = {{ $unpaidAmount }};

    const paymentCtx = document.getElementById('paymentChart');
    if (paymentCtx) {
        new Chart(paymentCtx, {
            type: 'doughnut',
            data: {
                labels: ['Paid', 'Unpaid'],
                datasets: [{
                    data: [paidAmount, unpaidAmount],
                    backgroundColor: ['#27ae60', '#f39c12']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }

    // Daily Revenue Chart
    const checkouts = {!! json_encode($checkouts->groupBy(function($checkout) {
        return $checkout->checkout_time->format('Y-m-d');
    })->map(function($group) {
        return $group->sum('total_cost');
    })) !!};

    const dailyRevenueCtx = document.getElementById('dailyRevenueChart');
    if (dailyRevenueCtx) {
        new Chart(dailyRevenueCtx, {
            type: 'bar',
            data: {
                labels: Object.keys(checkouts),
                datasets: [{
                    label: 'Revenue',
                    data: Object.values(checkouts),
                    backgroundColor: '#3498db'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // PDF Download Handler
    document.getElementById('downloadPdfBtn').addEventListener('click', function() {
        const from = document.getElementById('from').value;
        const to = document.getElementById('to').value;

        // Open PDF export page in new window
        window.open('{{ route("dashboard.export-pdf") }}?from=' + from + '&to=' + to, '_blank');
    });
</script>
@endpush
@endsection

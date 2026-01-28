@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<!-- Quick Action Buttons -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('drivers.create') }}" class="btn btn-primary btn-sm" title="Add New Driver">
                <i class="fas fa-user-plus"></i> Add Driver
            </a>
            <a href="{{ route('rooms.create') }}" class="btn btn-primary btn-sm" title="Add New Room">
                <i class="fas fa-door-open"></i> Add Room
            </a>
            <a href="{{ route('checkins.create') }}" class="btn btn-primary btn-sm" title="Process Check-in">
                <i class="fas fa-sign-in-alt"></i> Check-in
            </a>
            <a href="{{ route('checkouts.index') }}" class="btn btn-primary btn-sm" title="Process Check-out">
                <i class="fas fa-sign-out-alt"></i> Check-out
            </a>
            <a href="{{ route('dashboard.report') }}" class="btn btn-primary btn-sm" title="View Reports">
                <i class="fas fa-file-alt"></i> Reports
            </a>
        </div>
    </div>
</div>

<!-- Main KPI Cards -->
<div class="row mb-4">
    <!-- Revenue Today -->
    <div class="col-md-3 mb-3">
        <div class="kpi-card">
            <div class="d-flex justify-content-between align-items-start">
                <div style="flex: 1;">
                    <div class="kpi-label">Today Revenue</div>
                    <div class="kpi-value">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</div>
                </div>
                <i class="fas fa-money-bill-wave kpi-icon text-primary"></i>
            </div>
        </div>
    </div>

    <!-- Pending Payments -->
    <div class="col-md-3 mb-3">
        <div class="kpi-card">
            <div class="d-flex justify-content-between align-items-start">
                <div style="flex: 1;">
                    <div class="kpi-label">Pending Payments</div>
                    <div class="kpi-value">Rp {{ number_format($unpaidAmount, 0, ',', '.') }}</div>
                </div>
                <i class="fas fa-hourglass-end kpi-icon text-primary"></i>
            </div>
        </div>
    </div>

    <!-- Occupancy Rate -->
    <div class="col-md-3 mb-3">
        <div class="kpi-card">
            <div class="d-flex justify-content-between align-items-start">
                <div style="flex: 1;">
                    <div class="kpi-label">Occupancy Rate</div>
                    <div class="kpi-value">{{ $occupancyRate }}%</div>
                    <div class="progress mt-2" style="height: 5px;">
                        <div class="progress-bar bg-primary" style="width: {{ $occupancyRate }}%"></div>
                    </div>
                </div>
                <i class="fas fa-chart-pie kpi-icon text-primary"></i>
            </div>
        </div>
    </div>

    <!-- Currently Checked In -->
    <div class="col-md-3 mb-3">
        <div class="kpi-card">
            <div class="d-flex justify-content-between align-items-start">
                <div style="flex: 1;">
                    <div class="kpi-label">Checked In</div>
                    <div class="kpi-value">{{ $currentlyCheckedIn }}</div>
                </div>
                <i class="fas fa-users kpi-icon text-primary"></i>
            </div>
        </div>
    </div>
</div>

<!-- Secondary Stats -->
<div class="row mb-4">
    <!-- Total Drivers -->
    <div class="col-md-2 mb-3">
        <div class="stat-card">
            <i class="fas fa-users text-primary"></i>
            <div class="stat-value">{{ $totalDrivers }}</div>
            <div class="stat-label">Total Drivers</div>
        </div>
    </div>

    <!-- Available Lockers -->
    <div class="col-md-2 mb-3">
        <div class="stat-card">
            <i class="fas fa-cube text-primary"></i>
            <div class="stat-value">{{ $availableLockers }}</div>
            <div class="stat-label">Lockers</div>
        </div>
    </div>

    <!-- Available Rooms -->
    <div class="col-md-2 mb-3">
        <div class="stat-card">
            <i class="fas fa-door-open text-primary"></i>
            <div class="stat-value">{{ $availableRooms }}</div>
            <div class="stat-label">Available</div>
        </div>
    </div>

    <!-- Occupied Rooms -->
    <div class="col-md-2 mb-3">
        <div class="stat-card">
            <i class="fas fa-door-closed text-primary"></i>
            <div class="stat-value">{{ $occupiedRooms }}</div>
            <div class="stat-label">Occupied</div>
        </div>
    </div>

    <!-- Maintenance -->
    <div class="col-md-2 mb-3">
        <div class="stat-card">
            <i class="fas fa-wrench text-primary"></i>
            <div class="stat-value">{{ $maintenanceRooms }}</div>
            <div class="stat-label">Maintenance</div>
        </div>
    </div>

    <!-- Today Checkins -->
    <div class="col-md-2 mb-3">
        <div class="stat-card">
            <i class="fas fa-sign-in-alt text-primary"></i>
            <div class="stat-value">{{ $todayCheckins }}</div>
            <div class="stat-label">Today</div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row mb-4">
    <div class="col-lg-8 mb-4">
        <div class="card h-100">
            <div class="card-header card-header-primary py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-black">
                    <i class="fas fa-chart-line mr-2"></i>  Monthly Activity
                </h6>
                <span class="badge badge-light">Last 12 Months</span>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="monthlyChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-header card-header-primary py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-black">
                    <i class="fas fa-chart-pie mr-2"></i> Room Status
                </h6>
                <span class="badge badge-light">Real-time</span>
            </div>
            <div class="card-body d-flex justify-content-center">
                <div style="max-width: 400px; width: 100%;">
                    <canvas id="occupancyChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Financial Summary -->
<div class="row mb-4">
    <div class="col-lg-6 mb-4">
        <div class="card ">
            <div class="card-header card-header-primary py-3">
                <h6 class="m-0 font-weight-bold text-black">
                    <i class="fas fa-chart-bar mr-2"></i> Financial Summary
                </h6>
            </div>
            <div class="card-body">
                <div class="summary-item">
                    <i class="fas fa-check" style="background-color: #70e8ae; color: #1ea865;"></i>
                    <div>
                        <div class="summary-label">Paid Amount</div>
                        <div class="summary-value">Rp {{ number_format($paidAmount, 0, ',', '.') }}</div>
                    </div>
                </div>
                <div class="summary-item">
                    <i class="fas fa-times" style="background-color: #fa4747; color: #c41919;"></i>
                    <div>
                        <div class="summary-label">Unpaid Amount</div>
                        <div class="summary-value">Rp {{ number_format($unpaidAmount, 0, ',', '.') }}</div>
                    </div>
                </div>
                <hr style="margin: 16px 0;">
                <div class="summary-item">
                    <i class="fas fa-dollar-sign" style="background-color: #FEF3C7; color: #FEC905;"></i>
                    <div>
                        <div class="summary-label">Total Revenue</div>
                        <div class="summary-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card ">
            <div class="card-header card-header-primary py-3">
                <h6 class="m-0 font-weight-bold text-black">
                    <i class="fas fa-calendar-alt mr-2"></i> This Month Summary
                </h6>
            </div>
            <div class="card-body">
                <div class="summary-item">
                    <i class="fas fa-sign-in-alt" style="background-color: #70e8ae; color: #1ea865;"></i>
                    <div>
                        <div class="summary-label">Check-ins</div>
                        <div class="summary-value">{{ $thisMonthCheckins }}</div>
                    </div>
                </div>
                <div class="summary-item">
                    <i class="fas fa-sign-out-alt" style="background-color: #fa4747; color: #c41919;"></i>
                    <div>
                        <div class="summary-label">Check-outs</div>
                        <div class="summary-value">{{ $thisMonthCheckouts }}</div>
                    </div>
                </div>
                <hr style="margin: 16px 0;">
                <div class="alert alert-info mb-0">
                    <i class="fas fa-info-circle mr-2"></i>
                    <strong>Monthly Performance:</strong> {{ $thisMonthCheckouts }} completed stays with {{ $thisMonthCheckins }} check-ins.
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Transactions Table -->
<div class="row mb-4">
    <div class="col-lg-6 mb-4">
        <div class="card ">
            <div class="card-header card-header-primary py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-black">
                    <i class="fas fa-sign-in-alt mr-2"></i> Today's Check-ins
                </h6>
                <a href="{{ route('checkins.index') }}" class="btn btn-sm btn-light">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-3">Driver</th>
                                <th>Room</th>
                                <th class="text-end pe-3">Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($todayTransactions ?? [] as $transaction)
                                <tr>
                                    <td class="ps-3">
                                        <a href="{{ route('drivers.show', $transaction->driver) }}" class="text-decoration-none text-black">
                                            {{ $transaction->driver->name }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('rooms.show', $transaction->room) }}" class="text-decoration-none">
                                            #{{ $transaction->room->room_number }}
                                        </a>
                                    </td>
                                    <td class="text-end pe-3">
                                        <small class="text-muted">{{ $transaction->check_in_time->format('H:i') }}</small>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">No check-ins today</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card ">
            <div class="card-header card-header-primary py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-black">
                    <i class="fas fa-sign-out-alt mr-2"></i> Today's Check-outs
                </h6>
                <a href="{{ route('checkouts.index') }}" class="btn btn-sm btn-light">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-3">Driver</th>
                                <th>Cost</th>
                                <th class="text-end pe-3">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($todayCheckouts ?? [] as $checkout)
                                <tr>
                                    <td class="ps-3">
                                        <a href="{{ route('drivers.show', $checkout->driver) }}" class="text-decoration-none text-black">
                                            {{ $checkout->driver->name }}
                                        </a>
                                    </td>
                                    <td>
                                        <strong>Rp {{ number_format($checkout->total_cost, 0, ',', '.') }}</strong>
                                    </td>
                                    <td class="text-end pe-3">
                                        @if ($checkout->payment_status === 'paid')
                                            <span class="badge bg-success">Paid</span>
                                        @else
                                            <span class="badge bg-warning">Unpaid</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">No check-outs today</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities -->
<div class="row">
    <div class="col-12">
        <div class="card ">
            <div class="card-header card-header-primary py-3">
                <h6 class="m-0 font-weight-bold text-black">
                    <i class="fas fa-history mr-2"></i> Recent Activities
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-sm">
                        <thead class="thead-light">
                            <tr>
                                <th>User</th>
                                <th>Action</th>
                                <th>Subject</th>
                                <th class="text-end">Timestamp</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentActivities ?? [] as $activity)
                                <tr>
                                    <td>
                                        <span class="badge bg-primary">{{ substr($activity->user->name ?? 'S', 0, 1) }}</span>
                                        <strong>{{ $activity->user->name ?? 'System' }}</strong>
                                    </td>
                                    <td>
                                        @php
                                            $actionBadges = [
                                                'created' => 'bg-success',
                                                'create' => 'bg-success',
                                                'updated' => 'bg-info',
                                                'update' => 'bg-info',
                                                'deleted' => 'bg-danger',
                                                'delete' => 'bg-danger',
                                                'viewed' => 'bg-secondary',
                                                'view' => 'bg-secondary',
                                            ];
                                            $action = strtolower($activity->action);
                                            $badgeClass = $actionBadges[$action] ?? 'bg-secondary';
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">{{ ucfirst($action) }}</span>
                                    </td>
                                    <td>
                                        <small><strong>{{ $activity->model_type }}</strong> #{{ $activity->model_id }}</small>
                                    </td>
                                    <td class="text-end">
                                        <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox text-muted" style="font-size: 2rem;"></i>
                                        <p class="mt-2">No activities recorded yet</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    * {
        font-family: 'Poppins', sans-serif;
    }

    .border-left-primary { border-left: .25rem solid #007bff !important; }
    .border-left-success { border-left: .25rem solid #28a745 !important; }
    .border-left-warning { border-left: .25rem solid #ffc107 !important; }
    .border-left-danger { border-left: .25rem solid #dc3545 !important; }

    .text-gray-800 { color: #2e3338; }
    .text-muted { color: #9ca3af; }
    .font-weight-bold { font-weight: 600; }
    .opacity-25 { opacity: 0.25; }

    /* Card Styling */
    .card {
        border: none;
        border-radius: 12px;
        transition: all 0.3s ease;
        background: white;
    }

    .card:hover {
        box-shadow: 0 8px 24px rgba(0,0,0,0.12) !important;
        transform: translateY(-2px);
    }

    .card-header {
        border-radius: 12px 12px 0 0 !important;
        border: none;
        font-weight: 600;
        font-size: 0.95rem;
    }

    .card-header-primary {
        background: #FEC905 !important;
        color: #000 !important;
        border-left: 5px solid #FEC905;
        border-radius: 12px 0 0 0;
        font-weight: 700;
        padding: 18px 24px !important;
    }

    .card-header-success {
        background: white !important;
        color: #10b981 !important;
        border-left: 5px solid #10b981;
        border-radius: 12px 0 0 0;
        font-weight: 700;
        padding: 18px 24px !important;
    }

    .card-header-info {
        background: white !important;
        color: #06b6d4 !important;
        border-left: 5px solid #06b6d4;
        border-radius: 12px 0 0 0;
        font-weight: 700;
        padding: 18px 24px !important;
    }

    .card-header-dark {
        background: white !important;
        color: #1f2937 !important;
        border-left: 5px solid #1f2937;
        border-radius: 12px 0 0 0;
        font-weight: 700;
        padding: 18px 24px !important;
    }

    /* KPI Cards */
    .kpi-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        border-radius: 12px;
        padding: 24px;
        border: 1px solid #e5e7eb;
    }

    .kpi-card .kpi-value {
        font-size: 2rem;
        font-weight: 700;
        color: #1f2937;
        margin: 12px 0;
    }

    .kpi-card .kpi-label {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6b7280;
    }

    .kpi-card .kpi-icon {
        font-size: 2.5rem;
        opacity: 0.15;
    }

    /* Secondary Stats Cards */
    .stat-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        border-radius: 12px;
        padding: 18px;
        border: 1px solid #e5e7eb;
        text-align: center;
    }

    .stat-card i {
        font-size: 1.8rem;
        margin-bottom: 12px;
        display: block;
    }

    .stat-card .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 6px;
    }

    .stat-card .stat-label {
        font-size: 0.8rem;
        color: #9ca3af;
        font-weight: 500;
    }

    /* Buttons */
    .btn {
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.9rem;
        padding: 0.5rem 1rem;
        border: none;
        transition: all 0.3s ease;
    }

    .btn-primary {
        background-color: #FEC905;
        color: #1f2937;
    }

    .btn-primary:hover {
        background-color: #F4A901;
        color: #1f2937;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(254, 201, 5, 0.3);
    }

    .btn-success {
        background-color: #10b981;
        color: white;
    }

    .btn-success:hover {
        background-color: #059669;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .btn-info {
        background-color: #FEC905;
        color: #1f2937;
    }

    .btn-info:hover {
        background-color: #F4A901;
        color: #1f2937;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(254, 201, 5, 0.3);
    }

    .btn-warning {
        background-color: #f59e0b;
        color: white;
    }

    .btn-warning:hover {
        background-color: #d97706;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    }

    .btn-outline-secondary {
        border-color: #d1d5db;
        color: #4b5563;
    }

    .btn-outline-secondary:hover {
        background-color: #f3f4f6;
        border-color: #9ca3af;
        color: #1f2937;
    }

    /* Badge Styling */
    .badge {
        padding: 0.5rem 0.75rem;
        font-weight: 500;
        border-radius: 6px;
        font-size: 0.8rem;
    }

    .bg-success {
        background-color: #d1fae5 !important;
        color: #065f46 !important;
    }

    .bg-warning {
        background-color: #fef3c7 !important;
        color: #92400e !important;
    }

    .bg-danger {
        background-color: #fee2e2 !important;
        color: #7f1d1d !important;
    }

    .bg-primary {
        background-color: #FEF3C7 !important;
        color: #78350f !important;
    }

    .bg-info {
        background-color: #FEF3C7 !important;
        color: #78350f !important;
    }

    .bg-secondary {
        background-color: #e5e7eb !important;
        color: #374151 !important;
    }

    /* Tables */
    .table {
        border-collapse: separate;
        border-spacing: 0 8px;
    }

    .table th {
        background: transparent;
        border: none;
        border-bottom: 3px solid #FEC905;
        font-weight: 600;
        color: #6b7280;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 12px;
    }

    .table td {
        border: none;
        padding: 12px;
        background-color: #f9fafb;
        vertical-align: middle;
    }

    .table tbody tr {
        transition: all 0.2s ease;
    }

    .table tbody tr:hover {
        background-color: #f3f4f6;
    }

    .table tbody tr:first-child td:first-child {
        border-top-left-radius: 8px;
    }

    .table tbody tr:first-child td:last-child {
        border-top-right-radius: 8px;
    }

    .table tbody tr:last-child td:first-child {
        border-bottom-left-radius: 8px;
    }

    .table tbody tr:last-child td:last-child {
        border-bottom-right-radius: 8px;
    }

    /* Chart Container */
    .chart-area {
        position: relative;
        margin-top: 10px;
    }

    /* Alert Styling */
    .alert {
        border-radius: 8px;
        border: none;
        padding: 1rem;
    }

    .alert-info {
        background-color: #dbeafe;
        color: #1e40af;
    }

    .alert-success {
        background-color: #d1fae5;
        color: #065f46;
    }

    /* Progress Bar */
    .progress {
        background-color: #e5e7eb;
        border-radius: 10px;
        height: 8px;
    }

    .progress-bar {
        border-radius: 10px;
    }

    /* Financial Summary Cards */
    .summary-item {
        display: flex;
        align-items: center;
        padding: 16px 0;
    }

    .summary-item i {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        margin-right: 16px;
        font-size: 1.5rem;
    }

    .summary-value {
        font-size: 1.3rem;
        font-weight: 700;
        color: #1f2937;
    }

    .summary-label {
        font-size: 0.8rem;
        color: #9ca3af;
        font-weight: 500;
        text-transform: uppercase;
    }

    /* Page Title */
    .topbar-title {
        font-weight: 700;
    }

    /* Text Color Classes */
    .text-primary {
        color: #FEC905 !important;
    }

    .text-success {
        color: #10b981 !important;
    }

    .text-warning {
        color: #f59e0b !important;
    }

    .text-danger {
        color: #dc2626 !important;
    }

    .text-info {
        color: #06b6d4 !important;
    }

    .text-secondary {
        color: #6b7280 !important;
    }

    .text-muted {
        color: #9ca3af !important;
    }
</style>

@push('js')
<script>
    if (typeof Chart !== 'undefined') {
        // Monthly Chart
        var monthlyCtx = document.getElementById('monthlyChart');
        if (monthlyCtx) {
            var monthlyChart = new Chart(monthlyCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($monthlyData['months'] ?? []) !!},
                    datasets: [
                        {
                            label: 'Check-ins',
                            data: {!! json_encode($monthlyData['checkins'] ?? []) !!},
                            borderColor: '#007bff',
                            backgroundColor: 'rgba(0, 123, 255, 0.05)',
                            borderWidth: 2,
                            tension: 0.3,
                            fill: true,
                            pointRadius: 4,
                            pointBackgroundColor: '#007bff',
                            pointBorderColor: 'white',
                            pointBorderWidth: 2
                        },
                        {
                            label: 'Check-outs',
                            data: {!! json_encode($monthlyData['checkouts'] ?? []) !!},
                            borderColor: '#28a745',
                            backgroundColor: 'rgba(40, 167, 69, 0.05)',
                            borderWidth: 2,
                            tension: 0.3,
                            fill: true,
                            pointRadius: 4,
                            pointBackgroundColor: '#28a745',
                            pointBorderColor: 'white',
                            pointBorderWidth: 2
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: true,
                            labels: { usePointStyle: true, padding: 15 }
                        }
                    },
                    scales: {
                        y: { beginAtZero: true, grid: { drawBorder: false } },
                        x: { grid: { drawBorder: false, display: false } }
                    }
                }
            });
        }

        // Occupancy Chart
        var occupancyCtx = document.getElementById('occupancyChart');
        if (occupancyCtx) {
            var occupancyChart = new Chart(occupancyCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Available', 'Occupied', 'Maintenance'],
                    datasets: [{
                        data: [{!! $availableRooms ?? 0 !!}, {!! $occupiedRooms ?? 0 !!}, {!! $maintenanceRooms ?? 0 !!}],
                        backgroundColor: ['#28a745', '#dc3545', '#ffc107'],
                        borderColor: 'white',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom',
                            labels: { usePointStyle: true, padding: 15 }
                        }
                    }
                }
            });
        }
    }
</script>
@endpush
@endsection

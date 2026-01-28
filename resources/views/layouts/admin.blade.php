<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Mess Management')</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Font Awesome (CDN) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Poppins Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">
    <style>
        :root {
            --primary-color: #FEC905;
            --secondary-color: #6c757d;
            --success-color: #28a745;
            --danger-color: #dc3545;
        }

        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #ffffff;
            font-size: 0.8rem;
        }

        .sidebar {
            background: white;
            min-height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            width: 280px;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 8px 0 24px rgba(0, 0, 0, 0.1);
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 20px;
            background: transparent;
            border-bottom: 1px dotted #FEC905;
            margin-bottom: 24px;
        }

        .sidebar-brand i {
            font-size: 32px;
            color: #FEC905;
            filter: drop-shadow(0 2px 4px rgba(254, 201, 5, 0.3));
        }

        .sidebar-brand span {
            font-size: 20px;
            font-weight: 700;
            color: #1f2937;
            letter-spacing: 0.5px;
        }

        .sidebar-nav {
            padding: 0 12px;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px 16px;
            margin-bottom: 6px;
            color: #6b7280;
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.3s ease;
            font-weight: 500;
            font-size: 0.9rem;
            position: relative;
        }

        .sidebar-nav a:hover {
            background-color: #fff7ed;
            color: #FEC905;
            transform: translateX(4px);
        }

        .sidebar-nav a.active {
            background: #fff7ed;
            color: #FEC905;
            font-weight: 600;
            border-left: 3px solid #FEC905;
            padding-left: 13px;
            box-shadow: inset 4px 0 8px rgba(254, 201, 5, 0.1);
        }

        .sidebar-nav i {
            width: 20px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .sidebar-nav a:hover i {
            color: #FEC905;
            transform: scale(1.1);
        }

        .sidebar-nav a.active i {
            color: #FEC905;
        }

        .sidebar-logout {
            position: absolute;
            bottom: 20px;
            left: 12px;
            right: 15px;
            padding-top: 16px;
            border-top: 1px solid #e5e7eb;
        }

        .sidebar-logout form {
            width: 100%;
        }

        .sidebar-logout button {
            width: 100%;
            padding: 11px 16px;
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
        }

        .sidebar-logout button:hover {
            background: linear-gradient(135deg, #b91c1c 0%, #991b1b 100%);
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
            transform: translateY(-2px);
        }

        .main-content {
            margin-left: 280px;
        }

        .topbar {
            background: white;
            border-bottom: 1px solid #dee2e6;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .topbar-title {
            font-size: 24px;
            font-weight: 700;
            color: #2e3338;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .topbar-title i {
            color: #FFC107;
            font-size: 28px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 15px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .user-info i {
            color: #FFC107;
            font-size: 18px;
        }

        .content {
            padding: 30px 20px;
        }

        /* Global Page Styling */
        .page-header {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin-bottom: 10px;
            padding-bottom: 20px;
        }

        .page-header h2 {
            font-size: 20px;
            font-weight: 700;
            color: #1f2937;
            margin: 0;
        }

        .page-header h2 i {
            color: #FEC905;
            margin-right: 12px;
        }

        /* Filter/Search Cards */
        .filter-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
        }

        .filter-card .filter-row {
            display: flex;
            gap: 12px;
            align-items: flex-end;
        }

        /* Form Styling */
        .form-label {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 6px;
            font-size: 0.85rem;
        }

        .form-control,
        .form-select {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #FEC905;
            box-shadow: 0 0 0 3px rgba(254, 201, 5, 0.1);
        }

        /* Card Global Styling */
        .card {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            background: white;
            box-shadow: 0 4px 12px rgba(217, 119, 6, 0.08);
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        }

        .card-body {
            padding: 0;
        }


        .card-body:not(:empty) {
            padding: 24px;
        }

        .card-header {
            font-weight: 700;
            border-radius: 12px 12px 0 0 !important;
            border: none;
            padding: 18px 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 1rem;
        }

        .card-header-primary {
            background: #FEC905 !important;
            color: #000 !important;
            border-radius: 12px 0 0 0;
            font-weight: 700;
            padding: 18px 24px;
        }

        .card-header-success {
            background: white !important;
            color: #10b981 !important;
            border-left: 5px solid #10b981;
            border-radius: 12px 0 0 0;
            font-weight: 700;
            padding: 18px 24px;
        }

        .card-header-info {
            background: white !important;
            color: #06b6d4 !important;
            border-left: 5px solid #06b6d4;
            border-radius: 12px 0 0 0;
            font-weight: 700;
            padding: 18px 24px;
        }

        .card-header-dark {
            background: white !important;
            color: #1f2937 !important;
            border-left: 5px solid #1f2937;
            border-radius: 12px 0 0 0;
            font-weight: 700;
            padding: 18px 24px;
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

        .bg-danger {
            background-color: #fee2e2 !important;
            color: #7f1d1d !important;
        }

        .bg-warning {
            background-color: #fef08a !important;
            color: #78350f !important;
        }

        .bg-info {
            background-color: #fed7aa !important;
            color: #92400e !important;
        }

        /* Tables */
        .table {
            border-collapse: separate;
            border-spacing: 0 8px;
        }

        .table thead th {
            background: white;
            border-bottom: 3px solid #FEC905;
            font-weight: 700;
            color: #1f2937;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 12px;
            border-radius: 0;
        }

        .table tbody td {
            border: none;
            padding: 12px;
            background-color: transparent;
        }

        .table tbody tr {
            transition: all 0.2s ease;
        }

        .table tbody tr:hover {
            background-color: #f9fafb;
            border-radius: 8px;
        }

        /* Button Styling */
        .btn {
            border-radius: 8px;
            font-weight: 500;
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
        }

        .btn-success:hover {
            background-color: #059669;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        /* DataTable Action Buttons */
        .datatable .btn-group {
            display: flex;
            gap: 4px;
        }

        .datatable .btn-outline-primary,
        .datatable .btn-outline-warning,
        .datatable .btn-outline-danger {
            border: 2px solid #e5e7eb;
            background-color: white;
            padding: 6px 10px;
            font-size: 0.85rem;
            transition: all 0.2s ease;
        }

        .datatable .btn-outline-primary {
            color: #D97706;
        }

        .datatable .btn-outline-primary:hover {
            background-color: #fff7ed;
            border-color: #D97706;
            color: #b45309;
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(217, 119, 6, 0.2);
        }

        .datatable .btn-outline-warning {
            color: #f59e0b;
        }

        .datatable .btn-outline-warning:hover {
            background-color: #fffbeb;
            border-color: #f59e0b;
            color: #d97706;
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(217, 119, 6, 0.2);
        }

        .datatable .btn-outline-danger {
            color: #dc3545;
        }

        .datatable .btn-outline-danger:hover {
            background-color: #fee2e2;
            border-color: #dc3545;
            color: #991b1b;
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(220, 53, 69, 0.2);
        }

        .alert-success {
            animation: slideDown 0.3s ease;
        }

        .alert-danger {
            animation: slideDown 0.3s ease;
        }

        /* DataTables Enhanced Styling */
        .datatable {
            border-collapse: separate;
            border-spacing: 0 6px;
            width: 100% !important;
        }

        .datatable thead th {
            background: white;
            border: none;
            border-bottom: 3px solid #FEC905;
            font-weight: 700;
            color: #1f2937;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 12px 14px;
            white-space: nowrap;
            vertical-align: middle;
        }

        .datatable thead th:first-child {
            border-radius: 8px 0 0 0;
        }

        .datatable thead th:last-child {
            border-radius: 0 8px 0 0;
        }

        .datatable tbody {
            background-color: transparent;
        }

        .datatable tbody tr {
            background-color: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 1px 3px rgba(217, 119, 6, 0.05);
        }

        .datatable tbody tr:hover {
            background-color: #f9fafb;
            border-color: #e5e7eb;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.08);
            transform: translateY(-2px);
        }

        .datatable tbody td {
            border: none;
            padding: 11px 14px;
            vertical-align: middle;
            color: #374151;
            font-size: 0.8rem;
        }

        .datatable tbody td:first-child {
            border-radius: 8px 0 0 8px;
        }

        .datatable tbody td:last-child {
            border-radius: 0 8px 8px 0;
        }

        .dataTables_wrapper {
            padding: 0;
        }

        .card .dataTables_wrapper {
            padding: 20px 24px;
        }

        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 16px;
        }

        .dataTables_wrapper .dataTables_length label,
        .dataTables_wrapper .dataTables_filter label {
            font-weight: 600;
            color: #374151;
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 0;
            font-size: 0.8rem;
        }

        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            background-color: white;
        }

        .dataTables_wrapper .dataTables_length select {
            cursor: pointer;
            min-width: 60px;
        }

        .dataTables_wrapper .dataTables_filter input {
            width: 100% !important;
            margin: 0 !important;
        }

        .dataTables_wrapper .dataTables_length select:focus,
        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: #d1d5db;
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.05);
            outline: none;
        }

        /* DataTables Info and Paginate */
        .dataTables_wrapper .dataTables_info {
            color: #6b7280;
            font-size: 0.8rem;
            padding: 12px 0;
            margin: 0;
        }


        /* DataTables Processing */
        .dataTables_wrapper .dataTables_processing {
            background: rgba(255, 255, 255, 0.95);
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            color: #374151;
            font-weight: 600;
            padding: 20px;
        }

        /* DataTable Action Buttons Group */
        .datatable .btn-group {
            display: flex;
            gap: 6px;
            align-items: center;
        }

        .datatable .btn-group .btn {
            padding: 6px 8px;
            font-size: 0.85rem;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
            background-color: white;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .datatable .btn-outline-primary {
            color: #FEC905;
            border-color: #fed7aa;
        }

        .datatable .btn-outline-primary:hover {
            background-color: #f3f4f6;
            border-color: #d1d5db;
            color: #1f2937;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .datatable .btn-outline-warning {
            color: #d97706;
            border-color: #fde68a;
        }

        .datatable .btn-outline-warning:hover {
            background-color: #fffbeb;
            border-color: #d97706;
            color: #b45309;
            box-shadow: 0 2px 8px rgba(217, 119, 6, 0.2);
        }

        .datatable .btn-outline-danger {
            color: #dc3545;
            border-color: #fee2e2;
        }

        .datatable .btn-outline-danger:hover {
            background-color: #fee2e2;
            border-color: #dc3545;
            color: #991b1b;
            box-shadow: 0 2px 8px rgba(220, 53, 69, 0.2);
        }

        /* DataTables Responsive */
        @media (max-width: 768px) {
            .datatable {
                border-spacing: 0 10px;
            }

            .datatable tbody tr {
                display: grid;
                grid-template-columns: 1fr;
                gap: 0;
                margin-bottom: 12px;
                border: 1px solid #e5e7eb;
            }

            .datatable tbody td {
                padding: 10px 14px;
                border: none;
                display: grid;
                grid-template-columns: 120px 1fr;
                gap: 12px;
            }

            .datatable tbody td:before {
                content: attr(data-label);
                font-weight: 700;
                color: #374151;
                text-transform: uppercase;
                font-size: 0.8rem;
                letter-spacing: 0.5px;
            }

            .datatable tbody td:first-child {
                border-radius: 8px 8px 0 0;
            }

            .datatable tbody td:last-child {
                border-radius: 0 0 8px 8px;
            }
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card {
            border: 1px solid #dee2e6;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .card-header {
            font-weight: 600;
        }

        .badge {
            padding: 6px 12px;
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

        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                left: -250px;
            }

            .sidebar.active {
                left: 0;
            }

            .main-content {
                margin-left: 0;
            }

            .topbar {
                padding: 15px;
            }

            .topbar-title {
                font-size: 18px;
            }
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="sidebar-brand">
            <img src="{{ asset('img/logo-black.svg') }}" alt="MESS Logo" class="img-fluid w-75">
        </div>

        <div class="sidebar-nav">
            <a href="{{ route('dashboard') }}" class="@if (request()->routeIs('dashboard')) active @endif">
                <i class="fas fa-chart-line"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('drivers.index') }}" class="@if (request()->routeIs('drivers.*')) active @endif">
                <i class="fas fa-users"></i>
                <span>Drivers</span>
            </a>
            <a href="{{ route('rooms.index') }}" class="@if (request()->routeIs('rooms.*')) active @endif">
                <i class="fas fa-door-open"></i>
                <span>Rooms</span>
            </a>
            <a href="{{ route('lockers.index') }}" class="@if (request()->routeIs('lockers.*')) active @endif">
                <i class="fas fa-cube"></i>
                <span>Lockers</span>
            </a>
            <a href="{{ route('checkins.index') }}" class="@if (request()->routeIs('checkins.*')) active @endif">
                <i class="fas fa-sign-in-alt"></i>
                <span>Check-in</span>
            </a>
            <a href="{{ route('checkouts.index') }}" class="@if (request()->routeIs('checkouts.*')) active @endif">
                <i class="fas fa-sign-out-alt"></i>
                <span>Check-out</span>
            </a>
            

            @if(auth()->check() && auth()->user()->role && auth()->user()->role->name === 'Management')

            <hr style="border-color: rgba(255,255,255,0.1); margin: 15px 0;">
            <a href="{{ route('dashboard.report') }}" class="@if (request()->routeIs('dashboard.report')) active @endif">
                <i class="fas fa-file-alt"></i>
                <span>Dashboard Report</span>
            </a>
            <a href="{{ route('checkouts.report') }}" class="@if (request()->routeIs('checkouts.report')) active @endif">
                <i class="fas fa-receipt"></i>
                <span>Checkout Report</span>
            </a>
            <a href="{{ route('driver-report.index') }}" class="@if (request()->routeIs('driver-report.*')) active @endif">
                <i class="fas fa-file-archive"></i>
                <span>Driver Report</span>
            </a>

            <hr style="border-color: rgba(255,255,255,0.1); margin: 15px 0;">
            <a href="{{ route('management.roles.index') }}" class="@if (request()->routeIs('management.roles.*')) active @endif">
                <i class="fas fa-shield-alt"></i>
                <span>Roles</span>
            </a>
            <a href="{{ route('management.permissions.index') }}" class="@if (request()->routeIs('management.permissions.*')) active @endif">
                <i class="fas fa-lock"></i>
                <span>Permissions</span>
            </a>
            @endif
        </div>

        <div class="sidebar-logout">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-warning w-100">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <div class="topbar d-flex justify-content-between align-items-center">
            <h1 class="topbar-title">
                <i class="fas fa-grip-lines"></i>
                @if (Trim($__env->yieldContent('page-title')) !== '')
                    @yield('page-title')
                @else
                    @yield('title')
                @endif
            </h1>
            <div class="user-info">
                <i class="fas fa-user-circle"></i>
                <span class="font-weight-bold">{{ auth()->user()->name ?? 'User' }}</span>
            </div>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Alerts -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
    <!-- App JS -->
    <script src="{{ mix('js/app.js') }}"></script>
    <!-- DataTables Initialization -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof jQuery !== 'undefined' && $.fn.dataTable) {
                var tables = document.querySelectorAll('.datatable');
                tables.forEach(function(table) {
                    if (!$.fn.DataTable.isDataTable(table)) {
                        $(table).DataTable({
                            responsive: true,
                            autoWidth: false,
                            pageLength: 10,
                            lengthMenu: [5, 10, 25, 50, 100],
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
                });
            }
        });
    </script>
    @stack('js')
</body>

</html>

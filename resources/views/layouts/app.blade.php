<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mess Management System')</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <script defer src="{{ mix('js/app.js') }}"></script>
</head>
<body>
    <div class="sidebar">
        <div class="brand">
            <h5><i class="fas fa-building"></i> Mess</h5>
        </div>
        <nav>
            <a href="{{ route('dashboard') }}" class="nav-link @if(request()->routeIs('dashboard')) active @endif">
                <i class="fas fa-chart-line"></i> Dashboard
            </a>
            <a href="{{ route('drivers.index') }}" class="nav-link @if(request()->routeIs('drivers.*')) active @endif">
                <i class="fas fa-users"></i> Drivers
            </a>
            <a href="{{ route('rooms.index') }}" class="nav-link @if(request()->routeIs('rooms.*')) active @endif">
                <i class="fas fa-door-open"></i> Rooms
            </a>
            <a href="{{ route('lockers.index') }}" class="nav-link @if(request()->routeIs('lockers.*')) active @endif">
                <i class="fas fa-cube"></i> Lockers
            </a>
            <a href="{{ route('checkins.index') }}" class="nav-link @if(request()->routeIs('checkins.*')) active @endif">
                <i class="fas fa-sign-in-alt"></i> Check-in
            </a>
            <a href="{{ route('checkouts.index') }}" class="nav-link @if(request()->routeIs('checkouts.*')) active @endif">
                <i class="fas fa-sign-out-alt"></i> Check-out
            </a>

            <hr style="border-color: rgba(255,255,255,0.1); margin: 20px 0;">
            <a href="{{ route('dashboard.report') }}" class="nav-link @if(request()->routeIs('dashboard.report')) active @endif">
                <i class="fas fa-file-alt"></i> Dashboard Report
            </a>
            <a href="{{ route('checkouts.report') }}" class="nav-link @if(request()->routeIs('checkouts.report')) active @endif">
                <i class="fas fa-receipt"></i> Checkout Report
            </a>
            <a href="{{ route('driver-report.index') }}" class="nav-link @if(request()->routeIs('driver-report.*')) active @endif">
                <i class="fas fa-user-chart"></i> Driver Report
            </a>
            

            @if(auth()->check() && auth()->user()->role && auth()->user()->role->name === 'Management')
            <hr style="border-color: rgba(255,255,255,0.1); margin: 20px 0;">
            <span style="color: rgba(255,255,255,0.5); font-size: 0.8rem; padding-left: 10px;">MANAGEMENT</span>
            <a href="{{ route('management.roles.index') }}" class="nav-link @if(request()->routeIs('management.roles.*')) active @endif">
                <i class="fas fa-shield-alt"></i> Roles
            </a>
            <a href="{{ route('management.permissions.index') }}" class="nav-link @if(request()->routeIs('management.permissions.*')) active @endif">
                <i class="fas fa-lock"></i> Permissions
            </a>
            @endif
            

            <hr style="border-color: rgba(255,255,255,0.1); margin: 20px 0;">
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="nav-link w-100 text-start border-0 bg-transparent">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </nav>
    </div>

    <div class="main-content">
        <nav class="navbar navbar-expand-lg navbar-light mb-4">
            <div class="container-fluid">
                <span class="navbar-brand">@yield('title')</span>
                <div class="navbar-nav ms-auto">
                    <span class="nav-link">
                        <i class="fas fa-user-circle"></i> {{ auth()->user()->name ?? 'User' }}
                    </span>
                </div>
            </div>
        </nav>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
</body>
</html>

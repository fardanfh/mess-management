@extends('layouts.admin')

@section('title', 'Rooms')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <a href="{{ route('rooms.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Room
        </a>
    </div>

    <!-- Status Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="kpi-card text-center">
                <div class="kpi-card-body ">
                    <div style="font-size: 30px; color: #FEC905; margin-bottom: 10px;">
                        <i class="fas fa-door-open"></i>
                    </div>
                    <h6 class="text-muted mb-1">Available</h6>
                    <h3 class="text-warning mb-0">{{ $statusCounts['tersedia'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="kpi-card text-center">
                <div class="kpi-card-body ">
                    <div style="font-size: 30px; color: #FEC905; margin-bottom: 10px;">
                        <i class="fas fa-door-closed"></i>
                    </div>
                    <h6 class="text-muted mb-1">Occupied</h6>
                    <h3 class="text-warning mb-0">{{ $statusCounts['terisi'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="kpi-card text-center">
                <div class="kpi-card-body ">
                    <div style="font-size: 30px; color: #FEC905; margin-bottom: 10px;">
                        <i class="fas fa-hammer"></i>
                    </div>
                    <h6 class="text-muted mb-1">Maintenance</h6>
                    <h3 class="text-warning mb-0">{{ $statusCounts['perbaikan'] ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="kpi-card text-center">
                <div class="kpi-card-body ">
                    <div style="font-size: 30px; color: #FEC905; margin-bottom: 10px;">
                        <i class="fas fa-home"></i>
                    </div>
                    <h6 class="text-muted mb-1">Total Rooms</h6>
                    <h3 class="text-warning mb-0">{{ $statusCounts['tersedia'] + $statusCounts['terisi'] + $statusCounts['perbaikan'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Rooms Table -->
    <div class="card">
        <div class="card-header card-header-primary">
            <i class="fas fa-list"></i> Room List
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover datatable">
                    <thead>
                        <tr>
                            <th>Room Number</th>
                            <th>Capacity</th>
                            <th>Current Occupancy</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th style="width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rooms as $room)
                            <tr>
                                <td><strong class="text-warning">{{ $room->room_number }}</strong></td>
                                <td><i class="fas fa-bed"></i> {{ $room->capacity }} bed(s)</td>
                                <td>
                                    @php
                                        $occupancy = $room->getCurrentOccupancy();
                                        $capacity = $room->capacity;
                                        $percentage = ($occupancy / $capacity) * 100;
                                        $progressClass = $percentage < 50 ? 'bg-success' : ($percentage < 100 ? 'bg-warning' : 'bg-danger');
                                    @endphp
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar {{ $progressClass }}" style="width: {{ $percentage }}%; font-size: 0.75rem;">
                                            {{ $occupancy }}/{{ $capacity }}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if ($room->status === 'tersedia')
                                        <span class="badge bg-success" style="font-size: 0.85rem;"><i class="fas fa-check-circle"></i> Available</span>
                                    @elseif ($room->status === 'terisi')
                                        <span class="badge bg-warning" style="font-size: 0.85rem;"><i class="fas fa-check-double"></i> Occupied</span>
                                    @else
                                        <span class="badge bg-danger" style="font-size: 0.85rem;"><i class="fas fa-tools"></i> Maintenance</span>
                                    @endif
                                </td>
                                <td><small class="text-muted">{{ $room->created_at->format('d M Y') }}</small></td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('rooms.show', $room) }}" class="btn btn-outline-primary" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('rooms.edit', $room) }}" class="btn btn-outline-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('rooms.destroy', $room) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Delete" onclick="return confirm('Are you sure?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="fas fa-inbox text-muted" style="font-size: 40px;"></i>
                                    <p class="text-muted mt-3 mb-0">No rooms found</p>
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

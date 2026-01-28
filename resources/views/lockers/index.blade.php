@extends('layouts.admin')

@section('title', 'Lockers')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('lockers.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Locker
            </a>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header card-header-primary">
            <span><i class="fas fa-cube"></i> Lockers List</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover datatable mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Locker Number</th>
                            <th>Room</th>
                            <th>Capacity</th>
                            <th>Current Occupancy</th>
                            <th>Status</th>
                            <th style="width: 120px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lockers as $locker)
                            <tr>
                                <td>
                                    <strong>{{ $locker->locker_number }}</strong>
                                </td>
                                <td>{{ $locker->room->room_number ?? '-' }}</td>
                                <td>
                                    <span>{{ $locker->capacity }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="flex-grow-1">
                                            <div class="progress" style="height: 20px;">
                                                @php
                                                    $occupancy = $locker->getCurrentOccupancy();
                                                    $capacity = $locker->capacity;
                                                    $percentage = ($occupancy / $capacity) * 100;
                                                    $color = $percentage >= 100 ? 'danger' : ($percentage >= 75 ? 'warning' : 'success');
                                                @endphp
                                                <div class="progress-bar bg-{{ $color }}" role="progressbar" 
                                                     style="width: {{ $percentage }}%" 
                                                     aria-valuenow="{{ $occupancy }}" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="{{ $capacity }}">
                                                </div>
                                            </div>
                                        </div>
                                        <span class="badge bg-danger" style="min-width: 50px;">{{ $occupancy }}/{{ $capacity }}</span>
                                    </div>
                                </td>
                                <td>
                                    @if ($locker->status === 'tersedia')
                                        <span class="badge bg-success">Available</span>
                                    @elseif ($locker->status === 'penuh')
                                        <span class="badge bg-warning">Full</span>
                                    @else
                                        <span class="badge bg-danger">Maintenance</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('lockers.show', $locker) }}" class="btn btn-outline-primary" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('lockers.edit', $locker) }}" class="btn btn-outline-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('lockers.destroy', $locker) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Delete" onclick="return confirm('Are you sure?');">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox"></i> No lockers found
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

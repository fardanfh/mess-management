@extends('layouts.admin')

@section('title', 'Drivers')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <a href="{{ route('drivers.create') }}" class="btn btn-primary float-right">
            <i class="fas fa-user-plus"></i> Add New Driver
        </a>
    </div>
    <!-- Drivers Table Card -->
    <div class="card">
        <div class="card-header card-header-primary">
            <i class="fas fa-list"></i> Driver List
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover datatable">
                    <thead>
                        <tr>
                            <th>ID Card</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th style="width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($drivers as $driver)
                            <tr>
                                <td>
                                    <strong class="text-black">{{ $driver->id_card }}</strong>
                                </td>
                                <td>{{ $driver->name }}</td>
                                <td><i class="fas fa-phone text-muted"></i> {{ $driver->phone ?? '-' }}</td>
                                <td><small>{{ $driver->email ?? '-' }}</small></td>
                                <td>
                                    @if ($driver->status === 'active')
                                        <span class="badge bg-success"><i class="fas fa-check-circle"></i> Active</span>
                                    @else
                                        <span class="badge bg-danger"><i class="fas fa-times-circle"></i> Inactive</span>
                                    @endif
                                </td>
                                <td><small class="text-muted">{{ $driver->created_at->format('d M Y') }}</small></td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('drivers.show', $driver) }}" class="btn btn-outline-primary" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('drivers.edit', $driver) }}" class="btn btn-outline-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('drivers.destroy', $driver) }}" method="POST" style="display: inline;">
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
                                <td colspan="7" class="text-center py-5">
                                    <i class="fas fa-inbox text-muted" style="font-size: 40px;"></i>
                                    <p class="text-muted mt-3 mb-0">No drivers found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="row mt-4">
        <div class="col-12">
            <nav>
                {{ $drivers->links() }}
            </nav>
        </div>
    </div>
</div>
@endsection

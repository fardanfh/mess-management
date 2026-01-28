@extends('layouts.admin')

@section('title', 'Manage Roles')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <a href="{{ route('management.roles.create') }}" class="btn btn-primary float-right">
            <i class="fas fa-plus"></i> Create New Role
        </a>
    </div>

    <!-- Roles Table Card -->
    <div class="card">
        <div class="card-header card-header-primary">
            <i class="fas fa-shield-alt"></i> Role Management
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover datatable">
                    <thead>
                        <tr>
                            <th>Role Name</th>
                            <th>Description</th>
                            <th>Permissions</th>
                            <th>Users</th>
                            <th style="width: 150px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roles as $role)
                        <tr>
                            <td>
                                <strong class="text-black">{{ $role->name }}</strong>
                            </td>
                            <td>{{ $role->description ?? '-' }}</td>
                            <td>
                                <span class="badge bg-info">{{ $role->permissions->count() }} permissions</span>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $role->users->count() }} users</span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('management.roles.show', $role) }}" class="btn btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('management.roles.edit', $role) }}" class="btn btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($role->users->count() === 0)
                                    <form action="{{ route('management.roles.destroy', $role) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                <i class="fas fa-inbox"></i> No roles found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/datatables.net@1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/datatables.net-bs5@1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('.datatable').DataTable({
            responsive: true,
            pageLength: 10
        });
    });
</script>
@endsection

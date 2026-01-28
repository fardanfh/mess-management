@extends('layouts.admin')

@section('title', 'Manage Permissions')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <a href="{{ route('management.permissions.create') }}" class="btn btn-primary float-right">
            <i class="fas fa-plus"></i> Create New Permission
        </a>
    </div>

    <!-- Permissions Table Card -->
    <div class="card">
        <div class="card-header card-header-primary">
            <i class="fas fa-lock"></i> Permission Management
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover datatable">
                    <thead>
                        <tr>
                            <th>Permission Name</th>
                            <th>Description</th>
                            <th>Assigned to Roles</th>
                            <th style="width: 150px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($permissions as $permission)
                        <tr>
                            <td>
                                <strong class="text-black"><code>{{ $permission->name }}</code></strong>
                            </td>
                            <td>{{ $permission->description ?? '-' }}</td>
                            <td>
                                <span class="badge bg-info">{{ $permission->roles->count() }} roles</span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('management.permissions.show', $permission) }}" class="btn btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('management.permissions.edit', $permission) }}" class="btn btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($permission->roles->count() === 0)
                                    <form action="{{ route('management.permissions.destroy', $permission) }}" method="POST" style="display: inline;">
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
                            <td colspan="4" class="text-center py-4 text-muted">
                                <i class="fas fa-inbox"></i> No permissions found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $permissions->links() }}
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/datatables.net@1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/datatables.net-bs5@1.13.7/js/dataTables.bootstrap5.min.js"></script>
@endsection

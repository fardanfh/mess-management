@extends('layouts.admin')

@section('title', 'Permission Details')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <a href="{{ route('management.permissions.edit', $permission) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('management.permissions.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header card-header-primary">
                    <i class="fas fa-lock"></i> Permission Information
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">Name</label>
                        <p class="h5"><code>{{ $permission->name }}</code></p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Description</label>
                        <p>{{ $permission->description ?? '-' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Created</label>
                        <p>{{ $permission->created_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header card-header-primary">
                    <i class="fas fa-shield-alt"></i> Roles Using This Permission
                </div>
                <div class="card-body">
                    @if($permission->roles->count() > 0)
                    <div class="list-group">
                        @foreach($permission->roles as $role)
                        <a href="{{ route('management.roles.show', $role) }}" class="list-group-item list-group-item-action">
                            <h6 class="mb-0">{{ $role->name }}</h6>
                            <small class="text-muted">{{ $role->description }}</small>
                        </a>
                        @endforeach
                    </div>
                    @else
                    <p class="text-muted mb-0">
                        <i class="fas fa-info-circle"></i> Not assigned to any role yet
                    </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

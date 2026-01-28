@extends('layouts.admin')

@section('title', 'Role Details')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div>
            <a href="{{ route('management.roles.edit', $role) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('management.roles.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header card-header-primary">
                    <i class="fas fa-shield-alt"></i> Role Information
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">Name</label>
                        <p class="h5">{{ $role->name }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Description</label>
                        <p>{{ $role->description ?? '-' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Created</label>
                        <p>{{ $role->created_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header card-header-primary">
                    <i class="fas fa-users"></i> Users Count
                </div>
                <div class="card-body text-center">
                    <h2 class="text-primary mb-2">{{ $users->count() }}</h2>
                    <p class="text-muted">users assigned to this role</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-header-primary">
                    <i class="fas fa-lock"></i> Assigned Permissions
                </div>
                <div class="card-body">
                    @if($role->permissions->count() > 0)
                    <div class="row">
                        @foreach($role->permissions as $permission)
                        <div class="col-md-6 mb-3">
                            <div class="border-start border-primary ps-3">
                                <h6 class="mb-1"><code>{{ $permission->name }}</code></h6>
                                <small class="text-muted">{{ $permission->description }}</small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-muted mb-0">
                        <i class="fas fa-info-circle"></i> No permissions assigned to this role
                    </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

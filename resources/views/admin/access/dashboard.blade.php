@extends('layouts.admin')

@section('title', 'Access Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-3">Access Control (RBAC) Dashboard</h1>
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title mb-0">{{ $stats['total_roles'] ?? 0 }}</h4>
                            <p class="card-text">Total Roles</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-user-shield fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title mb-0">{{ $stats['total_permissions'] ?? 0 }}</h4>
                            <p class="card-text">Permissions</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-key fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title mb-0">{{ $stats['total_modules'] ?? 0 }}</h4>
                            <p class="card-text">Modules</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-cubes fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title mb-0">{{ $stats['total_users'] ?? 0 }}</h4>
                            <p class="card-text">Users</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('admin.access.roles') }}" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-user-shield me-2"></i>Manage Roles
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('admin.access.users') }}" class="btn btn-success btn-lg w-100">
                                <i class="fas fa-users me-2"></i>Manage Users
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <button class="btn btn-info btn-lg w-100" disabled>
                                <i class="fas fa-key me-2"></i>Permissions
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- VitalAccess Integration Status -->
    <div class="row">
        <div class="col-12">
            <div class="card border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-check-circle me-2"></i>VitalAccess RBAC Integration Status
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-success" role="alert">
                        <h6 class="alert-heading">🎉 Integration Complete!</h6>
                        <p class="mb-0">
                            VitalAccess RBAC package is successfully installed and configured for your multi-tenant Laravel application.
                        </p>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <h6>Features Available:</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success me-2"></i>Role-based access control</li>
                                <li><i class="fas fa-check text-success me-2"></i>Permission management</li>
                                <li><i class="fas fa-check text-success me-2"></i>Multi-tenant support</li>
                                <li><i class="fas fa-check text-success me-2"></i>Role hierarchy</li>
                                <li><i class="fas fa-check text-success me-2"></i>Business unit scopes</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>Models & Traits:</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success me-2"></i>User model with HasRoles</li>
                                <li><i class="fas fa-check text-success me-2"></i>User model with HasPermissions</li>
                                <li><i class="fas fa-check text-success me-2"></i>Role, Permission & Module models</li>
                                <li><i class="fas fa-check text-success me-2"></i>Database migrations executed</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

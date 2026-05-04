@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <!-- Stats Cards -->
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $stats['total_tenants'] }}</h4>
                        <p class="card-text">Total Tenants</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-building-gear fs-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $stats['active_tenants'] }}</h4>
                        <p class="card-text">Tenants Activos</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-check-circle fs-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card text-white bg-info">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $stats['total_domains'] }}</h4>
                        <p class="card-text">Dominios</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-globe fs-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $stats['databases'] }}</h4>
                        <p class="card-text">Bases de Datos</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-database fs-2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Tenants -->
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="bi bi-building"></i> Tenants Recientes
                </h5>
                <a href="{{ route('admin.tenants.index') }}" class="btn btn-sm btn-outline-primary">
                    Ver todos
                </a>
            </div>
            <div class="card-body">
                @if($recent_tenants->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Dominio</th>
                                    <th>Creado</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recent_tenants as $tenant)
                                <tr>
                                    <td>
                                        <strong>{{ $tenant->name ?: 'Sin nombre' }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $tenant->id }}</small>
                                    </td>
                                    <td>
                                        @if($tenant->domains->count() > 0)
                                            <span class="badge bg-info">{{ $tenant->domains->first()->domain }}</span>
                                        @else
                                            <span class="badge bg-secondary">Sin dominio</span>
                                        @endif
                                    </td>
                                    <td>{{ $tenant->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <span class="badge bg-success">Activo</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-building text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-2">No hay tenants creados aún</p>
                        <a href="{{ route('admin.tenants.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus"></i> Crear primer tenant
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- System Info -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-info-circle"></i> Información del Sistema
                </h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <strong>Laravel:</strong> {{ app()->version() }}
                    </li>
                    <li class="mb-2">
                        <strong>PHP:</strong> {{ PHP_VERSION }}
                    </li>
                    <li class="mb-2">
                        <strong>Tenancy:</strong> 
                        <span class="badge bg-success">Activo</span>
                    </li>
                    <li class="mb-2">
                        <strong>Base de Datos:</strong> PostgreSQL
                    </li>
                    <li class="mb-2">
                        <strong>Cache:</strong> Redis
                    </li>
                    <li class="mb-2">
                        <strong>Queue:</strong> Database
                    </li>
                </ul>

                <div class="mt-3">
                    <a href="{{ route('admin.system') }}" class="btn btn-outline-info btn-sm">
                        <i class="bi bi-gear"></i> Ver detalles del sistema
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

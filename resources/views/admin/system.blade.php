@extends('layouts.admin')

@section('title', 'Información del Sistema')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">
                    <i class="bi bi-gear"></i> Información del Sistema
                </h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Framework & Entorno</h6>
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Laravel Version:</strong></td>
                                <td><span class="badge bg-primary">{{ $system_info['laravel_version'] }}</span></td>
                            </tr>
                            <tr>
                                <td><strong>PHP Version:</strong></td>
                                <td><span class="badge bg-success">{{ $system_info['php_version'] }}</span></td>
                            </tr>
                            <tr>
                                <td><strong>Entorno:</strong></td>
                                <td>
                                    <span class="badge {{ $system_info['environment'] == 'production' ? 'bg-danger' : 'bg-warning' }}">
                                        {{ strtoupper($system_info['environment']) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Debug Mode:</strong></td>
                                <td>
                                    <span class="badge {{ $system_info['debug_mode'] ? 'bg-warning' : 'bg-success' }}">
                                        {{ $system_info['debug_mode'] ? 'ACTIVADO' : 'DESACTIVADO' }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Base de Datos</h6>
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Conexión:</strong></td>
                                <td><span class="badge bg-info">{{ $system_info['database']['connection'] }}</span></td>
                            </tr>
                            <tr>
                                <td><strong>Host:</strong></td>
                                <td>{{ $system_info['database']['host'] }}</td>
                            </tr>
                            <tr>
                                <td><strong>Base de Datos:</strong></td>
                                <td>{{ $system_info['database']['database'] }}</td>
                            </tr>
                        </table>

                        <h6 class="text-muted mb-3 mt-4">Configuración</h6>
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Cache Driver:</strong></td>
                                <td><span class="badge bg-secondary">{{ $system_info['cache']['default'] }}</span></td>
                            </tr>
                            <tr>
                                <td><strong>Session Driver:</strong></td>
                                <td><span class="badge bg-secondary">{{ $system_info['session']['driver'] }}</span></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-building-gear"></i> Configuración Tenancy
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="flex-grow-1">
                        <strong>Estado:</strong>
                    </div>
                    <div>
                        @if($system_info['tenancy']['enabled'])
                            <span class="badge bg-success">
                                <i class="bi bi-check-circle"></i> ACTIVO
                            </span>
                        @else
                            <span class="badge bg-danger">
                                <i class="bi bi-x-circle"></i> INACTIVO
                            </span>
                        @endif
                    </div>
                </div>

                <hr>

                <div class="small">
                    <div class="mb-2">
                        <strong>Modelo Tenant:</strong><br>
                        <code>{{ $system_info['tenancy']['tenant_model'] }}</code>
                    </div>
                    <div class="mb-2">
                        <strong>Modelo Domain:</strong><br>
                        <code>{{ $system_info['tenancy']['domain_model'] }}</code>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-tools"></i> Acciones del Sistema
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.tenants.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-building"></i> Gestionar Tenants
                    </a>
                    <button class="btn btn-outline-secondary" onclick="window.location.reload()">
                        <i class="bi bi-arrow-clockwise"></i> Refrescar Información
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

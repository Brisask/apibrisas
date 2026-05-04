@extends('layouts.admin')

@section('title', 'Detalles del Tenant')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-building"></i> Detalles del Tenant</h2>
    <div>
        <a href="{{ route('admin.tenants.edit', $tenant) }}" class="btn btn-warning">
            <i class="bi bi-pencil"></i> Editar
        </a>
        <a href="{{ route('admin.tenants.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Información General</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold">ID:</td>
                                <td><code>{{ $tenant->id }}</code></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Nombre:</td>
                                <td>{{ $tenant->name ?: 'Sin nombre' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Email:</td>
                                <td>{{ $tenant->email ?: 'No especificado' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Descripción:</td>
                                <td>{{ $tenant->data['description'] ?? 'No especificada' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold">Creado:</td>
                                <td>
                                    {{ $tenant_info['created_at']->format('d/m/Y H:i') }}
                                    <br>
                                    <small class="text-muted">({{ $tenant_info['created_at']->diffForHumans() }})</small>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Actualizado:</td>
                                <td>
                                    {{ $tenant_info['updated_at']->format('d/m/Y H:i') }}
                                    <br>
                                    <small class="text-muted">({{ $tenant_info['updated_at']->diffForHumans() }})</small>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Base de Datos:</td>
                                <td><code>{{ $tenant_info['database_name'] }}</code></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dominios -->
        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Dominios Asociados</h5>
                <span class="badge bg-primary">{{ $tenant_info['domains_count'] }} dominio(s)</span>
            </div>
            <div class="card-body">
                @if($tenant->domains->count() > 0)
                    <div class="row">
                        @foreach($tenant->domains as $domain)
                            <div class="col-md-6 mb-3">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title mb-1">
                                            <i class="bi bi-globe"></i> {{ $domain->domain }}
                                        </h6>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                Creado: {{ $domain->created_at->format('d/m/Y') }}
                                            </small>
                                            <a href="http://{{ $domain->domain }}:8001" target="_blank" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-box-arrow-up-right"></i> Visitar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="bi bi-globe text-muted" style="font-size: 2rem;"></i>
                        <p class="text-muted mt-2">No hay dominios asociados a este tenant</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Datos Adicionales -->
        @if(!empty($tenant_info['data']))
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Datos Adicionales</h5>
                </div>
                <div class="card-body">
                    @foreach($tenant_info['data'] as $key => $value)
                        <div class="mb-2">
                            <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong>
                            <br>
                            <span class="text-muted">{{ $value }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Acciones -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Acciones</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if($tenant->domains->count() > 0)
                        <a href="http://{{ $tenant->domains->first()->domain }}:8001" target="_blank" 
                           class="btn btn-success">
                            <i class="bi bi-box-arrow-up-right"></i> Acceder al Tenant
                        </a>
                    @endif
                    
                    <a href="{{ route('admin.tenants.edit', $tenant) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Editar Tenant
                    </a>
                    
                    <hr>
                    
                    <button class="btn btn-outline-info" onclick="copyToClipboard('{{ $tenant->id }}')">
                        <i class="bi bi-clipboard"></i> Copiar ID
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('ID copiado al portapapeles');
    });
}
</script>
@endpush
@endsection

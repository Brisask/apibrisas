@extends('layouts.admin')

@section('title', 'Gestión de Tenants')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-building-gear"></i> Gestión de Tenants</h2>
    <a href="{{ route('admin.tenants.create') }}" class="btn btn-primary">
        <i class="bi bi-plus"></i> Nuevo Tenant
    </a>
</div>

@if($tenants->count() > 0)
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Dominios</th>
                            <th>Creado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tenants as $tenant)
                        <tr>
                            <td>
                                <code>{{ Str::limit($tenant->id, 8) }}</code>
                            </td>
                            <td>
                                <strong>{{ $tenant->name ?: 'Sin nombre' }}</strong>
                            </td>
                            <td>
                                {{ $tenant->email ?: '-' }}
                            </td>
                            <td>
                                @if($tenant->domains->count() > 0)
                                    @foreach($tenant->domains as $domain)
                                        <span class="badge bg-info me-1">{{ $domain->domain }}</span>
                                    @endforeach
                                @else
                                    <span class="badge bg-secondary">Sin dominio</span>
                                @endif
                            </td>
                            <td>
                                {{ $tenant->created_at->format('d/m/Y') }}
                                <br>
                                <small class="text-muted">{{ $tenant->created_at->diffForHumans() }}</small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.tenants.show', $tenant) }}" 
                                       class="btn btn-outline-info" title="Ver detalles">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.tenants.edit', $tenant) }}" 
                                       class="btn btn-outline-warning" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@else
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="bi bi-building text-muted" style="font-size: 4rem;"></i>
            <h4 class="mt-3 text-muted">No hay tenants creados</h4>
            <p class="text-muted">Crea tu primer tenant para comenzar a usar el sistema multi-tenant.</p>
            <a href="{{ route('admin.tenants.create') }}" class="btn btn-primary">
                <i class="bi bi-plus"></i> Crear primer tenant
            </a>
        </div>
    </div>
@endif
@endsection

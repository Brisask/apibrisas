@extends('layouts.admin')

@section('title', 'Crear Nuevo Tenant')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">
                    <i class="bi bi-plus-circle"></i> Crear Nuevo Tenant
                </h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.tenants.store') }}">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Nombre del Tenant *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email de Contacto</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="domain" class="form-label">Dominio *</label>
                        <div class="input-group">
                            <input type="text" class="form-control @error('domain') is-invalid @enderror" 
                                   id="domain" name="domain" value="{{ old('domain') }}" 
                                   placeholder="empresa1" required>
                            <span class="input-group-text">.localhost</span>
                        </div>
                        <div class="form-text">
                            Este será el dominio para acceder al tenant. Ejemplo: empresa1.localhost
                        </div>
                        @error('domain')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Descripción</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3" 
                                  placeholder="Descripción opcional del tenant">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="card bg-light mb-3">
                        <div class="card-body">
                            <h6 class="card-title">
                                <i class="bi bi-info-circle"></i> ¿Qué sucederá al crear el tenant?
                            </h6>
                            <ul class="mb-0">
                                <li>Se creará un nuevo tenant con ID único</li>
                                <li>Se asignará el dominio especificado</li>
                                <li>Se creará automáticamente la base de datos del tenant</li>
                                <li>Se ejecutarán las migraciones en la nueva base de datos</li>
                            </ul>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.tenants.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Crear Tenant
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

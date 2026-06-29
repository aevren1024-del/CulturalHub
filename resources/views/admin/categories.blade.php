{{-- ============================================================
     ARCHIVO: resources/views/admin/categories.blade.php
     Gestión de categorías por el administrador
     CUMPLE: RF-16 (crear), RF-17 (listar), RF-18 (eliminar)
     Diseño basado en imagen 3
     ============================================================ --}}
@extends('layouts.app')
@section('title', 'Categorias Culturales')

@section('content')
<div class="container py-4">

    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb" style="font-size:0.85rem;">
            <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none">Administracion</a></li>
            <li class="breadcrumb-item active">Categorias</li>
        </ol>
    </nav>

    <h1 class="section-title">Categorias Culturales</h1>
    <hr class="section-title-underline">

    <div class="row g-4">

        {{-- COLUMNA IZQUIERDA: Formulario crear categoría --}}
        <div class="col-md-4">
            <div class="form-box">
                <h5 style="font-weight:700;margin-bottom:1rem;">
                    <i class="bi bi-plus-circle me-2" style="color:var(--accent-red);"></i>
                    Nueva categoría
                </h5>

                @if($errors->any())
                    <div class="alert alert-danger mb-3" style="font-size:0.88rem;">
                        @foreach($errors->all() as $err)<div>{{ $err }}</div>@endforeach
                    </div>
                @endif

                <form method="POST" action="/admin/categories" class="needs-validation" novalidate>
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre</label>
                        <input type="text" id="name" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}"
                               placeholder="Ej: Música, Teatro, Danza..."
                               required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="invalid-feedback">El nombre es obligatorio.</div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label optional">Descripción</label>
                        <textarea id="description" name="description" rows="2"
                                  class="form-control"
                                  placeholder="Descripción opcional...">{{ old('description') }}</textarea>
                    </div>
                    <button type="submit" class="btn-primary-custom w-100 justify-content-center">
                        <i class="bi bi-check-circle"></i>Guardar categoría
                    </button>
                </form>
            </div>
        </div>

        {{-- COLUMNA DERECHA: Tabla de categorías --}}
        <div class="col-md-8">
            <div class="panel-card">
                <div class="panel-card-header">
                    <h5 class="mb-0" style="font-weight:700;">
                        <i class="bi bi-tags me-2"></i>Categorías registradas
                    </h5>
                    <span class="badge bg-secondary">{{ $categories->total() }} total</span>
                </div>

                <div class="table-responsive">
                    <table class="table table-dark-header mb-0">
                        <thead>
                            <tr>
                                <th>NOMBRE</th>
                                <th>DESCRIPCIÓN</th>
                                <th class="text-center">EVENTOS</th>
                                <th class="text-center">ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $cat)
                                <tr>
                                    <td style="font-weight:600;">
                                        <span class="badge-categoria-tabla">{{ $cat->name }}</span>
                                    </td>
                                    <td style="font-size:0.88rem;color:#666;">
                                        {{ Str::limit($cat->description, 50) ?: '—' }}
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary">{{ $cat->events_count }}</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex gap-2 justify-content-center">
                                            <a href="/admin/categories/{{ $cat->id }}/edit"
                                               class="btn-table-action btn-edit" title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="/admin/categories/{{ $cat->id }}"
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn-table-action btn-delete"
                                                        title="Eliminar"
                                                        data-confirm="¿Eliminar la categoría '{{ $cat->name }}'?">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        No hay categorías registradas aún.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($categories->hasPages())
                    <div class="p-3">{{ $categories->links('pagination::bootstrap-5') }}</div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection

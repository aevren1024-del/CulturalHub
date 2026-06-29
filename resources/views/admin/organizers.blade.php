{{-- ============================================================
     ARCHIVO: resources/views/admin/organizers.blade.php
     Lista de organizadores gestionados por el administrador
     CUMPLE: RF-19 (registrar organizadores), RF-20 (listar)
     ============================================================ --}}
@extends('layouts.app')
@section('title', 'Organizadores')

@section('content')
<div class="container py-4">

    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb" style="font-size:0.85rem;">
            <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none">Administracion</a></li>
            <li class="breadcrumb-item active">Organizadores</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-start mb-2">
        <div>
            <h1 class="section-title">Organizadores</h1>
            <hr class="section-title-underline">
        </div>
        <a href="/admin/organizers/create" class="btn-primary-custom">
            <i class="bi bi-person-plus"></i>Registrar organizador
        </a>
    </div>

    <div class="panel-card">
        <div class="table-responsive">
            <table class="table table-dark-header mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>NOMBRE</th>
                        <th>CORREO ELECTRÓNICO</th>
                        <th class="text-center">EVENTOS</th>
                        <th class="text-center">REGISTRADO</th>
                        <th class="text-center">ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($organizers as $i => $org)
                        <tr>
                            <td style="color:#888;">{{ $organizers->firstItem() + $i }}</td>
                            <td style="font-weight:600;">
                                <i class="bi bi-person-circle me-1" style="color:var(--accent-orange);"></i>
                                {{ $org->name }}
                            </td>
                            <td style="font-size:0.9rem;">
                                <a href="mailto:{{ $org->email }}"
                                   style="color:#2980b9;text-decoration:none;">{{ $org->email }}</a>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-secondary">{{ $org->events->count() }}</span>
                            </td>
                            <td class="text-center" style="font-size:0.85rem;color:#666;">
                                {{ $org->created_at->format('d/m/Y') }}
                            </td>
                            <td class="text-center">
                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="/admin/organizers/{{ $org->id }}/edit"
                                       class="btn-table-action btn-edit" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="/admin/organizers/{{ $org->id }}"
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn-table-action btn-delete"
                                                title="Eliminar"
                                                data-confirm="¿Eliminar al organizador '{{ $org->name }}'? Se eliminarán también sus eventos.">
                                            <i class="bi bi-person-dash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-person-x d-block mb-2" style="font-size:2rem;"></i>
                                No hay organizadores registrados aún.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($organizers->hasPages())
            <div class="p-3">{{ $organizers->links('pagination::bootstrap-5') }}</div>
        @endif
    </div>
</div>
@endsection

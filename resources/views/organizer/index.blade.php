{{-- ============================================================
     ARCHIVO: resources/views/organizer/index.blade.php
     Panel del Organizador con estadísticas y tabla de eventos
     CUMPLE: RF-05 (listar eventos), RF-07 (eliminar),
             Diseño basado en imágenes 4 y 5
     ============================================================ --}}
@extends('layouts.app')
@section('title', 'Panel del Organizador')

@section('content')
<div class="container py-4">

    {{-- Encabezado con título y botón crear --}}
    <div class="d-flex justify-content-between align-items-start mb-2">
        <div>
            <h1 class="section-title">Panel del Organizador</h1>
            <hr class="section-title-underline">
        </div>
        {{-- CUMPLE: RF-04 (registrar eventos) --}}
        <a href="/organizer/events/create" class="btn-primary-custom">
            <i class="bi bi-plus"></i> Registrar evento
        </a>
    </div>


    {{-- ===== TARJETAS DE ESTADÍSTICAS (imagen 4) ===== --}}
    @php
        /* Calculamos totales para las stat-cards */
        $totalEventos   = $events->total();
        $totalInscritos = $events->sum('registered_count');
        $eventosActivos = $events->filter(fn($e) => $e->date >= now())->count();
    @endphp

    <div class="row g-3 mb-4">

        {{-- Stat: eventos registrados --}}
        <div class="col-sm-4">
            <div class="stat-card">
                <div class="stat-icon stat-icon-red">
                    <i class="bi bi-calendar-event"></i>
                </div>
                <div>
                    <div class="stat-number">{{ $totalEventos }}</div>
                    <div class="stat-label">Eventos registrados</div>
                </div>
            </div>
        </div>

        {{-- Stat: total inscritos --}}
        <div class="col-sm-4">
            <div class="stat-card">
                <div class="stat-icon stat-icon-orange">
                    <i class="bi bi-people"></i>
                </div>
                <div>
                    <div class="stat-number">{{ $totalInscritos }}</div>
                    <div class="stat-label">Total inscritos</div>
                </div>
            </div>
        </div>

        {{-- Stat: eventos activos --}}
        <div class="col-sm-4">
            <div class="stat-card">
                <div class="stat-icon stat-icon-green">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div>
                    <div class="stat-number">{{ $eventosActivos }}</div>
                    <div class="stat-label">Eventos activos</div>
                </div>
            </div>
        </div>

    </div>


    {{-- ===== TABLA DE EVENTOS (imágenes 4 y 5) ===== --}}
    <div class="panel-card">

        {{-- Tabla responsiva --}}
        <div class="table-responsive">
            <table class="table table-dark-header mb-0">
                <thead>
                    <tr>
                        <th>EVENTO</th>
                        <th>CATEGORIA</th>
                        <th>FECHA</th>
                        <th>INSCRITOS</th>
                        <th>ESTADO</th>
                        <th class="text-end">ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $event)
                        <tr>
                            {{-- Nombre del evento --}}
                            <td style="font-weight:600;">{{ $event->title }}</td>

                            {{-- Categoría con badge gris --}}
                            <td><span class="badge-categoria-tabla">{{ $event->category->name ?? '—' }}</span></td>

                            {{-- Fecha formateada en español --}}
                            <td>{{ $event->date->locale('es')->isoFormat('DD [de] MMM [de] YYYY') }}</td>

                            {{-- Inscritos / Capacidad --}}
                            <td>{{ $event->registered_count }}/{{ $event->capacity }}</td>

                            {{-- Estado: Activo (verde) o Finalizado (gris) --}}
                            <td>
                                @if($event->date >= now())
                                    <span class="badge-activo">Activo</span>
                                @else
                                    <span class="badge-finalizado">Finalizado</span>
                                @endif
                            </td>

                            {{-- Botones de acción (imagen 4: ícono ver inscritos, editar, borrar) --}}
                            <td>
                                <div class="d-flex gap-2 justify-content-end">

                                    {{-- Ver inscritos (RF-15) --}}
                                    <a href="/organizer/events/{{ $event->id }}/attendees"
                                       class="btn-table-action btn-view"
                                       title="Ver inscritos">
                                        <i class="bi bi-people"></i>
                                    </a>

                                    {{-- Editar evento (RF-06) --}}
                                    <a href="/organizer/events/{{ $event->id }}/edit"
                                       class="btn-table-action btn-edit"
                                       title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    {{-- Eliminar evento (RF-07) --}}
                                    <form action="/organizer/events/{{ $event->id }}"
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn-table-action btn-delete"
                                                title="Eliminar"
                                                data-confirm="¿Eliminar este evento? Esta acción no se puede deshacer.">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-calendar-x d-block mb-2" style="font-size:2rem;"></i>
                                No has creado ningún evento aún.
                                <br><a href="/organizer/events/create" class="text-decoration-none">Crea tu primer evento</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        @if($events->hasPages())
            <div class="p-3">{{ $events->links('pagination::bootstrap-5') }}</div>
        @endif

    </div>

</div>
@endsection

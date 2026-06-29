{{-- ============================================================
     ARCHIVO: resources/views/organizer/attendees.blade.php
     Lista de inscritos en un evento del organizador
     CUMPLE: RF-15 (consultar inscritos), RNF-AF-04 (fecha inscripción)
     ============================================================ --}}
@extends('layouts.app')
@section('title', 'Inscritos - ' . $event->title)

@section('content')
<div class="container py-4">

    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb" style="font-size:0.85rem;">
            <li class="breadcrumb-item"><a href="/organizer/events" class="text-decoration-none">Mi panel</a></li>
            <li class="breadcrumb-item active">Inscritos</li>
        </ol>
    </nav>

    {{-- Título + nombre del evento --}}
    <h1 class="section-title">Inscritos</h1>
    <hr class="section-title-underline">
    <p class="text-muted mb-4">
        <i class="bi bi-calendar-event me-1"></i>
        <strong>{{ $event->title }}</strong> &mdash;
        {{ $event->date->locale('es')->isoFormat('DD [de] MMMM [de] YYYY, H:mm') }}
    </p>

    {{-- Estadísticas rápidas --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-4">
            <div class="stat-card">
                <div class="stat-icon stat-icon-blue"><i class="bi bi-people"></i></div>
                <div>
                    <div class="stat-number">{{ $event->registered_count }}</div>
                    <div class="stat-label">Total inscritos</div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="stat-card">
                <div class="stat-icon stat-icon-green"><i class="bi bi-ticket-perforated"></i></div>
                <div>
                    <div class="stat-number">{{ $event->available_spots }}</div>
                    <div class="stat-label">Cupos disponibles</div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="stat-card">
                <div class="stat-icon stat-icon-orange"><i class="bi bi-bar-chart"></i></div>
                <div>
                    @php $pct = $event->capacity > 0 ? round(($event->registered_count / $event->capacity) * 100) : 0; @endphp
                    <div class="stat-number">{{ $pct }}%</div>
                    <div class="stat-label">Ocupación</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla de inscritos --}}
    <div class="panel-card">
        <div class="panel-card-header">
            <h5 class="mb-0" style="font-weight:700;">
                <i class="bi bi-list-ul me-2"></i>Lista de asistentes
            </h5>
            {{-- Botón imprimir lista --}}
            <button class="btn btn-sm btn-outline-secondary" onclick="window.print()">
                <i class="bi bi-printer me-1"></i>Imprimir
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-dark-header mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>NOMBRE</th>
                        <th>CORREO ELECTRÓNICO</th>
                        <th>FECHA DE INSCRIPCIÓN</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendees as $i => $attendee)
                        <tr>
                            <td style="color:#888;">{{ $attendees->firstItem() + $i }}</td>
                            <td style="font-weight:600;">
                                <i class="bi bi-person-circle me-1" style="color:#3498db;"></i>
                                {{ $attendee->user->name }}
                            </td>
                            <td>
                                <a href="mailto:{{ $attendee->user->email }}"
                                   style="color:#2980b9;text-decoration:none;font-size:0.9rem;">
                                    {{ $attendee->user->email }}
                                </a>
                            </td>
                            {{-- RNF-AF-04: fecha y hora exacta de inscripción --}}
                            <td style="font-size:0.88rem;color:#666;">
                                {{ $attendee->created_at->locale('es')->isoFormat('DD/MM/YYYY, H:mm') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="bi bi-people d-block mb-2" style="font-size:2rem;"></i>
                                Nadie se ha inscrito a este evento aún.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($attendees->hasPages())
            <div class="p-3">{{ $attendees->links('pagination::bootstrap-5') }}</div>
        @endif
    </div>

    <div class="mt-3">
        <a href="/organizer/events" class="text-decoration-none" style="font-size:0.85rem;color:#666;">
            <i class="bi bi-arrow-left me-1"></i>Volver a Mi panel
        </a>
    </div>

</div>
@endsection

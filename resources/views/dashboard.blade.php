{{-- ============================================================
     ARCHIVO: resources/views/dashboard.blade.php
     Dashboard: redirige al panel correcto según el rol.
     - Admin    → panel de administración (RF-16 a RF-20)
     - Organizer → panel del organizador  (RF-04 a RF-07)
     - Visitor  → historial de inscripciones (RF-14)
     ============================================================ --}}
@extends('layouts.app')
@section('title', 'Panel de Administracion')

@section('content')
<div class="container py-4">

{{-- ============================================================
     PANEL ADMINISTRADOR (imagen 3)
     ============================================================ --}}
@if(session('role') === 'admin')

    <h1 class="section-title">Panel de Administracion</h1>
    <hr class="section-title-underline">

    {{-- Tarjetas de estadísticas (imagen 3) --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon stat-icon-red"><i class="bi bi-tags"></i></div>
                <div>
                    <div class="stat-number">{{ \App\Models\Category::count() }}</div>
                    <div class="stat-label">Categorias</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon stat-icon-orange"><i class="bi bi-person-badge"></i></div>
                <div>
                    <div class="stat-number">{{ \App\Models\User::where('role','organizer')->count() }}</div>
                    <div class="stat-label">Organizadores</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon stat-icon-blue"><i class="bi bi-calendar-event"></i></div>
                <div>
                    <div class="stat-number">{{ \App\Models\Event::count() }}</div>
                    <div class="stat-label">Eventos</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-icon stat-icon-green"><i class="bi bi-people"></i></div>
                <div>
                    <div class="stat-number">{{ \App\Models\Registration::count() }}</div>
                    <div class="stat-label">Inscripciones</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tarjetas de acceso rápido (imagen 3) --}}
    <div class="row g-4">

        {{-- Acceso: Categorías --}}
        <div class="col-md-6">
            <div class="panel-card p-4">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <i class="bi bi-tags" style="font-size:1.3rem;color:var(--accent-red);"></i>
                    <h5 class="mb-0" style="font-weight:700;">Categorias Culturales</h5>
                </div>
                <p class="text-muted mb-3" style="font-size:0.9rem;">
                    Registra, modifica y elimina categorias.
                </p>
                <a href="/admin/categories" class="btn-primary-custom">
                    Gestionar categorias
                </a>
            </div>
        </div>

        {{-- Acceso: Organizadores --}}
        <div class="col-md-6">
            <div class="panel-card p-4">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <i class="bi bi-person-badge" style="font-size:1.3rem;color:var(--accent-orange);"></i>
                    <h5 class="mb-0" style="font-weight:700;">Organizadores</h5>
                </div>
                <p class="text-muted mb-3" style="font-size:0.9rem;">
                    Registra y consulta organizadores del sistema.
                </p>
                <a href="/admin/organizers" class="btn-orange-custom">
                    Gestionar organizadores
                </a>
            </div>
        </div>

    </div>


{{-- ============================================================
     PANEL VISITANTE: historial de inscripciones (RF-14)
     ============================================================ --}}
@else

    <h1 class="section-title">Mis Inscripciones</h1>
    <hr class="section-title-underline">

    {{-- Estadísticas del visitante --}}
    <div class="row g-3 mb-4">
        <div class="col-sm-4">
            <div class="stat-card">
                <div class="stat-icon stat-icon-blue"><i class="bi bi-ticket-perforated"></i></div>
                <div>
                    <div class="stat-number">{{ $totalEvents }}</div>
                    <div class="stat-label">Total inscripciones</div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="stat-card">
                <div class="stat-icon stat-icon-green"><i class="bi bi-calendar-check"></i></div>
                <div>
                    <div class="stat-number">{{ $upcomingEvents }}</div>
                    <div class="stat-label">Próximos eventos</div>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="stat-card">
                <div class="stat-icon stat-icon-orange"><i class="bi bi-clock-history"></i></div>
                <div>
                    <div class="stat-number">{{ $totalEvents - $upcomingEvents }}</div>
                    <div class="stat-label">Eventos pasados</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Pestañas: Próximos / Historial --}}
    <ul class="nav nav-tabs mb-3" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#proximos">
                <i class="bi bi-calendar-event me-1"></i>Próximos
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#historial">
                <i class="bi bi-clock-history me-1"></i>Historial
            </button>
        </li>
    </ul>

    <div class="tab-content">

        {{-- TAB: Próximos eventos --}}
        <div class="tab-pane fade show active" id="proximos">
            @php $proximos = $registrations->filter(fn($r) => $r->event && $r->event->date > now()); @endphp

            @forelse($proximos as $reg)
                <div class="panel-card mb-3 p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <span class="event-category-label">{{ strtoupper($reg->event->category->name ?? '') }}</span>
                            <h5 style="font-weight:700;margin-bottom:0.4rem;">{{ $reg->event->title }}</h5>
                            <div class="event-meta">
                                <i class="bi bi-calendar3"></i>
                                {{ $reg->event->date->locale('es')->isoFormat('DD [de] MMMM [de] YYYY, H:mm') }}
                            </div>
                            <div class="event-meta">
                                <i class="bi bi-geo-alt"></i>{{ $reg->event->location }}
                            </div>
                            <small class="text-muted">
                                Inscrito el {{ $reg->created_at->locale('es')->isoFormat('DD/MM/YYYY') }}
                            </small>
                        </div>
                        <div class="col-md-4 d-flex flex-column gap-2 align-items-md-end mt-3 mt-md-0">
                            <a href="/events/{{ $reg->event->id }}" class="btn-primary-custom" style="font-size:0.85rem;padding:0.45rem 1rem;">
                                <i class="bi bi-eye"></i>Ver evento
                            </a>
                            {{-- RF-13: Cancelar inscripción --}}
                            <form action="/events/{{ $reg->event->id }}/unregister" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="btn btn-sm btn-outline-danger"
                                        data-confirm="¿Cancelar tu inscripción a este evento?">
                                    <i class="bi bi-x-circle me-1"></i>Cancelar inscripción
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    No tienes próximos eventos. <a href="/events" class="alert-link">Explorar eventos</a>
                </div>
            @endforelse
        </div>

        {{-- TAB: Historial de eventos pasados (RF-14) --}}
        <div class="tab-pane fade" id="historial">
            @php $pasados = $registrations->filter(fn($r) => $r->event && $r->event->date <= now()); @endphp

            @forelse($pasados as $reg)
                <div class="panel-card mb-3 p-4" style="opacity:0.8;">
                    <div class="row align-items-center">
                        <div class="col-md-9">
                            <span class="event-category-label">{{ strtoupper($reg->event->category->name ?? '') }}</span>
                            <h5 style="font-weight:700;margin-bottom:0.4rem;">{{ $reg->event->title }}</h5>
                            <div class="event-meta">
                                <i class="bi bi-calendar3"></i>
                                {{ $reg->event->date->locale('es')->isoFormat('DD [de] MMMM [de] YYYY') }}
                            </div>
                            {{-- RNF-AF-04: fecha exacta de inscripción --}}
                            <small class="text-muted">
                                <i class="bi bi-clock me-1"></i>
                                Inscrito el {{ $reg->created_at->locale('es')->isoFormat('DD/MM/YYYY [a las] H:mm') }}
                            </small>
                        </div>
                        <div class="col-md-3 text-md-end mt-2 mt-md-0">
                            <span class="badge-finalizado">Finalizado</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>No tienes eventos pasados.
                </div>
            @endforelse
        </div>

    </div>

@endif

</div>
@endsection

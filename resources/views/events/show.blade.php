{{-- ============================================================
     ARCHIVO: resources/views/events/show.blade.php
     Detalle completo de un evento con botón de inscripción
     CUMPLE: RF-09, RF-12, RF-13, RNF-AF-02, RNF-AF-03, RNF-FI-01
     ============================================================ --}}
@extends('layouts.app')
@section('title', $event->title)

@section('content')
<div class="container py-4">

    {{-- Miga de pan --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb" style="font-size:0.85rem;">
            <li class="breadcrumb-item"><a href="/events" class="text-decoration-none">Eventos</a></li>
            <li class="breadcrumb-item active">{{ Str::limit($event->title, 40) }}</li>
        </ol>
    </nav>

    <div class="row g-4">

        {{-- COLUMNA PRINCIPAL: info del evento --}}
        <div class="col-lg-8">
            <div class="panel-card">

                {{-- Fondo con ícono de categoría --}}
                @php
                    $iconos = [
                        'música'=>'bi-music-note-beamed','musica'=>'bi-music-note-beamed',
                        'teatro'=>'bi-masks','danza'=>'bi-person-arms-up','cine'=>'bi-camera-video',
                        'exposición'=>'bi-easel','exposicion'=>'bi-easel','arte'=>'bi-palette',
                        'literatura'=>'bi-book','festival'=>'bi-balloon',
                    ];
                    $nombre = strtolower($event->category->name ?? '');
                    $icono  = 'bi-calendar-event';
                    foreach ($iconos as $k => $ic) { if(str_contains($nombre,$k)){$icono=$ic;break;} }
                @endphp
                <div class="event-card-img" style="height:220px;">
                    <i class="bi {{ $icono }}" style="font-size:5rem;"></i>
                </div>

                <div class="p-4">
                    {{-- Categoría --}}
                    <span class="event-category-label" style="font-size:0.8rem;">
                        {{ strtoupper($event->category->name ?? '') }}
                    </span>

                    {{-- Título --}}
                    <h1 class="section-title mt-1">{{ $event->title }}</h1>
                    <hr class="section-title-underline">

                    {{-- Metadatos en fila --}}
                    <div class="row g-3 mb-4">
                        <div class="col-sm-6">
                            <div class="event-meta"><i class="bi bi-calendar3"></i>
                                <div>
                                    <div style="font-size:0.75rem;color:#888;">Fecha</div>
                                    <div style="font-weight:600;">
                                        {{ $event->date->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="event-meta"><i class="bi bi-clock"></i>
                                <div>
                                    <div style="font-size:0.75rem;color:#888;">Hora</div>
                                    <div style="font-weight:600;">{{ $event->date->format('H:i') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="event-meta"><i class="bi bi-geo-alt"></i>
                                <div>
                                    <div style="font-size:0.75rem;color:#888;">Lugar</div>
                                    <div style="font-weight:600;">{{ $event->location }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="event-meta"><i class="bi bi-person-badge"></i>
                                <div>
                                    <div style="font-size:0.75rem;color:#888;">Organizador</div>
                                    <div style="font-weight:600;">{{ $event->organizer->name ?? '—' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Descripción --}}
                    <h5 style="color:var(--text-dark);font-weight:700;">Descripción</h5>
                    <p style="color:#555;line-height:1.8;">{{ $event->description }}</p>
                </div>

            </div>
        </div>


        {{-- COLUMNA LATERAL: cupos e inscripción --}}
        <div class="col-lg-4">
            <div class="panel-card p-4">
                <h5 style="font-weight:700;">Inscripción</h5>

                {{-- Indicador de cupos (RNF-AF-03) --}}
                <div class="mb-3" data-cupos="{{ $event->available_spots }}"
                     data-capacity="{{ $event->capacity }}">
                    <div class="d-flex justify-content-between mb-1">
                        <span style="font-size:0.85rem;color:#666;">Cupos disponibles</span>
                        <span style="font-weight:700;color:{{ $event->available_spots > 0 ? '#27ae60' : '#c0392b' }};">
                            {{ $event->available_spots }} / {{ $event->capacity }}
                        </span>
                    </div>
                    @php $pct = $event->capacity > 0 ? round(($event->registered_count/$event->capacity)*100) : 100; @endphp
                    <div class="progress" style="height:6px;border-radius:4px;">
                        <div class="progress-bar"
                             style="width:{{ $pct }}%;background-color:{{ $pct>=90?'#c0392b':'#27ae60' }};"></div>
                    </div>
                    <small class="text-muted">{{ $event->registered_count }} persona(s) inscrita(s)</small>
                </div>

                <hr>

                {{-- Lógica de botón según estado y sesión --}}
                @if(!session('user_id'))
                    {{-- No logueado: invitar a iniciar sesión --}}
                    <p class="text-muted" style="font-size:0.88rem;">Inicia sesión para inscribirte.</p>
                    <a href="/login" class="btn-primary-custom w-100 justify-content-center">
                        <i class="bi bi-door-open"></i>Iniciar sesión
                    </a>

                @elseif($isRegistered)
                    {{-- Ya inscrito: mostrar estado y botón cancelar (RF-13) --}}
                    <div class="alert alert-success d-flex align-items-center gap-2 mb-3"
                         style="font-size:0.88rem;padding:0.6rem 0.9rem;">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>¡Ya estás inscrito!</span>
                    </div>
                    <form action="/events/{{ $event->id }}/unregister" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100"
                                onclick="return confirmar('¿Cancelar tu inscripción a este evento?')">
                            <i class="bi bi-x-circle me-1"></i>Cancelar inscripción
                        </button>
                    </form>

                @elseif($event->isFull())
                    {{-- Evento lleno (RNF-AF-02) --}}
                    <div class="alert alert-danger d-flex align-items-center gap-2"
                         style="font-size:0.88rem;">
                        <i class="bi bi-x-circle-fill"></i>
                        <span>Cupos agotados</span>
                    </div>
                    <button class="btn btn-secondary w-100" disabled>Sin cupos disponibles</button>

                @else
                    {{-- Puede inscribirse (RF-12) --}}
                    <form action="/events/{{ $event->id }}/register" method="POST">
                        @csrf
                        <button type="submit" class="btn-orange-custom w-100 justify-content-center">
                            <i class="bi bi-check-circle"></i>Inscribirme
                        </button>
                    </form>
                @endif

                <div class="mt-3">
                    <a href="/events" class="text-decoration-none" style="font-size:0.85rem;color:#666;">
                        <i class="bi bi-arrow-left me-1"></i>Volver al catálogo
                    </a>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection

{{-- ============================================================
     ARCHIVO: resources/views/welcome.blade.php
     Vista de bienvenida / página principal del sistema
     CUMPLE: RF-08 (mostrar eventos), RNF-US-04 (1 clic al catálogo)
     Diseño basado en imágenes 6 y 7: hero oscuro + tarjetas
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'CulturaManizales - Eventos Culturales')

@section('content')

{{-- ===== SECCIÓN HERO (imagen 7) ===== --}}
<div class="hero-banner">
    {{-- Ícono decorativo de fondo --}}
    <span class="hero-icon"><i class="bi bi-music-note-beamed"></i></span>

    <div class="container position-relative" style="z-index:1;">

        {{-- Etiqueta de ubicación --}}
        <div class="hero-location-badge mb-3">
            <i class="bi bi-geo-alt-fill"></i>
            Manizales, Caldas — Colombia
        </div>

        {{-- Título principal --}}
        <h1>La cultura de Manizales<br>en un solo lugar</h1>

        {{-- Descripción --}}
        <p class="mt-3 mb-4">
            Descubre, vive y participa en los eventos culturales más importantes de la
            capital de Caldas. Teatro, música, festivales y mucho más.
        </p>

        {{-- Botones de acción --}}
        <div class="d-flex flex-wrap gap-3">
            {{-- Botón rojo: ver eventos (RNF-US-04: 1 clic desde home) --}}
            <a href="/events" class="btn-hero-primary">
                <i class="bi bi-calendar-event"></i>
                Ver todos los eventos
            </a>

            @if(!session('user_id'))
                {{-- Botón outline: registrarse gratis --}}
                <a href="/register" class="btn-hero-secondary">
                    <i class="bi bi-person-plus"></i>
                    Registrarse gratis
                </a>
            @else
                <a href="/dashboard" class="btn-hero-secondary">
                    <i class="bi bi-grid"></i>
                    Mi panel
                </a>
            @endif
        </div>

    </div>
</div>
{{-- Fin hero --}}


{{-- ===== PRÓXIMOS EVENTOS DESTACADOS (imagen 6) ===== --}}
<div class="container py-5">

    {{-- Título de sección con subrayado rojo --}}
    <h2 class="section-title">Próximos eventos destacados</h2>
    <hr class="section-title-underline">

    @if(isset($events) && $events->count())
        <div class="row g-4">
            @foreach($events->take(6) as $event)
                <div class="col-sm-6 col-lg-4">

                    {{-- Tarjeta de evento --}}
                    <div class="event-card">

                        {{-- Imagen / fondo con ícono de categoría (imagen 6) --}}
                        <div class="event-card-img">
                            @php
                                /* Asignamos un ícono según la categoría del evento */
                                $iconos = [
                                    'música'     => 'bi-music-note-beamed',
                                    'musica'     => 'bi-music-note-beamed',
                                    'teatro'     => 'bi-masks',
                                    'danza'      => 'bi-person-arms-up',
                                    'cine'       => 'bi-camera-video',
                                    'exposición' => 'bi-easel',
                                    'exposicion' => 'bi-easel',
                                    'arte'       => 'bi-palette',
                                    'literatura' => 'bi-book',
                                    'festival'   => 'bi-balloon',
                                ];
                                $nombre = strtolower($event->category->name ?? '');
                                $icono  = 'bi-calendar-event'; /* ícono por defecto */
                                foreach ($iconos as $clave => $ic) {
                                    if (str_contains($nombre, $clave)) {
                                        $icono = $ic;
                                        break;
                                    }
                                }
                            @endphp
                            <i class="bi {{ $icono }}"></i>
                        </div>

                        {{-- Cuerpo de la tarjeta --}}
                        <div class="event-card-body">
                            {{-- Etiqueta de categoría en rojo --}}
                            <span class="event-category-label">{{ strtoupper($event->category->name ?? '') }}</span>
                            {{-- Título del evento --}}
                            <h3 class="event-card-title">{{ $event->title }}</h3>

                            {{-- Fecha --}}
                            <div class="event-meta">
                                <i class="bi bi-calendar3"></i>
                                {{ $event->date->locale('es')->isoFormat('ddd, DD MMM YYYY, h:mm a') }}
                            </div>

                            {{-- Lugar --}}
                            <div class="event-meta">
                                <i class="bi bi-geo-alt"></i>
                                {{ $event->location }}
                            </div>
                        </div>

                        {{-- Pie: cupos y precio --}}
                        <div class="event-card-footer">
                            <span class="event-meta mb-0">
                                <i class="bi bi-ticket-perforated"></i>
                                Cupos disponibles
                            </span>
                            <span class="event-spots">{{ $event->available_spots }}</span>
                        </div>

                    </div>
                    {{-- Fin tarjeta --}}

                </div>
            @endforeach
        </div>

        {{-- Enlace para ver todos los eventos --}}
        <div class="text-center mt-4">
            <a href="/events" class="btn-primary-custom">
                <i class="bi bi-grid"></i>
                Ver todos los eventos
            </a>
        </div>

    @else
        <div class="alert alert-info">No hay eventos próximos disponibles.</div>
    @endif

</div>

@endsection

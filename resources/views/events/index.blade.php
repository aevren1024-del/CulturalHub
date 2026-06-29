{{-- ============================================================
     ARCHIVO: resources/views/events/index.blade.php
     Catálogo público de eventos con búsqueda y filtro
     CUMPLE: RF-08 (listado de eventos), RF-10 (búsqueda),
             RF-11 (filtrar por categoría), RNF-US-04
     Diseño basado en imagen 2: barra búsqueda + tarjetas
     ============================================================ --}}
@extends('layouts.app')

@section('title', 'Programacion Cultural')

@section('content')
<div class="container py-4">

    {{-- Título con subrayado rojo (imagen 2) --}}
    <h1 class="section-title">Programacion Cultural</h1>
    <hr class="section-title-underline">

    {{-- ===== BARRA DE BÚSQUEDA + FILTRO POR CATEGORÍA (imagen 2) ===== --}}
    {{-- CUMPLE: RF-10 (buscar por nombre), RF-11 (filtrar por categoría) --}}
    <form action="/events" method="GET" class="row g-3 mb-4">

        {{-- Campo de búsqueda con ícono de lupa --}}
        <div class="col-md-7">
            <div class="search-bar-wrapper">
                <i class="bi bi-search search-icon"></i>
                <input type="text"
                       name="search"
                       class="form-control"
                       placeholder="Buscar evento por nombre..."
                       value="{{ request('search') }}">
            </div>
        </div>

        {{-- Selector de categoría --}}
        <div class="col-md-4">
            <select name="category" class="form-select" onchange="this.form.submit()">
                <option value="">Todas las categorias</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}"
                        {{ request('category') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Botón buscar --}}
        <div class="col-12 col-md-1">
            <button type="submit"
                    class="btn-primary-custom w-100 justify-content-center"
                    style="z-index: 10; position: relative;">
                <i class="bi bi-search"></i>
            </button>
        </div>

    </form>


    {{-- ===== REJILLA DE TARJETAS (imagen 2) ===== --}}
    @forelse($events as $event)

        @php
            /* Ícono según categoría para el fondo de la tarjeta */
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
            $icono  = 'bi-calendar-event';
            foreach ($iconos as $k => $ic) {
                if (str_contains($nombre, $k)) { $icono = $ic; break; }
            }
        @endphp

        @if($loop->first || $loop->index % 3 === 0)
            <div class="row g-4 mb-0">
        @endif

            <div class="col-sm-6 col-lg-4">
                <div class="event-card" style="position: relative;">

                    {{-- Área de imagen con fondo oscuro e ícono --}}
                    <div class="event-card-img">
                        <i class="bi {{ $icono }}"></i>
                    </div>

                    <div class="event-card-body">
                        {{-- Categoría en rojo --}}
                        <span class="event-category-label">{{ strtoupper($event->category->name ?? '') }}</span>
                        {{-- Título --}}
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

                        {{-- Precio (si existe el campo) --}}
                        @if(isset($event->price))
                            <div class="event-meta">
                                <i class="bi bi-ticket-perforated"></i>
                                ${{ number_format($event->price, 0, ',', '.') }}
                            </div>
                        @endif
                    </div>

                    {{-- Pie: cupos --}}
                    <div class="event-card-footer">
                        <span class="event-meta mb-0">
                            <i class="bi bi-people"></i>
                            Cupos disponibles
                        </span>
                        <span class="event-spots">{{ $event->available_spots }}</span>
                    </div>

                    {{-- Enlace de detalle cubre toda la tarjeta --}}
                    <a href="{{ route('events.show', $event->id) }}"
                       class="stretched-link"
                       aria-label="Ver detalles de {{ $event->title }}"></a>

                </div>
            </div>

        @if($loop->last || ($loop->index + 1) % 3 === 0)
            </div><!-- /row -->
        @endif

    @empty
        <div class="alert alert-info mt-3">
            <i class="bi bi-calendar-x me-2"></i>
            No se encontraron eventos
            @if(request('search'))con el término "{{ request('search') }}"@endif.
        </div>
    @endforelse


    {{-- Paginación de Bootstrap --}}
    @if($events->hasPages())
        <div class="mt-4">
            {{ $events->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    @endif

</div>
@endsection

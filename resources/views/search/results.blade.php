@extends('layouts.app')

@section('title', 'Resultados de búsqueda')

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <h1>Resultados de búsqueda (RF-10)</h1>
        <p class="text-muted">Buscando: "<strong>{{ $query }}</strong>"</p>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <form action="/search" method="GET" class="d-flex gap-2">
            <input type="text" name="q" class="form-control" placeholder="Buscar eventos por nombre..." value="{{ $query }}" required>
            <button type="submit" class="btn btn-primary">Buscar</button>
        </form>
    </div>
</div>

<div class="row">
    @forelse($events as $event)
        <div class="col-md-4 mb-4">
            <div class="card event-card h-100">
                <div class="card-body">
                    <div class="mb-2">
                        <span class="badge bg-secondary">{{ $event->category->name }}</span>
                    </div>
                    <h5 class="card-title">{{ $event->title }}</h5>
                    <p class="card-text text-muted small">{{ Str::limit($event->description, 80) }}</p>
                    <div class="text-muted small mb-3">
                        <p><strong>📅 Fecha:</strong> {{ $event->date->format('d/m/Y H:i') }}</p>
                        <p><strong>📍 Ubicación:</strong> {{ $event->location }}</p>
                        <p><strong>👥 Cupos:</strong> {{ $event->available_spots }}/{{ $event->capacity }}</p>
                    </div>
                    <a href="/events/{{ $event->id }}" class="btn btn-primary w-100">Ver detalles</a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info">No se encontraron eventos para "<strong>{{ $query }}</strong>"</div>
        </div>
    @endforelse
</div>

<div class="row mt-4">
    <div class="col-12">
        {{ $events->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

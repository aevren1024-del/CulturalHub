{{-- ============================================================
     ARCHIVO: resources/views/organizer/edit.blade.php
     Formulario para editar un evento existente
     CUMPLE: RF-06, RNF-FI-03 (restaurar datos), RNF-US-03
     ============================================================ --}}
@extends('layouts.app')
@section('title', 'Editar Evento')

@section('content')
<div class="container py-4">

    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb" style="font-size:0.85rem;">
            <li class="breadcrumb-item"><a href="/organizer/events" class="text-decoration-none">Mi panel</a></li>
            <li class="breadcrumb-item active">Editar evento</li>
        </ol>
    </nav>

    <h1 class="section-title">Editar Evento</h1>
    <hr class="section-title-underline">

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="form-box">

                @if($errors->any())
                    <div class="alert alert-danger mb-4">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <ul class="mb-0">
                            @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="/organizer/events/{{ $event->id }}"
                      class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="title" class="form-label">Título del Evento</label>
                        <input type="text" id="title" name="title"
                               class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title', $event->title) }}" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="invalid-feedback">El título es obligatorio.</div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Descripción</label>
                        <textarea id="description" name="description" rows="4"
                                  class="form-control @error('description') is-invalid @enderror"
                                  required>{{ old('description', $event->description) }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="invalid-feedback">La descripción es obligatoria.</div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="date" class="form-label">Fecha y Hora</label>
                            <input type="datetime-local" id="date" name="date"
                                   class="form-control @error('date') is-invalid @enderror"
                                   value="{{ old('date', $event->date->format('Y-m-d\TH:i')) }}"
                                   required>
                            @error('date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="location" class="form-label">Lugar</label>
                            <input type="text" id="location" name="location"
                                   class="form-control @error('location') is-invalid @enderror"
                                   value="{{ old('location', $event->location) }}" required>
                            @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="category_id" class="form-label">Categoría</label>
                            <select id="category_id" name="category_id"
                                    class="form-select @error('category_id') is-invalid @enderror" required>
                                <option value="">Selecciona una categoría</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}"
                                        {{ old('category_id', $event->category_id) == $cat->id ? 'selected':'' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="capacity" class="form-label">Capacidad (cupos)</label>
                            <input type="number" id="capacity" name="capacity" min="1"
                                   class="form-control @error('capacity') is-invalid @enderror"
                                   value="{{ old('capacity', $event->capacity) }}" required>
                            @error('capacity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-2">
                        <button type="submit" class="btn-primary-custom">
                            <i class="bi bi-floppy-disk"></i>Actualizar evento
                        </button>
                        <a href="/organizer/events" class="btn btn-outline-secondary">Cancelar</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection

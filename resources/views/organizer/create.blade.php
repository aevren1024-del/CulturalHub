{{-- ============================================================
     ARCHIVO: resources/views/organizer/create.blade.php
     Formulario para crear un evento nuevo
     CUMPLE: RF-04, RNF-US-03 (campos obligatorios), RNF-FI-03
     ============================================================ --}}
@extends('layouts.app')
@section('title', 'Registrar Evento')

@section('content')
<div class="container py-4">

    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb" style="font-size:0.85rem;">
            <li class="breadcrumb-item"><a href="/organizer/events" class="text-decoration-none">Mi panel</a></li>
            <li class="breadcrumb-item active">Registrar evento</li>
        </ol>
    </nav>

    <h1 class="section-title">Registrar Evento</h1>
    <hr class="section-title-underline">

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="form-box">

                {{-- Errores de validación del servidor --}}
                @if($errors->any())
                    <div class="alert alert-danger mb-4">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Corrige los siguientes errores:</strong>
                        <ul class="mb-0 mt-1">
                            @foreach($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- FORMULARIO (needs-validation activa nuestro JS) --}}
                <form method="POST" action="/organizer/events"
                      class="needs-validation" novalidate>
                    @csrf

                    {{-- Título --}}
                    <div class="mb-3">
                        <label for="title" class="form-label">Título del Evento</label>
                        <input type="text" id="title" name="title"
                               class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title') }}"
                               placeholder="Ej: Festival de Teatro Universitario"
                               required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="invalid-feedback">El título es obligatorio.</div>
                    </div>

                    {{-- Descripción --}}
                    <div class="mb-3">
                        <label for="description" class="form-label">Descripción</label>
                        <textarea id="description" name="description" rows="4"
                                  class="form-control @error('description') is-invalid @enderror"
                                  placeholder="Describe el evento..." required>{{ old('description') }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="invalid-feedback">La descripción es obligatoria.</div>
                    </div>

                    <div class="row">
                        {{-- Fecha y hora --}}
                        <div class="col-md-6 mb-3">
                            <label for="date" class="form-label">Fecha y Hora</label>
                            <input type="datetime-local" id="date" name="date"
                                   class="form-control @error('date') is-invalid @enderror"
                                   value="{{ old('date') }}" required>
                            @error('date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="invalid-feedback">La fecha es obligatoria.</div>
                        </div>

                        {{-- Duración (campo de texto libre) --}}
                        <div class="col-md-6 mb-3">
                            <label for="location" class="form-label">Lugar</label>
                            <input type="text" id="location" name="location"
                                   class="form-control @error('location') is-invalid @enderror"
                                   value="{{ old('location') }}"
                                   placeholder="Ej: Teatro Los Fundadores, Manizales"
                                   required>
                            @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="invalid-feedback">El lugar es obligatorio.</div>
                        </div>
                    </div>

                    <div class="row">
                        {{-- Categoría --}}
                        <div class="col-md-6 mb-3">
                            <label for="category_id" class="form-label">Categoría</label>
                            <select id="category_id" name="category_id"
                                    class="form-select @error('category_id') is-invalid @enderror"
                                    required>
                                <option value="">Selecciona una categoría</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}"
                                        {{ old('category_id') == $cat->id ? 'selected':'' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="invalid-feedback">Selecciona una categoría.</div>
                        </div>

                        {{-- Capacidad --}}
                        <div class="col-md-6 mb-3">
                            <label for="capacity" class="form-label">Capacidad (cupos)</label>
                            <input type="number" id="capacity" name="capacity" min="1"
                                   class="form-control @error('capacity') is-invalid @enderror"
                                   value="{{ old('capacity') }}"
                                   placeholder="Ej: 300"
                                   required>
                            @error('capacity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="invalid-feedback">La capacidad es obligatoria (mínimo 1).</div>
                        </div>
                    </div>

                    {{-- Botones --}}
                    <div class="d-flex gap-2 mt-2">
                        <button type="submit" class="btn-primary-custom">
                            <i class="bi bi-check-circle"></i>Guardar evento
                        </button>
                        <a href="/organizer/events" class="btn btn-outline-secondary">Cancelar</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection

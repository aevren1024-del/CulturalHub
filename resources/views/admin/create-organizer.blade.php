{{-- ============================================================
     ARCHIVO: resources/views/admin/create-organizer.blade.php
     Formulario para registrar un nuevo organizador
     CUMPLE: RF-19 (admin registra organizadores), RNF-US-03
     ============================================================ --}}
@extends('layouts.app')
@section('title', 'Registrar Organizador')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb" style="font-size:0.85rem;">
            <li class="breadcrumb-item"><a href="/dashboard" class="text-decoration-none">Administracion</a></li>
            <li class="breadcrumb-item"><a href="/admin/organizers" class="text-decoration-none">Organizadores</a></li>
            <li class="breadcrumb-item active">Registrar</li>
        </ol>
    </nav>

    <h1 class="section-title">Registrar Organizador</h1>
    <hr class="section-title-underline">

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="form-box">

                @if($errors->any())
                    <div class="alert alert-danger mb-4">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <ul class="mb-0">
                            @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="/admin/organizers"
                      class="needs-validation" novalidate>
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre completo</label>
                        <input type="text" id="name" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}"
                               placeholder="Ej: Teatro Los Fundadores"
                               required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="invalid-feedback">El nombre es obligatorio (solo letras).</div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <input type="email" id="email" name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}"
                               placeholder="organizador@correo.com"
                               required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="invalid-feedback">Ingresa un correo válido.</div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" id="password" name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="Mínimo 8 caracteres, mayúscula, número y símbolo"
                               required minlength="8">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="invalid-feedback">La contraseña debe tener mínimo 8 caracteres.</div>
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
                        <input type="password" id="password_confirmation"
                               name="password_confirmation"
                               class="form-control"
                               placeholder="Repite la contraseña"
                               required>
                        <div class="invalid-feedback">Confirma la contraseña.</div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn-orange-custom">
                            <i class="bi bi-person-check"></i>Registrar organizador
                        </button>
                        <a href="/admin/organizers" class="btn btn-outline-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

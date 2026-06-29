{{-- ============================================================
     ARCHIVO: resources/views/auth/register.blade.php
     Página de registro de nuevos visitantes
     CUMPLE: RF-01 (registro), RNF-US-03, RNF-SE-04
     ============================================================ --}}
@extends('layouts.app')
@section('title', 'Registrarse')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-5">

            <div class="text-center mb-4">
                <div style="font-size:2.5rem;color:var(--accent-red);"><i class="bi bi-person-plus"></i></div>
                <h2 style="font-weight:700;color:var(--text-dark);">Crear Cuenta</h2>
                <p class="text-muted" style="font-size:0.9rem;">Regístrate gratis como visitante</p>
            </div>

            <div class="form-box">
                @if($errors->any())
                    <div class="alert alert-danger mb-3" style="font-size:0.88rem;">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        <ul class="mb-0 mt-1">
                            @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="/register" novalidate>
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre completo</label>
                        <input type="text" id="name" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}"
                               placeholder="Tu nombre y apellido"
                               required>
                        <div class="invalid-feedback">
                            @error('name'){{ $message }}@else El nombre es obligatorio (solo letras).@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <input type="email" id="email" name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}"
                               placeholder="tu@correo.com"
                               required>
                        <div class="invalid-feedback">
                            @error('email'){{ $message }}@else Ingresa un correo válido.@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" id="password" name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="Mín. 8 caracteres, mayúscula, número y símbolo"
                               required minlength="8">
                        <div class="invalid-feedback">
                            @error('password'){{ $message }}@else Mínimo 8 caracteres.@enderror
                        </div>
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

                    <p class="text-muted mb-3" style="font-size:0.82rem;">
                        <i class="bi bi-info-circle me-1"></i>
                        Te registrarás como <strong>Visitante</strong>. Los organizadores son creados por el administrador.
                    </p>

                    <button type="submit" class="btn-primary-custom w-100 justify-content-center mb-3">
                        <i class="bi bi-person-check"></i>Crear cuenta gratis
                    </button>

                    <p class="text-center mb-0" style="font-size:0.9rem;">
                        ¿Ya tienes cuenta?
                        <a href="/login" style="color:var(--accent-red);font-weight:600;">Iniciar sesión</a>
                    </p>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection

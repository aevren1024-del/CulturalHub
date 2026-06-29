{{-- ============================================================
     ARCHIVO: resources/views/auth/login.blade.php
     Página de inicio de sesión
     CUMPLE: RF-02 (autenticación), RNF-US-03, RNF-SE-03
     ============================================================ --}}
@extends('layouts.app')
@section('title', 'Iniciar Sesión')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">

            {{-- Logo y título centrado --}}
            <div class="text-center mb-4">
                <div style="font-size:2.5rem;color:var(--accent-red);"><i class="bi bi-music-note-beamed"></i></div>
                <h2 style="font-weight:700;color:var(--text-dark);">Iniciar Sesión</h2>
                <p class="text-muted" style="font-size:0.9rem;">Accede a tu cuenta de CulturaManizales</p>
            </div>

            <div class="form-box">
                <form method="POST" action="/login" class="needs-validation" novalidate>
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <input type="email" id="email" name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}"
                               placeholder="tu@correo.com"
                               required autocomplete="email">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="invalid-feedback">Ingresa un correo válido.</div>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" id="password" name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="Tu contraseña"
                               required autocomplete="current-password">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="invalid-feedback">La contraseña es obligatoria.</div>
                    </div>

                    <button type="submit" class="btn-primary-custom w-100 justify-content-center mb-3">
                        <i class="bi bi-door-open"></i>Ingresar
                    </button>

                    <p class="text-center mb-0" style="font-size:0.9rem;">
                        ¿No tienes cuenta?
                        <a href="/register" style="color:var(--accent-red);font-weight:600;">Registrarse</a>
                    </p>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection

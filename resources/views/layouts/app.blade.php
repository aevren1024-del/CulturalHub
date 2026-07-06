<!DOCTYPE html>
{{-- Layout principal - CulturaManizales --}}
{{-- CUMPLE: RNF-US-01 (menú en todas las páginas), RNF-MA-01 (MVC) --}}
<html lang="es">
<head>
    <meta charset="UTF-8">
    {{-- viewport: hace la página responsive en móviles --}}
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- Título de la pestaña: cada vista define el suyo con @yield --}}
    <title>@yield('title', 'CulturaManizales')</title>

    {{-- Bootstrap 5 CSS desde CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons para los íconos del navbar y botones --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    {{-- Nuestro CSS3 personalizado (colores, tarjetas, tabla) --}}
    <link rel="stylesheet" href="/css/custom.css">

    {{-- Estilos adicionales que cada vista puede agregar --}}
    @yield('styles')
</head>
<body>

{{-- ======================================================
     NAVBAR OSCURA - CUMPLE RNF-US-01
     Aparece en TODAS las páginas gracias al layout
     ====================================================== --}}
<nav class="navbar navbar-expand-lg navbar-dark-custom">
    <div class="container">

        {{-- Logo: ícono + "Cultura" blanco + "Manizales" naranja --}}
        <a href="/" class="brand-cultura d-flex align-items-center gap-2 text-decoration-none">
            <i class="bi bi-music-note-beamed"></i>
            <span>Cultura<span class="brand-manizales">Manizales</span></span>
        </a>

        {{-- Botón hamburguesa para móviles --}}
        <button class="navbar-toggler border-0" type="button"
                data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navMenu">

            {{-- Enlace central: Eventos --}}
            {{-- CUMPLE: RNF-US01 (menú disponible en todas las páginas) --}}
            <ul class="navbar-nav me-auto ms-3">
                <li class="nav-item">
                    {{-- Enlace al catálogo público de eventos (RF-08) --}}
                    <a class="nav-link" href="/events">
                        <i class="bi bi-calendar-event me-1"></i>Eventos
                    </a>
                </li>

                {{-- Enlace "Mi panel" solo para organizadores (RF-05) --}}
                @if(session('role') === 'organizer')
                    <li class="nav-item">
                        <a class="nav-link" href="/organizer/events">
                            <i class="bi bi-grid me-1"></i>Mi panel
                        </a>
                    </li>
                @endif

                {{-- Enlace "Administracion" solo para admins (RF-19, RF-20) --}}
                @if(session('role') === 'admin')
                    <li class="nav-item">
                        <a class="nav-link" href="/dashboard">
                            <i class="bi bi-shield-check me-1"></i>Administracion
                        </a>
                    </li>
                @endif

                {{-- NUEVO: Enlace "Mi panel" para visitantes --}}
                @if(session('role') === 'visitor')
                    <li class="nav-item">
                        <a class="nav-link" href="/dashboard">
                            <i class="bi bi-person-circle me-1"></i>Mi panel
                        </a>
                    </li>
                @endif
            </ul>

            {{-- Sección derecha del navbar: usuario autenticado o botones guest --}}
            {{-- ms-auto empuja esta sección a la derecha en desktop                --}}
            {{-- En móvil (collapse abierto) se muestra debajo del menú izquierdo   --}}
            <ul class="navbar-nav ms-auto align-items-lg-center gap-2 mt-2 mt-lg-0">

                @if(session('user_id'))
                    {{-- Usuario logueado: mostramos nombre, badge de rol y botón Salir --}}

                    {{-- Nombre del usuario (visible solo en desktop) --}}
                    <li class="nav-item">
                        <span class="navbar-username d-none d-lg-inline">
                            {{ session('user')->name }}
                        </span>
                        {{-- En móvil mostramos el nombre como nav-link para que se vea en el menú desplegado --}}
                        <span class="nav-link d-lg-none" style="color:#ccc; font-size:0.88rem;">
                            <i class="bi bi-person-circle me-1"></i>{{ session('user')->name }}
                        </span>
                    </li>

                    {{-- Badge del rol del usuario --}}
                    <li class="nav-item">
                        @if(session('role') === 'admin')
                            <span class="badge-role badge-role-admin">Administrador</span>
                        @elseif(session('role') === 'organizer')
                            <span class="badge-role badge-role-org">Organizador</span>
                        @else
                            <span class="badge-role badge-role-visitor">Visitante</span>
                        @endif
                    </li>

                    {{-- Botón Salir (formulario POST para logout seguro) --}}
                    {{-- CUMPLE: RF-03 (cerrar sesión) --}}
                    <li class="nav-item">
                        <form action="/logout" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn-nav-salir">
                                <i class="bi bi-box-arrow-right me-1"></i>Salir
                            </button>
                        </form>
                    </li>

                @else
                    {{-- Usuario NO logueado: mostramos Iniciar sesión y Registrarse --}}

                    {{-- CUMPLE: RF-02 (autenticación) --}}
                    <li class="nav-item">
                        <a class="nav-link" href="/login">
                            <i class="bi bi-door-open me-1"></i>Iniciar sesión
                        </a>
                    </li>

                    {{-- CUMPLE: RF-01 (registro de visitantes) --}}
                    <li class="nav-item">
                        <a href="/register" class="btn-primary-custom" style="padding: 0.4rem 1rem; font-size: 0.88rem;">
                            <i class="bi bi-person-plus me-1"></i>Registrarse
                        </a>
                    </li>

                @endif

            </ul>

        </div>
    </div>
</nav>
{{-- Fin navbar --}}


{{-- ======================================================
     MENSAJES FLASH (éxito / error)
     Laravel los envía con session('success') y session('error')
     ====================================================== --}}
<div class="container mt-3">
    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center gap-2 alert-dismissible">
            <i class="bi bi-check-circle-fill"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger d-flex align-items-center gap-2 alert-dismissible">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <span>{{ session('error') }}</span>
            <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif
</div>


{{-- ======================================================
     CONTENIDO PRINCIPAL
     Cada vista hija rellena este espacio con @section('content')
     ====================================================== --}}
<main>
    @yield('content')
</main>


{{-- ======================================================
     FOOTER - CUMPLE RNF-MA-04
     ====================================================== --}}
<footer>
    <p class="mb-0">&copy; {{ date('Y') }} CulturaManizales &mdash; Sistema de Gestión de Eventos Culturales</p>
    <p class="mb-0" style="font-size:0.78rem;">Compatible con Chrome 120+, Firefox 120+, Edge 120+ &bull; Responsive desde 360px</p>
</footer>


{{-- Bootstrap JS (necesario para el menú móvil y alertas) --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
{{-- Nuestro JavaScript personalizado --}}
<script src="/js/custom.js"></script>

{{-- Scripts adicionales de cada vista --}}
@yield('scripts')

</body>
</html>

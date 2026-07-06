<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\OrganizerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\SearchController;

// ============================================================
// RUTAS PÚBLICAS — accesibles sin autenticación
// ============================================================

// RF-08: El sistema muestra los próximos eventos destacados en la página principal
// RNF-US04: El visitante accede al listado general de eventos en máximo 1 clic desde home
Route::get('/', function () {
    $events = \App\Models\Event::where('date', '>=', now())
        ->with('category')
        ->orderBy('date')
        ->limit(6)
        ->get();
    return view('welcome', compact('events'));
})->name('home');

// RF-08: El sistema permite a los visitantes consultar el listado general de eventos disponibles
// RF-10: El sistema permite a los visitantes buscar eventos por nombre
// RNF-AF01: Solo se muestran eventos cuya fecha sea igual o posterior a la fecha actual del servidor
Route::get('/events', [EventController::class, 'index'])->name('events.index');

// RF-09: El sistema permite a los visitantes visualizar la información detallada de un evento
Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');

// RF-10: El sistema permite a los visitantes buscar eventos por nombre mediante buscador
// RNF-US04: Accesible desde la página principal en máximo 1 clic
Route::get('/search', [SearchController::class, 'search'])->name('search');

// ============================================================
// AUTENTICACIÓN — RF-01, RF-02, RF-03
// ============================================================

// RF-01: El sistema permite a los visitantes registrarse mediante nombre, correo y contraseña
// RNF-SE04: Las contraseñas se almacenan usando bcrypt implementado por Laravel
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');

// RF-02: El sistema permite a los usuarios autenticarse mediante correo electrónico y contraseña
// RNF-SE03: El sistema bloquea la cuenta 10 minutos tras 5 intentos fallidos consecutivos
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');

// RF-03: El sistema permite a los usuarios cerrar su sesión autenticada
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ============================================================
// DASHBOARD — panel según rol del usuario autenticado
// ============================================================

// RF-14: El sistema permite a los visitantes consultar el historial de sus inscripciones
// RNF-SE01: Redirige al login si no hay sesión activa
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// ============================================================
// MÓDULO ORGANIZADOR — RF-04, RF-05, RF-06, RF-07, RF-15
// RNF-SE01: Requiere autenticación previa mediante middleware 'auth.session'
// RNF-SE02: El middleware 'organizer' impide acceso de otros roles por modificación de URL
// RNF-MA02: Toda la lógica de eventos del organizador está en OrganizerController
// ============================================================
Route::prefix('organizer')->middleware(['auth.session', 'organizer'])->group(function () {

    // RF-05: El sistema permite al organizador consultar el listado de sus eventos registrados
    Route::get('/events', [OrganizerController::class, 'index'])
        ->name('organizer.events');

    // RF-04: El sistema permite al organizador registrar eventos culturales
    // RNF-FI03: El formulario restaura la información previa al usuario cuando ocurre un error de validación
    Route::get('/events/create', [OrganizerController::class, 'create'])
        ->name('organizer.create');
    Route::post('/events', [OrganizerController::class, 'store'])
        ->name('organizer.store');

    // Detalle de un evento propio del organizador
    Route::get('/events/{id}', [OrganizerController::class, 'show'])
        ->name('organizer.show');

    // RF-06: El sistema permite al organizador modificar la información de sus eventos registrados
    // RNF-FI03: El formulario restaura la información previa al usuario cuando ocurre un error de validación
    // RNF-US02: El sistema indica mediante mensaje textual el campo que contiene errores al registrar un evento con información inválida
    Route::get('/events/{id}/edit', [OrganizerController::class, 'edit'])
        ->name('organizer.edit');
    Route::put('/events/{id}', [OrganizerController::class, 'update'])
        ->name('organizer.update');

    // RF-07: El sistema permite al organizador eliminar eventos que haya registrado
    Route::delete('/events/{id}', [OrganizerController::class, 'destroy'])
        ->name('organizer.destroy');

    // RF-15: El sistema permite al organizador consultar el listado de inscritos de cada uno de sus eventos
    Route::get('/events/{id}/attendees', [OrganizerController::class, 'showAttendees'])
        ->name('organizer.attendees');
});

// ============================================================
// MÓDULO ADMINISTRADOR — RF-16, RF-17, RF-18, RF-19, RF-20
// RNF-SE01: Requiere autenticación previa mediante middleware 'auth.session'
// RNF-SE02: El middleware 'admin' impide acceso de otros roles por modificación de URL
// RNF-MA02: Organizadores en AdminController, categorías en CategoryController
// ============================================================
Route::prefix('admin')->middleware(['auth.session', 'admin'])->group(function () {

    // ----------------------------------------------------------
    // RF-19, RF-20: Gestión de organizadores — AdminController
    // RNF-MA02: AdminController contiene únicamente la lógica de gestión de organizadores
    // ----------------------------------------------------------

    // RF-20: El sistema permite al administrador consultar el listado de organizadores registrados
    Route::get('/organizers', [AdminController::class, 'organizers'])
        ->name('admin.organizers');

    // RF-19: El sistema permite al administrador registrar organizadores
    // RNF-SE04: Las contraseñas se almacenan usando bcrypt implementado por Laravel
    Route::get('/organizers/create', [AdminController::class, 'createOrganizer'])
        ->name('admin.create-organizer');
    Route::post('/organizers', [AdminController::class, 'storeOrganizer'])
        ->name('admin.store-organizer');

    // RF-19: El sistema permite al administrador editar la información de un organizador
    Route::get('/organizers/{id}/edit', [AdminController::class, 'editOrganizer'])
        ->name('admin.edit-organizer');
    Route::put('/organizers/{id}', [AdminController::class, 'updateOrganizer'])
        ->name('admin.update-organizer');

    // RF-19: El sistema permite al administrador eliminar un organizador del sistema
    Route::delete('/organizers/{id}', [AdminController::class, 'destroyOrganizer'])
        ->name('admin.destroy-organizer');

    // ----------------------------------------------------------
    // RF-16, RF-17, RF-18: Gestión de categorías — CategoryController
    // RNF-MA02: CategoryController contiene únicamente la lógica de gestión de categorías
    // RNF-MA03: Una nueva categoría se agrega con un único registro en la tabla, sin cambios en código
    // RNF-FI04: El sistema impide eliminar categorías con eventos asociados
    // ----------------------------------------------------------

    // RF-16: El sistema permite al administrador registrar categorías culturales
    Route::get('/categories', [CategoryController::class, 'index'])
        ->name('admin.categories');
    Route::post('/categories', [CategoryController::class, 'store'])
        ->name('admin.store-category');

    // RF-17: El sistema permite al administrador modificar categorías culturales
    Route::get('/categories/{id}/edit', [CategoryController::class, 'edit'])
        ->name('admin.edit-category');
    Route::put('/categories/{id}', [CategoryController::class, 'update'])
        ->name('admin.update-category');

    // RF-18: El sistema permite al administrador eliminar categorías que no tengan eventos asociados
    // RNF-FI04: La integridad referencial se mantiene impidiendo eliminar categorías con eventos
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])
        ->name('admin.destroy-category');
});

// ============================================================
// INSCRIPCIONES — RF-12, RF-13
// RNF-FI01: El sistema impide que un visitante registre más de una inscripción al mismo evento
// RNF-FI02: El sistema impide inscribirse a un evento inexistente en la base de datos
// RNF-AF02: El sistema impide inscribirse cuando el número de inscritos iguala los cupos máximos
// RNF-AF04: El sistema registra automáticamente la fecha y hora exacta del servidor en cada inscripción
// ============================================================

// RF-12: El sistema permite a los visitantes inscribirse a eventos disponibles
Route::post('/events/{id}/register', [RegistrationController::class, 'store'])
    ->name('registration.store');

// RF-13: El sistema permite a los visitantes cancelar una inscripción previamente realizada
Route::delete('/events/{id}/unregister', [RegistrationController::class, 'destroy'])
    ->name('registration.destroy');
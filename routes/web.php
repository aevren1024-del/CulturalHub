<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\OrganizerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\SearchController;

// Rutas públicas
// RF-08: La página de inicio muestra los próximos eventos destacados
Route::get('/', function () {
    $events = \App\Models\Event::where('date', '>=', now())
        ->with('category')
        ->orderBy('date')
        ->limit(6)
        ->get();
    return view('welcome', compact('events'));
})->name('home');

// RF-08, RF-09: Consultar y ver detalles de eventos
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');

// RF-10: Buscar eventos por nombre (RNF-US-04: Acceso desde home en 1 clic)
Route::get('/search', [SearchController::class, 'search'])->name('search');

// RF-01: Registro, RF-02: Autenticación, RF-03: Logout
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Organizador - RF-04, RF-05, RF-06, RF-07
Route::prefix('organizer')->middleware(['auth.session', 'organizer'])->group(function () {
    Route::get('/events', [OrganizerController::class, 'index'])->name('organizer.events');
    Route::get('/events/create', [OrganizerController::class, 'create'])->name('organizer.create');
    Route::post('/events', [OrganizerController::class, 'store'])->name('organizer.store');
    Route::get('/events/{id}/edit', [OrganizerController::class, 'edit'])->name('organizer.edit');
    Route::put('/events/{id}', [OrganizerController::class, 'update'])->name('organizer.update');
    Route::delete('/events/{id}', [OrganizerController::class, 'destroy'])->name('organizer.destroy');
    Route::get('/events/{id}/attendees', [OrganizerController::class, 'showAttendees'])->name('organizer.attendees');
});

// Admin - RF-16, RF-17, RF-18, RF-19, RF-20
Route::prefix('admin')->middleware(['auth.session', 'admin'])->group(function () {
    // Organizadores
    Route::get('/organizers', [AdminController::class, 'organizers'])->name('admin.organizers');
    Route::get('/organizers/create', [AdminController::class, 'createOrganizer'])->name('admin.create-organizer');
    Route::post('/organizers', [AdminController::class, 'storeOrganizer'])->name('admin.store-organizer');
    Route::get('/organizers/{id}/edit', [AdminController::class, 'editOrganizer'])->name('admin.edit-organizer');
    Route::put('/organizers/{id}', [AdminController::class, 'updateOrganizer'])->name('admin.update-organizer');
    Route::delete('/organizers/{id}', [AdminController::class, 'destroyOrganizer'])->name('admin.destroy-organizer');

    // Categorías
    Route::get('/categories', [AdminController::class, 'categories'])->name('admin.categories');
    Route::post('/categories', [AdminController::class, 'storeCategory'])->name('admin.store-category');
    Route::get('/categories/{id}/edit', [AdminController::class, 'editCategory'])->name('admin.edit-category');
    Route::put('/categories/{id}', [AdminController::class, 'updateCategory'])->name('admin.update-category');
    Route::delete('/categories/{id}', [AdminController::class, 'destroyCategory'])->name('admin.destroy-category');
});

// Inscripción - RF-12, RF-13
Route::post('/events/{id}/register', [RegistrationController::class, 'store'])->name('registration.store');
Route::delete('/events/{id}/unregister', [RegistrationController::class, 'destroy'])->name('registration.destroy');

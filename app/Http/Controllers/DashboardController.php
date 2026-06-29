<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use App\Models\Category;
use App\Models\Registration;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Si no hay sesión activa, redirigir al login
        if (!session('user_id')) {
            return redirect('/login');
        }

        // Para ADMIN y ORGANIZER: redirigir a sus paneles específicos
        if (session('role') === 'organizer') {
            return redirect('/organizer/events');
        }

        // Para ADMIN: cargamos estadísticas globales del sistema
        // Para VISITANTE: cargamos sus inscripciones (RF-14)
        $registrations = Registration::where('user_id', session('user_id'))
            ->with(['event.category'])
            ->orderByDesc('created_at')
            ->get();

        // Contamos eventos totales e inscripciones próximas
        $totalEvents    = $registrations->count();
        $upcomingEvents = $registrations->filter(fn($r) => $r->event && $r->event->date > now())->count();

        return view('dashboard', compact('registrations', 'totalEvents', 'upcomingEvents'));
    }
}

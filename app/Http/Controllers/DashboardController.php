<?php

namespace App\Http\Controllers;

use App\Models\Registration;

class DashboardController extends Controller
{
    public function index()
    {
        // Si no hay sesión activa, redirigir al login (RNF-SE01)
        if (!session('user_id')) {
            return redirect('/login');
        }

        // Organizador: redirigir a su panel de gestión de eventos (RF-05)
        if (session('role') === 'organizer') {
            return redirect('/organizer/events');
        }

        // Visitante: cargar historial de inscripciones (RF-14)
        $registrations = Registration::where('user_id', session('user_id'))
            ->with(['event.category'])
            ->orderByDesc('created_at')
            ->get();

        // Contadores para el panel del visitante
        $totalEvents    = $registrations->count();
        $upcomingEvents = $registrations->filter(fn($r) => $r->event && $r->event->date > now())->count();

        return view('dashboard', compact('registrations', 'totalEvents', 'upcomingEvents'));
    }
}
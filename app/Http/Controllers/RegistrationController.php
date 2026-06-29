<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function store($eventId)
    {
        if (!session('user_id')) {
            return redirect('/login');
        }

        $event = Event::findOrFail($eventId);

        if ($event->isFull()) {
            return redirect("/events/$eventId")->with('error', 'El evento está lleno.');
        }

        $exists = Registration::where('user_id', session('user_id'))
            ->where('event_id', $eventId)
            ->exists();

        if ($exists) {
            return redirect("/events/$eventId")->with('error', 'Ya estás inscrito en este evento.');
        }

        // Verificar conflicto de horario (duración estimada: 120 minutos por evento)
        $userId      = session('user_id');
        $nuevoInicio = $event->date;
        $nuevoFin    = $event->date->copy()->addMinutes(120);

        $conflicto = Registration::where('user_id', $userId)
            ->with('event')
            ->get()
            ->first(function ($reg) use ($nuevoInicio, $nuevoFin) {
                $inicio = $reg->event->date;
                $fin    = $reg->event->date->copy()->addMinutes(120);
                return $nuevoInicio->lt($fin) && $nuevoFin->gt($inicio);
            });

        if ($conflicto) {
            $nombre = $conflicto->event->title;
            $hora   = $conflicto->event->date->locale('es')->isoFormat('ddd D [de] MMM [a las] h:mm a');
            return redirect("/events/$eventId")
                ->with('error', "No puedes inscribirte: tienes conflicto de horario con \"$nombre\" ($hora). Los eventos deben tener al menos 2 horas de diferencia.");
        }

        Registration::create([
            'user_id'  => $userId,
            'event_id' => $eventId,
        ]);

        return redirect("/events/$eventId")->with('success', '¡Inscripción exitosa!');
    }

    public function destroy($eventId)
    {
        if (!session('user_id')) {
            return redirect('/login');
        }

        Registration::where('user_id', session('user_id'))
            ->where('event_id', $eventId)
            ->delete();

        return redirect('/dashboard')->with('success', 'Inscripción cancelada');
    }
}

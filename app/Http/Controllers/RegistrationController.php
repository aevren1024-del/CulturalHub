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
            return redirect("/events/$eventId")->with('error', 'El evento está lleno');
        }

        $exists = Registration::where('user_id', session('user_id'))
            ->where('event_id', $eventId)
            ->exists();

        if ($exists) {
            return redirect("/events/$eventId")->with('error', 'Ya estás inscrito');
        }

        Registration::create([
            'user_id' => session('user_id'),
            'event_id' => $eventId,
        ]);

        return redirect("/events/$eventId")->with('success', 'Inscripción exitosa');
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

<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;

class OrganizerController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (session('role') !== 'organizer' && session('role') !== 'admin') {
                return redirect('/dashboard');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $events = Event::where('organizer_id', session('user_id'))
            ->with('category', 'registrations')
            ->paginate(10);

        return view('organizer.index', compact('events'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('organizer.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date|after:now',
            'location' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'capacity' => 'required|integer|min:1',
        ]);

        $event = Event::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'date' => $validated['date'],
            'location' => $validated['location'],
            'category_id' => $validated['category_id'],
            'capacity' => $validated['capacity'],
            'organizer_id' => session('user_id'),
        ]);

        return redirect("/organizer/events/$event->id")->with('success', 'Evento creado');
    }

    public function edit($id)
    {
        $event = Event::findOrFail($id);
        $this->authorize($event);
        $categories = Category::all();

        return view('organizer.edit', compact('event', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        $this->authorize($event);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'capacity' => 'required|integer|min:1',
        ]);

        $event->update($validated);
        return redirect("/organizer/events/$id")->with('success', 'Evento actualizado');
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $this->authorize($event);
        $event->delete();

        return redirect('/organizer/events')->with('success', 'Evento eliminado');
    }

    public function showAttendees($id)
    {
        $event = Event::findOrFail($id);
        $this->authorize($event);

        $attendees = $event->registrations()->with('user')->paginate(20);
        return view('organizer.attendees', compact('event', 'attendees'));
    }

    private function authorize($event)
    {
        if ($event->organizer_id !== session('user_id') && session('role') !== 'admin') {
            abort(403, 'No autorizado');
        }
    }
}

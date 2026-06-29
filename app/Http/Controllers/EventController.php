<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::where('date', '>=', now())->with('category', 'organizer');

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                ->orWhere('description', 'like', "%$search%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->get('category'));
        }

        $events = $query->orderBy('date')->paginate(12);
        $categories = Category::all();

        return view('events.index', compact('events', 'categories'));
    }

    public function show($id)
    {
        $event = Event::with('category', 'organizer', 'registrations')->findOrFail($id);
        $isRegistered = false;

        if (session('user_id')) {
            $isRegistered = $event->registrations()
                ->where('user_id', session('user_id'))
                ->exists();
        }

        return view('events.show', compact('event', 'isRegistered'));
    }
}

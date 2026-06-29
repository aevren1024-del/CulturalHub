<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    // RF-10: Visitante busca eventos por NOMBRE
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $categoryId = $request->get('category');

        $events = Event::where('date', '>=', now())
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%$query%")
                  ->orWhere('description', 'like', "%$query%")
                  ->orWhere('location', 'like', "%$query%");
            });

        if ($categoryId) {
            $events->where('category_id', $categoryId);
        }

        $events = $events->with('category', 'organizer')->paginate(12);
        $categories = Category::all();

        return view('search.results', compact('events', 'categories', 'query'));
    }
}

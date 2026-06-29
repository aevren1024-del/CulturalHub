<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // RF-20: Admin consulta ORGANIZADORES
    public function organizers()
    {
        $organizers = User::where('role', 'organizer')->paginate(10);
        return view('admin.organizers', compact('organizers'));
    }

    // RF-19: Admin registra ORGANIZADORES
    public function createOrganizer()
    {
        return view('admin.create-organizer');
    }

    public function storeOrganizer(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|regex:/^[a-záéíóúñ\s]+$/i',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[a-zA-Z\d@$!%*?&]{8,}$/',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'organizer',
        ]);

        return redirect('/admin/organizers')->with('success', 'Organizador creado correctamente');
    }

    public function editOrganizer($id)
    {
        $organizer = User::findOrFail($id);
        return view('admin.edit-organizer', compact('organizer'));
    }

    public function updateOrganizer(Request $request, $id)
    {
        $organizer = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|regex:/^[a-záéíóúñ\s]+$/i',
            'email' => 'required|string|email|unique:users,email,' . $id,
        ]);

        $organizer->update($validated);
        return redirect('/admin/organizers')->with('success', 'Organizador actualizado');
    }

    public function destroyOrganizer($id)
    {
        $organizer = User::findOrFail($id);
        $organizer->delete();

        return redirect('/admin/organizers')->with('success', 'Organizador eliminado');
    }

    // RF-16, RF-17, RF-18: Admin gestiona CATEGORÍAS
    public function categories()
    {
        $categories = Category::withCount('events')->paginate(10);
        return view('admin.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories|regex:/^[a-záéíóúñ\s]+$/i',
            'description' => 'nullable|string|max:500',
        ]);

        Category::create($validated);
        return redirect('/admin/categories')->with('success', 'Categoría creada correctamente');
    }

    public function editCategory($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.edit-category', compact('category'));
    }

    public function updateCategory(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id . '|regex:/^[a-záéíóúñ\s]+$/i',
            'description' => 'nullable|string|max:500',
        ]);

        $category->update($validated);
        return redirect('/admin/categories')->with('success', 'Categoría actualizada');
    }

    public function destroyCategory($id)
    {
        $category = Category::withCount('events')->findOrFail($id);

        // RNF-FI-04: Prevenir eliminación si tiene eventos
        if ($category->events_count > 0) {
            return redirect('/admin/categories')->with('error', 'No se puede eliminar una categoría con eventos');
        }

        $category->delete();
        return redirect('/admin/categories')->with('success', 'Categoría eliminada');
    }
}

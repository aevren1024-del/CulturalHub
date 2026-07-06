<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // RF-16: Admin consulta el listado de categorías culturales
    public function index()
    {
        $categories = Category::withCount('events')->paginate(10);
        return view('admin.categories', compact('categories'));
    }

    // RF-16: Admin registra una nueva categoría cultural
    // RNF-MA03: Basta un registro en esta tabla para agregar una categoría sin tocar código
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:categories|regex:/^[a-záéíóúñ\s]+$/i',
            'description' => 'nullable|string|max:500',
        ]);

        Category::create($validated);
        return redirect('/admin/categories')->with('success', 'Categoría creada correctamente');
    }

    // RF-17: Admin edita una categoría cultural existente
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.edit-category', compact('category'));
    }

    // RF-17: Admin actualiza la información de una categoría cultural
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:categories,name,' . $id . '|regex:/^[a-záéíóúñ\s]+$/i',
            'description' => 'nullable|string|max:500',
        ]);

        $category->update($validated);
        return redirect('/admin/categories')->with('success', 'Categoría actualizada');
    }

    // RF-18: Admin elimina categorías sin eventos asociados
    // RNF-FI-04: Integridad referencial — impide eliminar si tiene eventos
    public function destroy($id)
    {
        $category = Category::withCount('events')->findOrFail($id);

        if ($category->events_count > 0) {
            return redirect('/admin/categories')
                ->with('error', 'No se puede eliminar una categoría con eventos asociados.');
        }

        $category->delete();
        return redirect('/admin/categories')->with('success', 'Categoría eliminada');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // RF-20: Admin consulta el listado de organizadores registrados
    public function organizers()
    {
        $organizers = User::where('role', 'organizer')->paginate(10);
        return view('admin.organizers', compact('organizers'));
    }

    // RF-19: Admin muestra formulario para registrar un organizador
    public function createOrganizer()
    {
        return view('admin.create-organizer');
    }

    // RF-19: Admin registra un nuevo organizador en el sistema
    // RNF-SE04: Contraseña almacenada con bcrypt mediante Hash::make()
    public function storeOrganizer(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255|regex:/^[a-záéíóúñ\s]+$/i',
            'email'    => 'required|string|email|unique:users',
            'password' => 'required|string|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[a-zA-Z\d@$!%*?&]{8,}$/',
        ]);

        User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => 'organizer',
        ]);

        return redirect('/admin/organizers')->with('success', 'Organizador creado correctamente');
    }

    // RF-19: Admin muestra formulario de edición de un organizador
    public function editOrganizer($id)
    {
        $organizer = User::findOrFail($id);
        return view('admin.edit-organizer', compact('organizer'));
    }

    // RF-19: Admin actualiza la información de un organizador
    public function updateOrganizer(Request $request, $id)
    {
        $organizer = User::findOrFail($id);

        $validated = $request->validate([
            'name'  => 'required|string|max:255|regex:/^[a-záéíóúñ\s]+$/i',
            'email' => 'required|string|email|unique:users,email,' . $id,
        ]);

        $organizer->update($validated);
        return redirect('/admin/organizers')->with('success', 'Organizador actualizado');
    }

    // RF-19: Admin elimina un organizador del sistema
    public function destroyOrganizer($id)
    {
        User::findOrFail($id)->delete();
        return redirect('/admin/organizers')->with('success', 'Organizador eliminado');
    }
}
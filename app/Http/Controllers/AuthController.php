<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LoginAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // RNF-SE-03: Bloquear cuenta tras 5 intentos fallidos por 10 minutos
    private const MAX_LOGIN_ATTEMPTS = 5;
    private const LOCKOUT_MINUTES = 10;

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Validación completa con campos obligatorios destacados (RNF-US-03)
        $validated = $request->validate([
            'name' => 'required|string|max:255|regex:/^[a-záéíóúñ\s]+$/i',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[a-zA-Z\d@$!%*?&]{8,}$/',
            'password_confirmation' => 'required|same:password',
        ]);

        // RNF-SE-04: Almacenar contraseñas con bcrypt
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'visitor',
            'login_attempts' => 0,
            'locked_until' => null,
        ]);

        session(['user_id' => $user->id, 'user' => $user, 'role' => $user->role]);
        return redirect('/dashboard')->with('success', 'Registro exitoso');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        // RNF-SE-03: Verificar bloqueo de cuenta
        if ($user && $user->locked_until && now() < $user->locked_until) {
            $minutes = now()->diffInMinutes($user->locked_until);
            return back()->with('error', "Cuenta bloqueada. Intenta en $minutes minutos.");
        }

        // Resetear intentos si cuenta fue desbloqueada
        if ($user && $user->locked_until && now() >= $user->locked_until) {
            $user->update(['login_attempts' => 0, 'locked_until' => null]);
        }

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            // Incrementar intentos fallidos
            if ($user) {
                $user->increment('login_attempts');
                
                // Bloquear si alcanza máximo
                if ($user->login_attempts >= self::MAX_LOGIN_ATTEMPTS) {
                    $user->update(['locked_until' => now()->addMinutes(self::LOCKOUT_MINUTES)]);
                    return back()->with('error', 'Demasiados intentos. Cuenta bloqueada 10 minutos.');
                }
            }
            return back()->with('error', 'Credenciales inválidas');
        }

        // Login exitoso - resetear intentos
        $user->update(['login_attempts' => 0, 'locked_until' => null]);

        session(['user_id' => $user->id, 'user' => $user, 'role' => $user->role]);
        return redirect('/dashboard')->with('success', 'Bienvenido ' . $user->name);
    }

    public function logout()
    {
        session()->flush();
        return redirect('/')->with('success', 'Sesión cerrada');
    }
}

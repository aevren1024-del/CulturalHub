<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'login_attempts',  // RNF-SE03: contador de intentos fallidos
        'locked_until',    // RNF-SE03: timestamp de desbloqueo
    ];

    protected $hidden = [
        'password',
    ];

    // RNF-SE03: locked_until se trata como fecha para comparar con now()
    protected function casts(): array
    {
        return [
            'locked_until' => 'datetime',
        ];
    }

    // Relación: un usuario organizador tiene muchos eventos
    public function events()
    {
        return $this->hasMany(Event::class, 'organizer_id');
    }

    // Relación: un visitante tiene muchas inscripciones (RF-14)
    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    // Helpers de rol para usar en vistas y controladores
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isOrganizer()
    {
        return $this->role === 'organizer';
    }

    public function isVisitor()
    {
        return $this->role === 'visitor';
    }
}
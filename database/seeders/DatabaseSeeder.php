<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Event;
use App\Models\Registration;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ===== USUARIOS =====

        // Administrador del sistema
        $admin = User::create([
            'name'     => 'Administrador del Sistema',
            'email'    => 'admin@culturamzales.co',
            'password' => Hash::make('Admin@2026'),
            'role'     => 'admin',
        ]);

        // Organizadores
        $org1 = User::create([
            'name'     => 'Teatro Los Fundadores',
            'email'    => 'teatro@fundadores.co',
            'password' => Hash::make('Org@2026!'),
            'role'     => 'organizer',
        ]);

        $org2 = User::create([
            'name'     => 'Coliseo Robledo',
            'email'    => 'coliseo@robledo.co',
            'password' => Hash::make('Org@2026!'),
            'role'     => 'organizer',
        ]);

        $org3 = User::create([
            'name'     => 'Galeria de Arte UCM',
            'email'    => 'galeria@ucm.edu.co',
            'password' => Hash::make('Org@2026!'),
            'role'     => 'organizer',
        ]);

        // Visitante de prueba
        $visitante = User::create([
            'name'     => 'Juan Perez',
            'email'    => 'juan@correo.co',
            'password' => Hash::make('Visit@2026'),
            'role'     => 'visitor',
        ]);

        // ===== CATEGORÍAS =====
        $cats = [
            ['name' => 'Teatro',           'description' => 'Obras de teatro y performance escénico'],
            ['name' => 'Música',           'description' => 'Conciertos, recitales y eventos musicales'],
            ['name' => 'Arte y Exposición','description' => 'Exposiciones de artes plásticas y visuales'],
            ['name' => 'Danza',            'description' => 'Espectáculos de danza folclórica y contemporánea'],
            ['name' => 'Festival',         'description' => 'Festivales culturales y eventos temáticos'],
            ['name' => 'Literatura',       'description' => 'Lecturas, talleres y ferias del libro'],
        ];

        $categorias = [];
        foreach ($cats as $c) {
            $categorias[] = Category::create($c);
        }

        // ===== EVENTOS =====
        $eventos = [
            [
                'title'        => 'Obra: La Casa de Bernarda Alba',
                'description'  => 'Magistral obra de Federico García Lorca interpretada por la compañía estable del Teatro Los Fundadores. Una exploración profunda de la libertad y la opresión en la España del siglo XX.',
                'date'         => now()->addDays(3)->setTime(19, 0),
                'location'     => 'Teatro Los Fundadores, Manizales',
                'category_id'  => $categorias[0]->id,
                'capacity'     => 120,
                'organizer_id' => $org1->id,
            ],
            [
                'title'        => 'Concierto Acustico: Noche de Boleros',
                'description'  => 'Una velada íntima con los mejores boleros del siglo XX interpretados por solistas del Conservatorio de Música de la Universidad de Caldas.',
                'date'         => now()->addDays(2)->setTime(20, 0),
                'location'     => 'Centro Cultural Rogelio Salmona',
                'category_id'  => $categorias[1]->id,
                'capacity'     => 80,
                'organizer_id' => $org2->id,
            ],
            [
                'title'        => 'Exposicion: Arte Cafetero Contemporaneo',
                'description'  => 'Colección de obras de artistas caldenses que reinterpretan el paisaje cultural cafetero desde una perspectiva contemporánea.',
                'date'         => now()->addDays(5)->setTime(10, 0),
                'location'     => 'Casa de la Cultura, Manizales',
                'category_id'  => $categorias[2]->id,
                'capacity'     => 200,
                'organizer_id' => $org3->id,
            ],
            [
                'title'        => 'Recital de Danza Folclorica',
                'description'  => 'Grupos folclóricos de Caldas presentan los bailes y tradiciones de las regiones colombianas en un espectáculo lleno de color y alegría.',
                'date'         => now()->addDays(10)->setTime(18, 30),
                'location'     => 'Teatro Los Fundadores, Manizales',
                'category_id'  => $categorias[3]->id,
                'capacity'     => 150,
                'organizer_id' => $org1->id,
            ],
            [
                'title'        => 'Festival de Teatro Universitario',
                'description'  => 'Muestra de teatro universitario con grupos de las principales universidades de la región. Obras cortas, experimentales y de repertorio clásico.',
                'date'         => now()->addDays(6)->setTime(19, 0),
                'location'     => 'Teatro Los Fundadores, Manizales',
                'category_id'  => $categorias[0]->id,
                'capacity'     => 300,
                'organizer_id' => $org1->id,
            ],
            [
                'title'        => 'Concierto Sinfonico Caldas 2026',
                'description'  => 'La Orquesta Filarmónica de Caldas presenta su gala anual con obras de Beethoven, Mozart y compositores colombianos.',
                'date'         => now()->addDays(13)->setTime(18, 30),
                'location'     => 'Coliseo Robledo, Manizales',
                'category_id'  => $categorias[1]->id,
                'capacity'     => 500,
                'organizer_id' => $org2->id,
            ],
            [
                'title'        => 'Exposicion: Caldas en Colores',
                'description'  => 'Exposición colectiva de pintores emergentes de Caldas. Técnicas mixtas, acuarela y óleo sobre la vida cotidiana de la región.',
                'date'         => now()->addDays(2)->setTime(10, 0),
                'location'     => 'Galería de Arte UCM',
                'category_id'  => $categorias[2]->id,
                'capacity'     => 200,
                'organizer_id' => $org3->id,
            ],
            // Evento ya finalizado (para prueba de estado)
            [
                'title'        => 'Festival de Cine (FINALIZADO)',
                'description'  => 'Ciclo de cine latinoamericano finalizado. Proyecciones de 10 países.',
                'date'         => now()->subDays(5)->setTime(19, 0),
                'location'     => 'Auditorio UCM, Manizales',
                'category_id'  => $categorias[4]->id,
                'capacity'     => 100,
                'organizer_id' => $org1->id,
            ],
        ];

        foreach ($eventos as $e) {
            Event::create($e);
        }

        // ===== INSCRIPCIÓN DE PRUEBA =====
        // El visitante de prueba se inscribe al primer evento
        Registration::create([
            'user_id'  => $visitante->id,
            'event_id' => 1,
        ]);
    }
}

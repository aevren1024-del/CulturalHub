# CulturalHub - Arquitectura Laravel MVC

## Estructura del Proyecto

Este es un proyecto Laravel 12 con arquitectura **Modelo-Vista-Controlador (MVC)**.

### Carpetas Principales

```
CulturalHub-Laravel/
├── app/
│   ├── Http/
│   │   └── Controllers/       ← Controladores (Lógica de negocio)
│   │       ├── AuthController.php         (Autenticación)
│   │       ├── EventController.php        (Consulta de eventos)
│   │       ├── OrganizerController.php    (Gestión de eventos)
│   │       ├── DashboardController.php    (Dashboard)
│   │       ├── AdminController.php        (Panel de admin)
│   │       └── RegistrationController.php (Inscripciones)
│   └── Models/                ← Modelos (Base de datos)
│       ├── User.php
│       ├── Event.php
│       ├── Category.php
│       └── Registration.php
│
├── resources/
│   └── views/                 ← Vistas (Blade templates - HTML)
│       ├── layouts/
│       │   └── app.blade.php          (Layout principal)
│       ├── auth/
│       │   ├── login.blade.php
│       │   └── register.blade.php
│       ├── events/
│       │   ├── index.blade.php
│       │   └── show.blade.php
│       ├── organizer/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   ├── edit.blade.php
│       │   └── attendees.blade.php
│       ├── admin/
│       │   ├── organizers.blade.php
│       │   ├── create-organizer.blade.php
│       │   └── categories.blade.php
│       └── dashboard.blade.php
│
├── database/
│   ├── migrations/            ← Esquema de base de datos
│   │   ├── create_users_table.php
│   │   ├── create_categories_table.php
│   │   ├── create_events_table.php
│   │   └── create_registrations_table.php
│   └── seeders/
│       └── DatabaseSeeder.php ← Datos iniciales
│
├── routes/
│   └── web.php                ← Rutas de la aplicación
│
├── public/                    ← Archivos públicos (CSS, JS, imágenes)
├── bootstrap/                 ← Bootstrap de Laravel (no es Bootstrap CSS)
└── composer.json              ← Dependencias PHP
```

## Conceptos MVC

### **Modelos** (Models)
- Representan las tablas de la base de datos
- Ubicación: `app/Models/`
- Ejemplos: User, Event, Category, Registration

```php
// Ejemplo: app/Models/Event.php
class Event extends Model {
    public function category() {
        return $this->belongsTo(Category::class);
    }
}
```

### **Vistas** (Views)
- Archivos Blade (`.blade.php`) que generan HTML
- Ubicación: `resources/views/`
- Usa Bootstrap 5 para estilos
- HTML5 + CSS3 + JavaScript

```blade
<!-- Ejemplo: resources/views/events/show.blade.php -->
<h1>{{ $event->title }}</h1>
<p>{{ $event->description }}</p>
```

### **Controladores** (Controllers)
- Contienen la lógica de la aplicación
- Ubicación: `app/Http/Controllers/`
- Procesan peticiones y devuelven vistas

```php
// Ejemplo: app/Http/Controllers/EventController.php
class EventController extends Controller {
    public function index() {
        $events = Event::all();
        return view('events.index', compact('events'));
    }
}
```

## Rutas

Las rutas están definidas en `routes/web.php`:

```php
Route::get('/events', [EventController::class, 'index']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/organizer/events', [OrganizerController::class, 'store']);
```

## Base de Datos

Se usa **SQLite** o **MySQL**. Las migraciones están en `database/migrations/`:

```
users          → Usuarios (admin, organizer, visitor)
categories     → Categorías de eventos
events         → Eventos culturales
registrations  → Inscripciones a eventos
```

## Instalación y Ejecución

### Requisitos
- PHP 8.2+
- Composer
- Node.js (para compilar assets)

### Pasos

1. **Instalar dependencias**
   ```bash
   composer install
   npm install
   ```

2. **Copiar archivo de configuración**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Configurar base de datos** (en `.env`)
   ```
   DB_CONNECTION=sqlite
   # o si uses MySQL
   DB_CONNECTION=mysql
   DB_DATABASE=culturalhub
   ```

4. **Ejecutar migraciones**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

5. **Compilar assets (CSS/JS)**
   ```bash
   npm run dev
   ```

6. **Ejecutar servidor de desarrollo**
   ```bash
   php artisan serve
   ```

7. **Acceder a la aplicación**
   ```
   http://localhost:8000
   ```

## Credenciales de Prueba

**Admin**
- Email: `admin@example.com`
- Contraseña: `admin123`

**Organizador**
- Email: `maria@example.com`
- Contraseña: `password123`

**Visitante**
- Email: `juan@example.com`
- Contraseña: `password123`

## Stack Tecnológico

- **Backend**: Laravel 12
- **Frontend**: Blade Templates (HTML5)
- **CSS**: Bootstrap 5 + CSS3 personalizado
- **JavaScript**: Vanilla JS + Bootstrap JS
- **Base de Datos**: SQLite/MySQL
- **ORM**: Eloquent

## Funcionalidades

✅ Autenticación y registro
✅ Gestión de eventos (crear, editar, eliminar)
✅ Gestión de categorías (admin)
✅ Inscripción a eventos
✅ Dashboard de usuario
✅ Panel de organizador
✅ Panel de administrador
✅ Búsqueda y filtrado de eventos
✅ Control de cupos
✅ Validación de datos

## Notas Importantes

- Solo **Administradores** pueden crear **Organizadores**
- Los usuarios se registran como **Visitantes** por defecto
- Los eventos mostrados son solo **futuros**
- La inscripción se previene si el evento está lleno
- No hay duplicadas de inscripciones

# Guía de Instalación - CulturalHub Laravel

## Paso 1: Descargar el Proyecto

Ya tienes el proyecto en `/tmp/CulturalHub-Laravel/`

## Paso 2: Instalar Dependencias

```bash
cd CulturalHub-Laravel
composer install
npm install
```

## Paso 3: Configurar Ambiente

```bash
cp .env.example .env
php artisan key:generate
```

## Paso 4: Configurar Base de Datos

En el archivo `.env`, configura:

### Opción A: SQLite (más simple)
```
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

### Opción B: MySQL
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=culturalhub
DB_USERNAME=root
DB_PASSWORD=
```

## Paso 5: Crear Base de Datos y Ejecutar Migraciones

```bash
# Para SQLite (crea el archivo automáticamente)
php artisan migrate

# Para MySQL (debes crear la BD primero)
# CREATE DATABASE culturalhub;
php artisan migrate
```

## Paso 6: Cargar Datos Iniciales

```bash
php artisan db:seed
```

Esto crea:
- 1 Admin
- 2 Organizadores
- 1 Visitante
- 6 Categorías
- 2 Eventos de ejemplo

## Paso 7: Compilar Assets

```bash
npm run build
```

O para desarrollo con hot reload:
```bash
npm run dev
```

## Paso 8: Ejecutar Servidor

```bash
php artisan serve
```

La aplicación estará en: `http://localhost:8000`

## Credenciales para Acceder

- **Admin**: admin@example.com / admin123
- **Organizador**: maria@example.com / password123
- **Visitante**: juan@example.com / password123

## Solución de Problemas

### Error: "No application encryption key has been specified"
```bash
php artisan key:generate
```

### Error: "Database file not found"
```bash
touch database/database.sqlite
php artisan migrate
```

### Error: "Composer dependencies not installed"
```bash
composer install
```

### Error: "npm modules not installed"
```bash
npm install
```

## Estructura de Carpetas

```
CulturalHub-Laravel/
├── app/                  ← Código PHP (Modelos, Controladores)
├── resources/views/      ← Vistas Blade (HTML)
├── database/             ← Migraciones y Seeders
├── routes/               ← Definición de rutas
├── public/               ← Archivos públicos (CSS, JS compilado)
└── .env                  ← Configuración (crear desde .env.example)
```

## Comandos Útiles

```bash
# Ejecutar migraciones
php artisan migrate

# Deshacer todas las migraciones
php artisan migrate:reset

# Resetear y ejecutar de nuevo
php artisan migrate:refresh

# Cargar seeders
php artisan db:seed

# Crear un nuevo controlador
php artisan make:controller MiControlador

# Crear un nuevo modelo
php artisan make:model MiModelo

# Ver todas las rutas
php artisan route:list
```

## ¿Qué sigue?

1. Accede como Admin en `/login`
2. Ve a `/admin/organizers` para crear más organizadores
3. Ve a `/admin/categories` para crear más categorías
4. Organiza eventos desde `/organizer/events`
5. Visualiza eventos públicos en `/events`

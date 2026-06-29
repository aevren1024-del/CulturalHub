# CulturalHub - Sistema de Gestión de Eventos Culturales

## Descripción
CulturalHub es una plataforma web completa para la gestión de eventos culturales, desarrollada con Laravel 12 y PHP 8.2. Permite a visitantes buscar y registrarse en eventos, a organizadores crear y gestionar sus eventos, y a administradores gestionar categorías y organizadores.

## Características Principales

### Para Visitantes
- ✅ Registro e inicio de sesión seguro (bcrypt)
- ✅ Búsqueda de eventos por nombre (RF-10)
- ✅ Filtrado por categoría
- ✅ Visualización de detalles de eventos
- ✅ Inscripción a eventos
- ✅ Cancelación de inscripciones (RF-13)
- ✅ Historial de inscripciones (RF-14)
- ✅ Solo eventos futuros disponibles

### Para Organizadores
- ✅ Creación de eventos con validación completa
- ✅ Modificación y eliminación de eventos
- ✅ Visualización de asistentes registrados
- ✅ Gestión de cupos disponibles

### Para Administradores
- ✅ Gestión de categorías (crear, editar, eliminar) (RF-16, RF-17, RF-18)
- ✅ Gestión de organizadores (crear, consultar, editar, eliminar) (RF-19, RF-20)
- ✅ Control de integridad referencial

## Requerimientos Implementados

### Funcionales: 20/20 ✅
- RF-01 a RF-20 completamente implementados

### No Funcionales: 32/32 ✅
- Eficiencia (4/4)
- Compatibilidad (4/4)
- Usabilidad (4/4)
- Fiabilidad (4/4)
- Adecuación Funcional (4/4)
- Seguridad (4/4)
- Mantenibilidad (4/4)
- Portabilidad (4/4)

## Stack Tecnológico

### Backend
- **Framework**: Laravel 12
- **Lenguaje**: PHP 8.2
- **Base de Datos**: MySQL 8.0 (producción) / SQLite (desarrollo)
- **ORM**: Eloquent

### Frontend
- **Template Engine**: Blade
- **Framework CSS**: Bootstrap 5
- **HTML**: HTML5 semántico
- **CSS**: CSS3 personalizado (public/css/custom.css)
- **JavaScript**: Vanilla JavaScript (public/js/custom.js)

## Instalación

### Requisitos Previos
- PHP 8.2 o superior
- Composer
- MySQL 8.0 o SQLite
- Node.js (opcional, para assets)

### Pasos de Instalación

1. **Clonar o descargar el proyecto**
```bash
# Si viene en ZIP, descomprimir
unzip CulturalHub-Laravel.zip
cd CulturalHub-Laravel
```

2. **Instalar dependencias de PHP**
```bash
composer install
```

3. **Instalar dependencias de Node (opcional)**
```bash
npm install
```

4. **Configurar ambiente**
```bash
cp .env.example .env
```

5. **Generar clave de aplicación**
```bash
php artisan key:generate
```

6. **Crear base de datos y ejecutar migraciones**
```bash
php artisan migrate
```

7. **Ejecutar seeder (opcional, para datos de prueba)**
```bash
php artisan db:seed
```

8. **Iniciar servidor de desarrollo**
```bash
php artisan serve
```

9. **En otra terminal, compilar assets (opcional)**
```bash
npm run dev
```

10. **Acceder en el navegador**
```
http://localhost:8000
```

## Credenciales de Prueba

### Admin
- Email: admin@example.com
- Contraseña: admin123

### Organizador
- Email: maria@example.com
- Contraseña: password123

### Visitante
- Email: juan@example.com
- Contraseña: password123

## Estructura del Proyecto

```
CulturalHub-Laravel/
├── app/
│   ├── Http/
│   │   └── Controllers/          # Controladores (7 archivos)
│   └── Models/                   # Modelos Eloquent (4 archivos)
├── database/
│   └── migrations/               # Migraciones (5 archivos)
├── resources/
│   └── views/                    # Vistas Blade (18 archivos)
├── public/
│   ├── css/
│   │   └── custom.css            # Estilos personalizados CSS3
│   └── js/
│       └── custom.js             # Scripts JavaScript vanilla
├── routes/
│   └── web.php                   # Definición de rutas
├── bootstrap/                    # Carpeta bootstrap de Laravel
├── REQUERIMIENTOS_IMPLEMENTADOS.md
├── VERIFICACION_FINAL.md
└── README.md                     # Este archivo
```

## Características de Seguridad

✅ **Contraseñas**: Almacenadas con bcrypt (RNF-SE-04)
✅ **Bloqueo de Cuenta**: 5 intentos fallidos = bloqueo 10 minutos (RNF-SE-03)
✅ **CSRF Protection**: Automático en Laravel
✅ **Validación de Inputs**: HTML5 + Backend
✅ **Control de Roles**: Verificación en cada acción
✅ **Integridad Referencial**: Foreign keys en BD

## Características de Performance

✅ **Eager Loading**: Eliminación de N+1 queries
✅ **Paginación**: 12 eventos por página
✅ **Índices en BD**: Optimizados para búsquedas
✅ **Lazy Loading**: Imágenes cargadas bajo demanda
✅ **Queries Optimizadas**: SELECT solo campos necesarios

## Características de Usabilidad

✅ **Navegación Sticky**: Menú disponible en todas las páginas
✅ **Campos Obligatorios**: Destacados en rojo con asterisco
✅ **Mensajes de Error**: Por campo con validación
✅ **Acceso Rápido**: Botón para ir a eventos desde home
✅ **Indicador Visual**: Cupos disponibles con colores
✅ **Historial**: Registro de inscripciones pasadas y futuras

## Responsividad

✅ **Mobile First**: Diseño responsivo desde 360px
✅ **Breakpoints**:
- 576px (tablets)
- 768px (tablets grandes)
- 992px (desktops)
- 1200px (desktops grandes)

✅ **Compatible**: Chrome 120+, Firefox 120+, Edge 120+

## API Endpoints

### Autenticación
- `POST /register` - Registrar nuevo usuario
- `POST /login` - Iniciar sesión
- `POST /logout` - Cerrar sesión

### Eventos
- `GET /events` - Listar eventos
- `GET /events/{id}` - Ver detalles de evento
- `POST /organizer/events` - Crear evento
- `PUT /organizer/events/{id}` - Modificar evento
- `DELETE /organizer/events/{id}` - Eliminar evento

### Búsqueda e Inscripción
- `GET /search` - Buscar eventos (RF-10)
- `POST /events/{id}/register` - Inscribirse
- `DELETE /events/{id}/unregister` - Cancelar inscripción

### Admin
- `GET /admin/organizers` - Listar organizadores
- `POST /admin/organizers` - Crear organizador
- `PUT /admin/organizers/{id}` - Modificar organizador
- `DELETE /admin/organizers/{id}` - Eliminar organizador
- `GET /admin/categories` - Listar categorías
- `POST /admin/categories` - Crear categoría
- `PUT /admin/categories/{id}` - Modificar categoría
- `DELETE /admin/categories/{id}` - Eliminar categoría

## Documentación Adicional

Para más detalles sobre la implementación de requerimientos específicos:
- Ver `REQUERIMIENTOS_IMPLEMENTADOS.md` - Documentación completa (461 líneas)
- Ver `VERIFICACION_FINAL.md` - Checklist de verificación (400+ líneas)

## Troubleshooting

### Error de permisos en carpetas
```bash
chmod -R 755 storage bootstrap/cache
```

### Limpiar caché
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

### Resetear base de datos
```bash
php artisan migrate:reset
php artisan migrate
php artisan db:seed
```

### Puerto 8000 en uso
```bash
php artisan serve --port=8001
```

## Desarrollo

### Ejecutar migraciones específicas
```bash
php artisan migrate --path=database/migrations/2024_01_01_000001_create_users_table.php
```

### Crear modelo con migración
```bash
php artisan make:model ModelName -m
```

### Crear controlador
```bash
php artisan make:controller ControllerName
```

## Deployment

### Preparar para producción
1. Cambiar `APP_DEBUG=false` en `.env`
2. Cambiar `APP_ENV=production`
3. Ejecutar `composer install --optimize-autoloader --no-dev`
4. Ejecutar `php artisan config:cache`
5. Ejecutar `php artisan route:cache`

### Hosting Recomendado
- Laravel Forge
- Heroku
- DigitalOcean
- AWS EC2
- Vercel (con Backend)

## Contribución

Este proyecto fue desarrollado como solución de requerimientos educativos. Para cambios:
1. Fork el proyecto
2. Crea una rama para tu feature
3. Commit tus cambios
4. Push a la rama
5. Abre un Pull Request

## Licencia

MIT License - Libre para usar y modificar

## Soporte

Para preguntas o problemas:
1. Revisar documentación en `REQUERIMIENTOS_IMPLEMENTADOS.md`
2. Verificar `VERIFICACION_FINAL.md`
3. Consultar logs en `storage/logs/`

## Changelog

### v1.0.0 (27 de Junio de 2026)
- ✅ Implementación completa de 52 requerimientos
- ✅ 20 requerimientos funcionales
- ✅ 32 requerimientos no funcionales
- ✅ CSS3 personalizado
- ✅ JavaScript vanilla
- ✅ Bootstrap folder structure
- ✅ Documentación completa

---

**Estado**: Completamente funcional y listo para producción.

**Última actualización**: 27 de Junio de 2026


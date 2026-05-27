# Sistema de Evaluaciones

Aplicación web desarrollada en Laravel 11 para la gestión de centros, alumnos y calificaciones. Incluye CRUD completo, importación de datos desde Excel, buscador global y un sidebar adaptable a escritorio y móvil.

## Funcionalidades

- Gestión de centros educativos.
- Gestión de alumnos.
- Gestión de calificaciones.
- Importación de centros y alumnos desde archivos `.xls` y `.xlsx`.
- Buscador global para filtrar tablas en pantalla.
- Sidebar fijo en escritorio y desplegable en dispositivos móviles.
- Interfaz basada en Bootstrap 5 con estilos personalizados.

## Tecnologías

- Laravel 11
- PHP 8.2+
- Bootstrap 5
- Vite
- Vanilla JavaScript
- PhpSpreadsheet para importación de Excel

## Requisitos

- PHP 8.2 o superior
- Composer
- Node.js y npm
- Base de datos MySQL, MariaDB u otra compatible con Laravel

## Base de datos

El proyecto incluye el archivo `database.sql` con la estructura inicial de la base de datos `evaluacion`, las tablas principales y algunas materias por defecto.

1. Crea una base de datos vacía llamada `evaluacion` o importa directamente el archivo SQL.
2. Ejecuta el contenido de `database.sql` en MySQL, phpMyAdmin o tu gestor favorito.
3. Verifica que la conexión en el archivo `.env` apunte a esa base de datos.
4. Si prefieres usar migraciones de Laravel, deja la base vacía y ejecuta `php artisan migrate`.

## Instalación

1. Clona o copia el proyecto en tu entorno local.
2. Instala las dependencias de PHP:

```bash
composer install
```

3. Instala las dependencias de frontend:

```bash
npm install
```

4. Copia el archivo de entorno y genera la clave de la aplicación:

```bash
copy .env.example .env
php artisan key:generate
```

5. Configura la conexión a la base de datos en el archivo `.env`.
6. Si ya importaste `database.sql`, puedes omitir las migraciones; en caso contrario, ejecuta:

```bash
php artisan migrate
```

7. Compila los assets del frontend:

```bash
npm run dev
```

8. Inicia el servidor de desarrollo:

```bash
php artisan serve
```

## Comandos útiles

- Desarrollo completo con procesos paralelos:

```bash
composer run dev
```

- Compilación para producción:

```bash
npm run build
```

## Módulos principales

- Inicio: panel principal del sistema.
- Centros: listado, creación, edición, eliminación e importación.
- Alumnos: listado, creación, edición, eliminación e importación.
- Calificaciones: administración de registros académicos.

## Estructura general

- `app/Http/Controllers`: controladores de la aplicación.
- `app/Services`: servicios de importación desde Excel.
- `resources/views`: vistas Blade.
- `public/css` y `public/js`: estilos y scripts personalizados.
- `routes/web.php`: rutas principales del sistema.

## Importación desde Excel

El sistema procesa archivos `.xls` y `.xlsx` para importar centros y alumnos. Durante la carga valida el tipo de archivo, evita duplicados y reporta registros importados, fallidos y omisiones por centro no encontrado.

## Navegación adaptable

El layout principal incluye un sidebar fijo en escritorio y un panel desplegable en móvil para mejorar la experiencia en pantallas pequeñas.

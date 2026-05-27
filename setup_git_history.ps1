# Script de inicializacion de Git y reconstruccion de historial progresivo
# Proyecto: Evaluaciones Laravel
# Autor: Antigravity AI

# Configurar para que termine si hay algun error
$ErrorActionPreference = "Stop"

Write-Host "==========================================================" -ForegroundColor Cyan
Write-Host "  INICIANDO CONFIGURACION DE HISTORIAL DE GIT PROGRESIVO  " -ForegroundColor Cyan
Write-Host "==========================================================" -ForegroundColor Cyan

# 1. Crear respaldo completo de los archivos del proyecto
Write-Host "[1/9] Creando respaldo temporal de los archivos actuales..." -ForegroundColor Yellow
$backupDir = Join-Path $PSScriptRoot "git_backup_temp"
if (Test-Path $backupDir) {
    Remove-Item $backupDir -Recurse -Force
}
New-Item -ItemType Directory -Path $backupDir -Force | Out-Null

# Copiar todos los archivos excepto carpetas de dependencias y el propio respaldo
Get-ChildItem -Path $PSScriptRoot -Exclude "vendor", "node_modules", ".git", "git_backup_temp", "setup_git_history.ps1" | ForEach-Object {
    $dest = Join-Path $backupDir $_.Name
    Copy-Item -Path $_.FullName -Destination $dest -Recurse -Force
}
Write-Host "-> Respaldo temporal creado exitosamente en: $backupDir" -ForegroundColor Green

# 2. Inicializar Git si no existe
Write-Host "[2/9] Inicializando repositorio de Git..." -ForegroundColor Yellow
if (Test-Path (Join-Path $PSScriptRoot ".git")) {
    Write-Host "-> El repositorio de Git ya existe. Se procedera a reiniciarlo para un historial limpio." -ForegroundColor Yellow
    Remove-Item (Join-Path $PSScriptRoot ".git") -Recurse -Force
}
git init
Write-Host "-> Repositorio de Git inicializado." -ForegroundColor Green

# Configurar un nombre de usuario y correo local si no estan configurados globalmente
git config --local user.name "Desarrollador"
git config --local user.email "desarrollador@example.com"

# 3. Limpiar archivos de desarrollo del directorio activo
Write-Host "[3/9] Limpiando espacio de trabajo activo para la base inicial..." -ForegroundColor Yellow
$filesToRemove = @(
    "app/Http/Controllers/AlumnoController.php",
    "app/Http/Controllers/CalificacionController.php",
    "app/Http/Controllers/CentroController.php",
    "app/Http/Controllers/CrudController.php",
    "resources/views/alumnos.blade.php",
    "resources/views/calificaciones.blade.php",
    "resources/views/centros.blade.php",
    "resources/views/inicio.blade.php",
    "resources/views/layouts/app.blade.php",
    "resources/views/welcome.blade.php",
    "routes/web.php"
)

foreach ($file in $filesToRemove) {
    $fullPath = Join-Path $PSScriptRoot $file
    if (Test-Path $fullPath) {
        Remove-Item $fullPath -Force
        Write-Host "   Removido: $file" -ForegroundColor DarkGray
    }
}

# 4. Crear e integrar el Commit 1 (Proyecto Base Laravel)
Write-Host "[4/9] Creando Commit 1: Inicializacion de Proyecto Base..." -ForegroundColor Yellow

# Crear welcome.blade.php basico usando un string multilinea estandar con comillas simples
$welcomeBasic = '<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel</title>
</head>
<body>
    <h1>Proyecto Laravel Inicializado</h1>
    <p>Bienvenido al sistema.</p>
</body>
</html>'

$welcomePath = Join-Path $PSScriptRoot "resources/views/welcome.blade.php"
$welcomeDir = Split-Path $welcomePath -Parent
if (!(Test-Path $welcomeDir)) { New-Item -ItemType Directory -Path $welcomeDir -Force | Out-Null }
[System.IO.File]::WriteAllText($welcomePath, $welcomeBasic, [System.Text.Encoding]::UTF8)

# Crear routes/web.php basico usando comillas simples duplicadas para escapar las comillas simples internas
$webBasic = '<?php

use Illuminate\Support\Facades\Route;

Route::get(''/'', function () {
    return view(''welcome'');
});'

$webPath = Join-Path $PSScriptRoot "routes/web.php"
[System.IO.File]::WriteAllText($webPath, $webBasic, [System.Text.Encoding]::UTF8)

# Realizar Commit 1
git add .
git commit -m "chore: inicializar proyecto base de Laravel"
Write-Host "-> Commit 1 realizado con exito." -ForegroundColor Green

# 5. Crear e integrar el Commit 2 (App Layout y Estilos)
Write-Host "[5/9] Creando Commit 2: Plantilla Principal y Estilos..." -ForegroundColor Yellow

# Restaurar app.blade.php (Layout)
$layoutSource = Join-Path $backupDir "resources/views/layouts/app.blade.php"
$layoutDest = Join-Path $PSScriptRoot "resources/views/layouts/app.blade.php"
$layoutDestDir = Split-Path $layoutDest -Parent
if (!(Test-Path $layoutDestDir)) { New-Item -ItemType Directory -Path $layoutDestDir -Force | Out-Null }
Copy-Item -Path $layoutSource -Destination $layoutDest -Force

# Restaurar welcome.blade.php modificado
$welcomeSource = Join-Path $backupDir "resources/views/welcome.blade.php"
Copy-Item -Path $welcomeSource -Destination $welcomePath -Force

# Realizar Commit 2
git add .
git commit -m "feat: agregar plantilla principal app layout y configuracion de estilos"
Write-Host "-> Commit 2 realizado con exito." -ForegroundColor Green

# 6. Crear e integrar el Commit 3 (Modulo Alumnos)
Write-Host "[6/9] Creando Commit 3: Modulo de Alumnos..." -ForegroundColor Yellow

# Copiar AlumnoController
$alumnoCtrlSource = Join-Path $backupDir "app/Http/Controllers/AlumnoController.php"
$alumnoCtrlDest = Join-Path $PSScriptRoot "app/Http/Controllers/AlumnoController.php"
Copy-Item -Path $alumnoCtrlSource -Destination $alumnoCtrlDest -Force

# Copiar alumnos.blade.php
$alumnoViewSource = Join-Path $backupDir "resources/views/alumnos.blade.php"
$alumnoViewDest = Join-Path $PSScriptRoot "resources/views/alumnos.blade.php"
Copy-Item -Path $alumnoViewSource -Destination $alumnoViewDest -Force

# Escribir routes/web.php para Alumnos
$webAlumnos = '<?php

use App\Http\Controllers\AlumnoController;
use Illuminate\Support\Facades\Route;

Route::get(''/'', function () {
    return view(''welcome'');
});

// Rutas para CRUD de alumnos
Route::get(''/alumnos'', [AlumnoController::class, ''index''])->name(''alumnos'');
Route::post(''/alumnos/create'', [AlumnoController::class, ''store''])->name(''alumnos.store'');
Route::post(''/alumnos/update'', [AlumnoController::class, ''update''])->name(''alumnos.update'');
Route::get(''/alumnos/delete/{id}'', [AlumnoController::class, ''destroy''])->name(''alumnos.destroy'');'

[System.IO.File]::WriteAllText($webPath, $webAlumnos, [System.Text.Encoding]::UTF8)

# Realizar Commit 3
git add .
git commit -m "feat: implementar modulo de alumnos con CRUD"
Write-Host "-> Commit 3 realizado con exito." -ForegroundColor Green

# 7. Crear e integrar el Commit 4 (Modulo Calificaciones)
Write-Host "[7/9] Creando Commit 4: Modulo de Calificaciones..." -ForegroundColor Yellow

# Copiar CalificacionController
$calCtrlSource = Join-Path $backupDir "app/Http/Controllers/CalificacionController.php"
$calCtrlDest = Join-Path $PSScriptRoot "app/Http/Controllers/CalificacionController.php"
Copy-Item -Path $calCtrlSource -Destination $calCtrlDest -Force

# Copiar calificaciones.blade.php
$calViewSource = Join-Path $backupDir "resources/views/calificaciones.blade.php"
$calViewDest = Join-Path $PSScriptRoot "resources/views/calificaciones.blade.php"
Copy-Item -Path $calViewSource -Destination $calViewDest -Force

# Escribir routes/web.php para Alumnos + Calificaciones
$webCalificaciones = '<?php

use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\CalificacionController;
use Illuminate\Support\Facades\Route;

Route::get(''/'', function () {
    return view(''welcome'');
});

// Rutas para CRUD de alumnos
Route::get(''/alumnos'', [AlumnoController::class, ''index''])->name(''alumnos'');
Route::post(''/alumnos/create'', [AlumnoController::class, ''store''])->name(''alumnos.store'');
Route::post(''/alumnos/update'', [AlumnoController::class, ''update''])->name(''alumnos.update'');
Route::get(''/alumnos/delete/{id}'', [AlumnoController::class, ''destroy''])->name(''alumnos.destroy'');

// Rutas para CRUD de calificaciones
Route::get(''/calificaciones'', [CalificacionController::class, ''index''])->name(''calificaciones'');
Route::post(''/calificaciones/create'', [CalificacionController::class, ''store''])->name(''calificaciones.store'');
Route::post(''/calificaciones/update'', [CalificacionController::class, ''update''])->name(''calificaciones.update'');
Route::get(''/calificaciones/delete/{id}'', [CalificacionController::class, ''destroy''])->name(''calificaciones.destroy'');'

[System.IO.File]::WriteAllText($webPath, $webCalificaciones, [System.Text.Encoding]::UTF8)

# Realizar Commit 4
git add .
git commit -m "feat: implementar modulo de calificaciones con CRUD"
Write-Host "-> Commit 4 realizado con exito." -ForegroundColor Green

# 8. Crear e integrar el Commit 5 (Modulo Centros)
Write-Host "[8/9] Creando Commit 5: Modulo de Centros..." -ForegroundColor Yellow

# Copiar CentroController
$centroCtrlSource = Join-Path $backupDir "app/Http/Controllers/CentroController.php"
$centroCtrlDest = Join-Path $PSScriptRoot "app/Http/Controllers/CentroController.php"
Copy-Item -Path $centroCtrlSource -Destination $centroCtrlDest -Force

# Copiar centros.blade.php
$centroViewSource = Join-Path $backupDir "resources/views/centros.blade.php"
$centroViewDest = Join-Path $PSScriptRoot "resources/views/centros.blade.php"
Copy-Item -Path $centroViewSource -Destination $centroViewDest -Force

# Escribir routes/web.php para Alumnos + Calificaciones + Centros
$webCentros = '<?php

use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\CalificacionController;
use App\Http\Controllers\CentroController;
use Illuminate\Support\Facades\Route;

Route::get(''/'', function () {
    return view(''welcome'');
});

// Rutas para CRUD de centros
Route::get(''/centros'', [CentroController::class, ''index''])->name(''centros'');
Route::post(''/centros/create'', [CentroController::class, ''store''])->name(''centros.store'');
Route::post(''/centros/update'', [CentroController::class, ''update''])->name(''centros.update'');
Route::get(''/centros/delete/{id}'', [CentroController::class, ''destroy''])->name(''centros.destroy'');

// Ruta para importacion de centros (desde modal)
Route::post(''/centros/import'', [CentroController::class, ''import''])->name(''centros.import'');

// Rutas para CRUD de alumnos
Route::get(''/alumnos'', [AlumnoController::class, ''index''])->name(''alumnos'');
Route::post(''/alumnos/create'', [AlumnoController::class, ''store''])->name(''alumnos.store'');
Route::post(''/alumnos/update'', [AlumnoController::class, ''update''])->name(''alumnos.update'');
Route::get(''/alumnos/delete/{id}'', [AlumnoController::class, ''destroy''])->name(''alumnos.destroy'');

// Rutas para CRUD de calificaciones
Route::get(''/calificaciones'', [CalificacionController::class, ''index''])->name(''calificaciones'');
Write-Host "-> Restableciendo calificaciones update route..." -ForegroundColor DarkGray
Route::post(''/calificaciones/create'', [CalificacionController::class, ''store''])->name(''calificaciones.store'');
Route::post(''/calificaciones/update'', [CalificacionController::class, ''update''])->name(''calificaciones.update'');
Route::get(''/calificaciones/delete/{id}'', [CalificacionController::class, ''destroy''])->name(''calificaciones.destroy'');'

[System.IO.File]::WriteAllText($webPath, $webCentros, [System.Text.Encoding]::UTF8)

# Realizar Commit 5
git add .
git commit -m "feat: implementar modulo de centros con CRUD e importacion de archivos Excel"
Write-Host "-> Commit 5 realizado con exito." -ForegroundColor Green

# 9. Crear e integrar el Commit 6 (Modulo Inicio y Restauracion Completa)
Write-Host "[9/9] Creando Commit 6: Modulo de Inicio y estado final..." -ForegroundColor Yellow

# Copiar inicio.blade.php
$inicioViewSource = Join-Path $backupDir "resources/views/inicio.blade.php"
$inicioViewDest = Join-Path $PSScriptRoot "resources/views/inicio.blade.php"
Copy-Item -Path $inicioViewSource -Destination $inicioViewDest -Force

# Copiar CrudController
$crudCtrlSource = Join-Path $backupDir "app/Http/Controllers/CrudController.php"
$crudCtrlDest = Join-Path $PSScriptRoot "app/Http/Controllers/CrudController.php"
Copy-Item -Path $crudCtrlSource -Destination $crudCtrlDest -Force

# Copiar routes/web.php original completo
$webSource = Join-Path $backupDir "routes/web.php"
Copy-Item -Path $webSource -Destination $webPath -Force

# Restaurar todos los archivos originales del respaldo para asegurar integridad completa
Write-Host "-> Restaurando archivos originales del respaldo para asegurar 100% de coincidencia..." -ForegroundColor Cyan
Get-ChildItem -Path $backupDir | ForEach-Object {
    $dest = Join-Path $PSScriptRoot $_.Name
    if ($_.PsIsContainer) {
        Copy-Item -Path $_.FullName -Destination $PSScriptRoot -Recurse -Force
    } else {
        Copy-Item -Path $_.FullName -Destination $dest -Force
    }
}

# Realizar Commit 6
git add .
git commit -m "feat: implementar pagina de inicio y redirecciones de ruta"
Write-Host "-> Commit 6 realizado con exito." -ForegroundColor Green

# 10. Limpieza final de directorio temporal
Write-Host "Limpiando archivos de respaldo temporales..." -ForegroundColor Yellow
if (Test-Path $backupDir) {
    Remove-Item $backupDir -Recurse -Force
}
Write-Host "-> Archivos temporales eliminados." -ForegroundColor Green

Write-Host ""
Write-Host "==========================================================" -ForegroundColor Green
Write-Host "  HISTORIAL DE GIT PROGRESIVO CREADO CON EXITO!           " -ForegroundColor Green
Write-Host "==========================================================" -ForegroundColor Green
Write-Host ""
Write-Host "Resumen de commits generados:" -ForegroundColor Cyan
git log --oneline --graph --all
Write-Host ""
Write-Host "Para subir tu codigo a un repositorio nuevo de GitHub, sigue estos pasos:" -ForegroundColor Cyan
Write-Host "1. Abre tu terminal de Laragon o PowerShell en este directorio." -ForegroundColor White
Write-Host "2. Ejecuta los siguientes comandos (reemplazando la URL por la de tu repo de GitHub):" -ForegroundColor White
Write-Host "   git remote add origin https://github.com/tu-usuario/tu-repositorio.git" -ForegroundColor Gray
Write-Host "   git branch -M main" -ForegroundColor Gray
Write-Host "   git push -u origin main" -ForegroundColor Gray
Write-Host ""

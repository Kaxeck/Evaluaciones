<?php

use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\CalificacionController;
use App\Http\Controllers\CentroController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Rutas para CRUD de centros
Route::get('/centros', [CentroController::class, 'index'])->name('centros');
Route::post('/centros/create', [CentroController::class, 'store'])->name('centros.store');
Route::post('/centros/update', [CentroController::class, 'update'])->name('centros.update');
Route::get('/centros/delete/{id}', [CentroController::class, 'destroy'])->name('centros.destroy');

// Ruta para importacion de centros (desde modal)
Route::post('/centros/import', [CentroController::class, 'import'])->name('centros.import');

// Rutas para CRUD de alumnos
Route::get('/alumnos', [AlumnoController::class, 'index'])->name('alumnos');
Route::post('/alumnos/create', [AlumnoController::class, 'store'])->name('alumnos.store');
Route::post('/alumnos/update', [AlumnoController::class, 'update'])->name('alumnos.update');
Route::get('/alumnos/delete/{id}', [AlumnoController::class, 'destroy'])->name('alumnos.destroy');

// Rutas para CRUD de calificaciones
Route::get('/calificaciones', [CalificacionController::class, 'index'])->name('calificaciones');
Write-Host "-> Restableciendo calificaciones update route..." -ForegroundColor DarkGray
Route::post('/calificaciones/create', [CalificacionController::class, 'store'])->name('calificaciones.store');
Route::post('/calificaciones/update', [CalificacionController::class, 'update'])->name('calificaciones.update');
Route::get('/calificaciones/delete/{id}', [CalificacionController::class, 'destroy'])->name('calificaciones.destroy');
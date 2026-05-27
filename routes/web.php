<?php

use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\CalificacionController;
use App\Http\Controllers\CentroController;
use App\Http\Controllers\CrudController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// Rutas para la aplicación
Route::redirect('/', '/inicio');

// Rutas para CRUD de centros
Route::get('/centros', [CentroController::class, 'index'])->name('centros');
Route::post('/centros/create', [CentroController::class, 'store'])->name('centros.store');
Route::post('/centros/update', [CentroController::class, 'update'])->name('centros.update');
Route::get('/centros/delete/{id}', [CentroController::class, 'destroy'])->name('centros.destroy');

// Ruta de inicio (página principal)
Route::get('/inicio', [HomeController::class, 'index'])->name('inicio');

// Ruta para importación de centros (desde modal)
Route::post('/centros/import', [CentroController::class, 'import'])->name('centros.import');

// Rutas para CRUD de alumnos
Route::get('/alumnos', [AlumnoController::class, 'index'])->name('alumnos');
Route::post('/alumnos/create', [AlumnoController::class, 'store'])->name('alumnos.store');
Route::post('/alumnos/update', [AlumnoController::class, 'update'])->name('alumnos.update');
Route::get('/alumnos/delete/{id}', [AlumnoController::class, 'destroy'])->name('alumnos.destroy');
Route::post('/alumnos/import', [AlumnoController::class, 'import'])->name('alumnos.import');

// Rutas para CRUD de calificaciones
Route::get('/calificaciones', [CalificacionController::class, 'index'])->name('calificaciones');
Route::post('/calificaciones/create', [CalificacionController::class, 'store'])->name('calificaciones.store');
Route::post('/calificaciones/update', [CalificacionController::class, 'update'])->name('calificaciones.update');
Route::get('/calificaciones/delete/{id}', [CalificacionController::class, 'destroy'])->name('calificaciones.destroy');

// Rutas para CRUD genérico
Route::post('/create', [CrudController::class, 'create'])->name('crud.create');
Route::post('/update', [CrudController::class, 'update'])->name('crud.update');
Route::get('/delete--{id}', [CrudController::class, 'delete'])->name('crud.delete');
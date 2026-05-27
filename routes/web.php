<?php

use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\CalificacionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Rutas para CRUD de alumnos
Route::get('/alumnos', [AlumnoController::class, 'index'])->name('alumnos');
Route::post('/alumnos/create', [AlumnoController::class, 'store'])->name('alumnos.store');
Route::post('/alumnos/update', [AlumnoController::class, 'update'])->name('alumnos.update');
Route::get('/alumnos/delete/{id}', [AlumnoController::class, 'destroy'])->name('alumnos.destroy');

// Rutas para CRUD de calificaciones
Route::get('/calificaciones', [CalificacionController::class, 'index'])->name('calificaciones');
Route::post('/calificaciones/create', [CalificacionController::class, 'store'])->name('calificaciones.store');
Route::post('/calificaciones/update', [CalificacionController::class, 'update'])->name('calificaciones.update');
Route::get('/calificaciones/delete/{id}', [CalificacionController::class, 'destroy'])->name('calificaciones.destroy');
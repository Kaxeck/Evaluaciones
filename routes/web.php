<?php

use App\Http\Controllers\AlumnoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Rutas para CRUD de alumnos
Route::get('/alumnos', [AlumnoController::class, 'index'])->name('alumnos');
Route::post('/alumnos/create', [AlumnoController::class, 'store'])->name('alumnos.store');
Route::post('/alumnos/update', [AlumnoController::class, 'update'])->name('alumnos.update');
Route::get('/alumnos/delete/{id}', [AlumnoController::class, 'destroy'])->name('alumnos.destroy');
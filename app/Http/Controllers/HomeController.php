<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $totals = [
            'alumnos' => DB::table('alumnos')->count(),
            'centros' => DB::table('centros')->count(),
            'materias' => DB::table('materias')->count(),
            'calificaciones' => DB::table('calificaciones')->count(),
        ];

        $recentAlumnos = DB::table('alumnos')
            ->select('id','matricula','nombre','paterno','materno')
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get();

        $recentCalificaciones = DB::table('calificaciones as ca')
            ->join('alumnos as a', 'a.id', '=', 'ca.alumno_id')
            ->join('materias as m', 'm.id', '=', 'ca.materia_id')
            ->select(
                'ca.id',
                'ca.promedio',
                'ca.parcial1',
                'ca.parcial2',
                'ca.parcial3',
                DB::raw("CONCAT(a.nombre, ' ', a.paterno, ' ', a.materno) AS alumno"),
                'm.nombre AS materia'
            )
            ->orderBy('ca.id', 'desc')
            ->limit(5)
            ->get();

        return view('inicio')
            ->with('totals', $totals)
            ->with('recentAlumnos', $recentAlumnos)
            ->with('recentCalificaciones', $recentCalificaciones);
    }
}

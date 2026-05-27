<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CalificacionController extends Controller
{
    public function index()
    {
        $datos = DB::table('calificaciones as ca')
            ->join('alumnos as a', 'a.id', '=', 'ca.alumno_id')
            ->join('materias as m', 'm.id', '=', 'ca.materia_id')
            ->select(
                'ca.id',
                'ca.alumno_id',
                'ca.materia_id',
                DB::raw("CONCAT(a.nombre, ' ', a.paterno, ' ', a.materno) AS alumno_nombre"),
                'm.nombre AS materia_nombre',
                DB::raw("(SELECT c.nombre FROM centros c WHERE c.id = a.centro_id) AS centro_nombre"),
                'ca.parcial1',
                'ca.parcial2',
                'ca.parcial3',
                'ca.promedio'
            )
            ->orderBy('ca.id', 'asc')
            ->paginate(35);

        $alumnos = DB::table('alumnos as a')
            ->join('centros as c', 'c.id', '=', 'a.centro_id')
            ->select(
                'a.id',
                'a.nombre',
                'a.paterno',
                'a.materno',
                DB::raw('c.nombre AS centro_nombre')
            )
            ->orderBy('a.nombre')
            ->get();
        $materias = DB::select("SELECT id, nombre FROM materias ORDER BY nombre");

        return view('calificaciones')->with('datos', $datos)->with('alumnos', $alumnos)->with('materias', $materias);
    }

    public function store(Request $request)
    {
        $promedio = $this->calcularPromedio($request->txtparcial1, $request->txtparcial2, $request->txtparcial3);

        try {
            $sql = DB::insert(
                'INSERT INTO calificaciones(alumno_id, materia_id, parcial1, parcial2, parcial3, promedio) VALUES (?,?,?,?,?,?)',
                [
                    $request->txtalumno_id,
                    $request->txtmateria_id,
                    $request->txtparcial1,
                    $request->txtparcial2,
                    $request->txtparcial3,
                    $promedio,
                ]
            );
        } catch (\Throwable $th) {
            $sql = 0;
        }

        if ($sql == true) {
            return back()->with('success', 'Calificación añadida correctamente');
        }

        return back()->with('error', 'Error al añadir la calificación');
    }

    public function update(Request $request)
    {
        $promedio = $this->calcularPromedio($request->txtparcial1, $request->txtparcial2, $request->txtparcial3);

        try {
            $sql = DB::update(
                'UPDATE calificaciones SET alumno_id=?, materia_id=?, parcial1=?, parcial2=?, parcial3=?, promedio=? WHERE id=?',
                [
                    $request->txtalumno_id,
                    $request->txtmateria_id,
                    $request->txtparcial1,
                    $request->txtparcial2,
                    $request->txtparcial3,
                    $promedio,
                    $request->txtcodigo,
                ]
            );

            if ($sql == 0) {
                $sql = 1;
            }
        } catch (\Throwable $th) {
            $sql = 0;
        }

        if ($sql == true) {
            return back()->with('success', 'Calificación actualizada correctamente');
        }

        return back()->with('error', 'Error al actualizar la calificación');
    }

    public function destroy($id)
    {
        try {
            $sql = DB::delete('DELETE FROM calificaciones WHERE id=?', [$id]);
        } catch (\Throwable $th) {
            $sql = 0;
        }

        if ($sql == true) {
            return back()->with('success', 'Calificación eliminada correctamente');
        }

        return back()->with('error', 'Error al eliminar la calificación');
    }

    private function calcularPromedio($parcial1, $parcial2, $parcial3)
    {
        $valores = array_filter([$parcial1, $parcial2, $parcial3], fn ($valor) => $valor !== null && $valor !== '');

        if (count($valores) === 0) {
            return null;
        }

        return round(array_sum($valores) / count($valores), 2);
    }
}
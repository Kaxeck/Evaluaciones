<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
        $validated = $this->validateCalificacion($request);

        if (!is_array($validated)) {
            return $validated;
        }

        $promedio = $this->calcularPromedio($validated['txtparcial1'], $validated['txtparcial2'], $validated['txtparcial3']);

        try {
            $sql = DB::insert(
                'INSERT INTO calificaciones(alumno_id, materia_id, parcial1, parcial2, parcial3, promedio) VALUES (?,?,?,?,?,?)',
                [
                    $validated['txtalumno_id'],
                    $validated['txtmateria_id'],
                    $validated['txtparcial1'],
                    $validated['txtparcial2'],
                    $validated['txtparcial3'],
                    $promedio,
                ]
            );
        } catch (\Throwable $th) {
            $sql = 0;
        }

        return $this->respondCalificacion($request, $sql == true, 'añadida');
    }

    public function update(Request $request)
    {
        $validated = $this->validateCalificacion($request);

        if (!is_array($validated)) {
            return $validated;
        }

        $promedio = $this->calcularPromedio($validated['txtparcial1'], $validated['txtparcial2'], $validated['txtparcial3']);

        try {
            $sql = DB::update(
                'UPDATE calificaciones SET alumno_id=?, materia_id=?, parcial1=?, parcial2=?, parcial3=?, promedio=? WHERE id=?',
                [
                    $validated['txtalumno_id'],
                    $validated['txtmateria_id'],
                    $validated['txtparcial1'],
                    $validated['txtparcial2'],
                    $validated['txtparcial3'],
                    $promedio,
                    $validated['txtcodigo'],
                ]
            );

            if ($sql == 0) {
                $sql = 1;
            }
        } catch (\Throwable $th) {
            $sql = 0;
        }

        return $this->respondCalificacion($request, $sql == true, 'actualizada');
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

    private function validateCalificacion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'txtalumno_id' => ['required', 'integer', 'exists:alumnos,id'],
            'txtmateria_id' => ['required', 'integer', 'exists:materias,id'],
            'txtparcial1' => ['required', 'numeric', 'min:0'],
            'txtparcial2' => ['required', 'numeric', 'min:0'],
            'txtparcial3' => ['required', 'numeric', 'min:0'],
        ], [
            'txtalumno_id.required' => 'Selecciona un alumno.',
            'txtmateria_id.required' => 'Selecciona una materia.',
            'txtparcial1.required' => 'El parcial 1 es obligatorio.',
            'txtparcial2.required' => 'El parcial 2 es obligatorio.',
            'txtparcial3.required' => 'El parcial 3 es obligatorio.',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Validación fallida',
                    'errors' => $validator->errors(),
                ], 422);
            }

            return back()->withErrors($validator)->withInput();
        }

        return $validator->validated();
    }

    private function respondCalificacion(Request $request, bool $success, string $action)
    {
        $failedVerb = $action === 'añadida' ? 'añadir' : 'actualizar';
        $message = $success
            ? "Calificación {$action} correctamente"
            : "Error al {$failedVerb} la calificación";

        if ($request->expectsJson()) {
            return response()->json([
                'success' => $success,
                'message' => $message,
            ], $success ? 200 : 500);
        }

        return $success
            ? back()->with('success', $message)
            : back()->with('error', $message);
    }
}
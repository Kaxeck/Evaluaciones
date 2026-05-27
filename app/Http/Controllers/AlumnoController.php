<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlumnoController extends Controller
{
    // Función para mostrar los registros
    public function index()
    {
        $datos = DB::table('alumnos as a')
            ->join('centros as c', 'c.id', '=', 'a.centro_id')
            ->select(
                'a.id',
                'a.centro_id',
                DB::raw('c.nombre AS centro_nombre'),
                'a.matricula',
                'a.estatus',
                'a.nombre',
                'a.paterno',
                'a.materno',
                'a.genero',
                'a.generacion',
                'a.municipio_residencia',
                'a.pais_nacimiento',
                'a.fecha_nacimiento'
            )
            ->orderBy('a.id', 'asc')
            ->paginate(35);

        $centros = DB::select('SELECT id, nombre FROM centros ORDER BY nombre');

        return view('alumnos')->with('datos', $datos)->with('centros', $centros);
    }

    // Función para añadir un registro
    public function store(Request $request)
    {
        try {
            $sql = DB::insert(
                'INSERT INTO alumnos(centro_id,matricula,estatus,nombre,paterno,materno,genero,generacion,municipio_residencia,pais_nacimiento,fecha_nacimiento) VALUES (?,?,?,?,?,?,?,?,?,?,?)',
                [
                    $request->txtcentro_id,
                    $request->txtmatricula,
                    $request->txtestatus,
                    $request->txtnombre,
                    $request->txtpaterno,
                    $request->txtmaterno,
                    $request->txtgenero,
                    $request->txtgeneracion,
                    $request->txtmunicipio_residencia,
                    $request->txtpais_nacimiento,
                    $request->txtfecha_nacimiento,
                ]
            );
        } catch (\Throwable $th) {
            $sql = 0;
        }

        if ($sql == true) {
            return back()->with('success', 'Alumno añadido correctamente');
        }

        return back()->with('error', 'Error al añadir el alumno');
    }

    // Función para actualizar un registro
    public function update(Request $request)
    {
        try {
            $sql = DB::update(
                'UPDATE alumnos SET centro_id=?, matricula=?, estatus=?, nombre=?, paterno=?, materno=?, genero=?, generacion=?, municipio_residencia=?, pais_nacimiento=?, fecha_nacimiento=? WHERE id=?',
                [
                    $request->txtcentro_id,
                    $request->txtmatricula,
                    $request->txtestatus,
                    $request->txtnombre,
                    $request->txtpaterno,
                    $request->txtmaterno,
                    $request->txtgenero,
                    $request->txtgeneracion,
                    $request->txtmunicipio_residencia,
                    $request->txtpais_nacimiento,
                    $request->txtfecha_nacimiento,
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
            return back()->with('success', 'Alumno actualizado correctamente');
        }

        return back()->with('error', 'Error al actualizar el alumno');
    }

    // Función para eliminar un registro
    public function destroy($id)
    {
        try {
            $sql = DB::delete('DELETE FROM alumnos WHERE id=?', [$id]);
        } catch (\Throwable $th) {
            $sql = 0;
        }

        if ($sql == true) {
            return back()->with('success', 'Alumno eliminado correctamente');
        }

        return back()->with('error', 'Error al eliminar el alumno');
    }
}
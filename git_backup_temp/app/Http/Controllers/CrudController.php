<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CrudController extends Controller
{
    public function index()
    {
        return redirect()->route('inicio');
    }

    public function centros()
    {
        $datos = DB::table('centros')
            ->orderBy('id', 'desc')
            ->paginate(35);

        return view('centros')->with('datos', $datos);
    }

    public function alumnos()
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
            ->orderBy('a.id', 'desc')
            ->paginate(35);

        $centros = DB::select("SELECT id, nombre FROM centros ORDER BY nombre");

        return view("alumnos")->with("datos", $datos)->with("centros", $centros);
    }

    //Función para añadir un registro
    public function create(Request $request)
    {
        try {
            if ($request->entity === 'centros') {
                $sql = DB::insert(
                    'INSERT INTO centros(nombre, clave_cct, municipio, encargado, correo_encargado) VALUES (?,?,?,?,?)',
                    [
                        $request->txtnombre,
                        $request->txtclave_cct,
                        $request->txtmunicipio,
                        $request->txtencargado,
                        $request->txtcorreo_encargado,
                    ]
                );
            } else {
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
            }
        } catch (\Throwable $th) {
            $sql = 0;
        }

        if ($sql == true) {
            return back()->with('success', $request->entity === 'centros' ? 'Centro añadido correctamente' : 'Alumno añadido correctamente');
        }

        return back()->with('error', $request->entity === 'centros' ? 'Error al añadir el centro' : 'Error al añadir el alumno');
    }

    //Función para modificar un registro
    public function update(Request $request)
    {
        try {
            if ($request->entity === 'centros') {
                $sql = DB::update(
                    'UPDATE centros SET nombre=?, clave_cct=?, municipio=?, encargado=?, correo_encargado=? WHERE id=?',
                    [
                        $request->txtnombre,
                        $request->txtclave_cct,
                        $request->txtmunicipio,
                        $request->txtencargado,
                        $request->txtcorreo_encargado,
                        $request->txtcodigo,
                    ]
                );
            } else {
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
            }

            if ($sql == 0) {
                $sql = 1;
            }
        } catch (\Throwable $th) {
            $sql = 0;
        }

        if ($sql == true) {
            return back()->with('success', $request->entity === 'centros' ? 'Centro actualizado correctamente' : 'Alumno actualizado correctamente');
        }

        return back()->with('error', $request->entity === 'centros' ? 'Error al actualizar el centro' : 'Error al actualizar el alumno');
    }

    public function delete($id)
    {
        try {
            if (request('entity') === 'centros') {
                $sql = DB::delete('DELETE FROM centros WHERE id=?', [$id]);
            } else {
                $sql = DB::delete('DELETE FROM alumnos WHERE id=?', [$id]);
            }
        } catch (\Throwable $th) {
            $sql = 0;
        }

        if ($sql == true) {
            return back()->with('success', request('entity') === 'centros' ? 'Centro eliminado correctamente' : 'Alumno eliminado correctamente');
        }

        return back()->with('error', request('entity') === 'centros' ? 'Error al eliminar el centro' : 'Error al eliminar el alumno');
    }
}
<?php

namespace App\Http\Controllers;

use App\Services\CentroImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CentroController extends Controller
{
    public function index()
    {
        $datos = DB::table('centros')
            ->orderBy('id', 'asc')
            ->paginate(35);

        return view('centros')->with('datos', $datos);
    }
    // Función para añadir un registro
    public function store(Request $request)
    {
        try {
            $sql = DB::insert(
                'INSERT INTO centros(clave, nombre, clave_cct, municipio, encargado, correo_encargado) VALUES (?,?,?,?,?,?)',
                [
                    $request->txtclave,
                    $request->txtnombre,
                    $request->txtclave_cct,
                    $request->txtmunicipio,
                    $request->txtencargado,
                    $request->txtcorreo_encargado,
                ]
            );
        } catch (\Throwable $th) {
            $sql = 0;
        }

        if ($sql == true) {
            return back()->with('success', 'Centro añadido correctamente');
        }

        return back()->with('error', 'Error al añadir el centro');
    }

    // Función para actualizar un registro
    public function update(Request $request)
    {
        try {
            $sql = DB::update(
                'UPDATE centros SET clave=?, nombre=?, clave_cct=?, municipio=?, encargado=?, correo_encargado=? WHERE id=?',
                [
                    $request->txtclave,
                    $request->txtnombre,
                    $request->txtclave_cct,
                    $request->txtmunicipio,
                    $request->txtencargado,
                    $request->txtcorreo_encargado,
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
            return back()->with('success', 'Centro actualizado correctamente');
        }

        return back()->with('error', 'Error al actualizar el centro');
    }

    // Función para eliminar un registro
    public function destroy($id)
    {
        try {
            $sql = DB::delete('DELETE FROM centros WHERE id=?', [$id]);
        } catch (\Throwable $th) {
            $sql = 0;
        }

        if ($sql == true) {
            return back()->with('success', 'Centro eliminado correctamente');
        }

        return back()->with('error', 'Error al eliminar el centro');
    }

    // Importar archivo de centros (XLS/XLSX)
    public function import(Request $request, CentroImportService $importService)
    {
        try {
            if (!$request->hasFile('file')) {
                return back()->with('error', 'No se seleccionó ningún archivo');
            }

            $result = $importService->import($request->file('file'));

            $parts = ["{$result['importados']} exitoso(s)"];

            if ($result['duplicados'] > 0) {
                $parts[] = "{$result['duplicados']} duplicado(s) ignorado(s)";
            }

            if ($result['fallidos'] > 0) {
                $parts[] = "{$result['fallidos']} fallido(s)";
            }

            return back()->with('success', 'Importación completada: ' . implode(', ', $parts));
        } catch (\Throwable $th) {
            return back()->with('error', 'Error al subir el archivo: ' . $th->getMessage());
        }
    }
}
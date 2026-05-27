<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

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
    public function import(Request $request)
    {
        try {
            if (!$request->hasFile('file')) {
                return back()->with('error', 'No se seleccionó ningún archivo');
            }

            $file = $request->file('file');
            $extension = strtolower($file->getClientOriginalExtension());

            if (!in_array($extension, ['xls', 'xlsx'], true)) {
                return back()->with('error', 'Solo se permite importar archivos XLS o XLSX');
            }

            $uploadedPath = $file->getPathname();

            if (!is_readable($uploadedPath)) {
                return back()->with('error', 'El archivo subido no es legible');
            }

            $spreadsheet = IOFactory::load($uploadedPath);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, true);

            if (empty($rows)) {
                return back()->with('error', 'El archivo no contiene datos');
            }

            $importados = 0;
            $duplicados = 0;
            $fallidos = 0;
            $cabeceraProcesada = false;

            DB::beginTransaction();

            foreach ($rows as $row) {
                $values = array_values($row);
                $values = array_map(static function ($value) {
                    return trim((string) $value);
                }, $values);

                $firstCell = strtolower($values[0] ?? '');

                if ($firstCell === 'id' || ctype_digit($firstCell)) {
                    array_shift($values);
                }

                if ($this->rowIsEmpty($values)) {
                    continue;
                }

                if (!$cabeceraProcesada) {
                    $cabeceraProcesada = true;

                    $firstCell = strtolower($values[0] ?? '');
                    $secondCell = strtolower($values[1] ?? '');

                    if (
                        str_contains($firstCell, 'clave') ||
                        str_contains($secondCell, 'telebachillerato') ||
                        str_contains($secondCell, 'nombre')
                    ) {
                        continue;
                    }
                }

                $clave = $values[0] ?? '';
                $nombre = $values[1] ?? '';
                $claveCct = $values[2] ?? '';
                $municipio = $values[3] ?? '';
                $encargado = $values[4] ?? '';
                $correoEncargado = $values[5] ?? '';

                if ($clave === '' && $nombre === '' && $claveCct === '') {
                    continue;
                }

                try {
                    DB::insert(
                        'INSERT INTO centros(clave, nombre, clave_cct, municipio, encargado, correo_encargado) VALUES (?,?,?,?,?,?)',
                        [$clave, $nombre, $claveCct, $municipio, $encargado, $correoEncargado]
                    );

                    $importados++;
                } catch (\Throwable $rowException) {
                    $message = $rowException->getMessage();

                    if (str_contains($message, 'Duplicate entry') || str_contains($message, '1062')) {
                        $duplicados++;
                        continue;
                    }

                    $fallidos++;
                }
            }

            DB::commit();

            $parts = ["{$importados} exitoso(s)"];

            if ($duplicados > 0) {
                $parts[] = "{$duplicados} duplicado(s) ignorado(s)";
            }

            if ($fallidos > 0) {
                $parts[] = "{$fallidos} fallido(s)";
            }

            return back()->with('success', 'Importación completada: ' . implode(', ', $parts));
        } catch (\Throwable $th) {
            DB::rollBack();

            return back()->with('error', 'Error al subir el archivo: ' . $th->getMessage());
        }
    }

    private function rowIsEmpty(array $values): bool
    {
        foreach ($values as $value) {
            if (trim((string) $value) !== '') {
                return false;
            }
        }

        return true;
    }
}
<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

// Servicio para manejar la importación de centros desde un archivo Excel
class CentroImportService
{
    // Función para importar centros desde un archivo Excel
    public function import(UploadedFile $file): array
    {
        $extension = strtolower($file->getClientOriginalExtension());

        if (!in_array($extension, ['xls', 'xlsx'], true)) {
            throw new \InvalidArgumentException('Solo se permite importar archivos XLS o XLSX');
        }

        $uploadedPath = $file->getPathname();

        if (!is_readable($uploadedPath)) {
            throw new \RuntimeException('El archivo subido no es legible');
        }

        $spreadsheet = IOFactory::load($uploadedPath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true);

        if (empty($rows)) {
            throw new \RuntimeException('El archivo no contiene datos');
        }

        $importados = 0;
        $duplicados = 0;
        $fallidos = 0;
        $cabeceraProcesada = false;

        DB::beginTransaction();

        try {
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
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        return [
            'importados' => $importados,
            'duplicados' => $duplicados,
            'fallidos' => $fallidos,
        ];
    }

    // Función para determinar si una fila está vacía (todos los valores son nulos o espacios en blanco)
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

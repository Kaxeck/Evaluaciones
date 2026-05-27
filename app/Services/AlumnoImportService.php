<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

// Servicio para manejar la importación de alumnos desde un archivo Excel
class AlumnoImportService
{
    // Función para importar alumnos desde un archivo Excel
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
        $sinCentro = 0;
        $cabeceraProcesada = false;

        DB::beginTransaction();

        try {
            foreach ($rows as $row) {
                $values = array_values($row);
                $values = array_map(static function ($value) {
                    return trim((string) $value);
                }, $values);

                if ($this->rowIsEmpty($values)) {
                    continue;
                }

                if (!$cabeceraProcesada) {
                    $cabeceraProcesada = true;

                    if ($this->rowLooksLikeHeader($values)) {
                        continue;
                    }
                }

                $matricula = $values[0] ?? '';
                $telebachillerato = $values[1] ?? '';
                $estatus = $values[2] ?? '';
                $nombre = $values[3] ?? '';
                $paterno = $values[4] ?? '';
                $materno = $values[5] ?? '';
                $genero = $values[6] ?? '';
                $generacion = $values[7] ?? '';
                $municipioResidencia = $values[8] ?? '';
                $paisNacimiento = $values[9] ?? '';
                $fechaNacimiento = $this->normalizeDate($values[10] ?? '');

                if ($matricula === '' && $telebachillerato === '' && $nombre === '') {
                    continue;
                }

                $centro = DB::table('centros')
                    ->select('id')
                    ->whereRaw('TRIM(LOWER(nombre)) = ?', [mb_strtolower(trim($telebachillerato), 'UTF-8')])
                    ->first();

                if (!$centro) {
                    $sinCentro++;
                    continue;
                }

                try {
                    DB::insert(
                        'INSERT INTO alumnos(centro_id,matricula,estatus,nombre,paterno,materno,genero,generacion,municipio_residencia,pais_nacimiento,fecha_nacimiento) VALUES (?,?,?,?,?,?,?,?,?,?,?)',
                        [
                            $centro->id,
                            $matricula,
                            $estatus,
                            $nombre,
                            $paterno,
                            $materno,
                            $genero,
                            $generacion,
                            $municipioResidencia,
                            $paisNacimiento,
                            $fechaNacimiento,
                        ]
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
            'sin_centro' => $sinCentro,
        ];
    }

    // Función para determinar si una fila parece ser la cabecera del archivo
    private function rowLooksLikeHeader(array $values): bool
    {
        $firstCell = strtolower((string) ($values[0] ?? ''));
        $secondCell = strtolower((string) ($values[1] ?? ''));
        $thirdCell = strtolower((string) ($values[2] ?? ''));

        return str_contains($firstCell, 'matricula')
            || str_contains($firstCell, 'matrícula')
            || str_contains($secondCell, 'telebachillerato')
            || str_contains($thirdCell, 'estatus');
    }

    // Función para normalizar fechas, manejando tanto formatos de texto como fechas en formato Excel
    private function normalizeDate(string $value): string
    {
        $value = trim($value);

        if ($value === '') {
            return '';
        }

        if (is_numeric($value)) {
            try {
                return ExcelDate::excelToDateTimeObject((float) $value)->format('Y-m-d');
            } catch (\Throwable $th) {
                return $value;
            }
        }

        foreach (['d/m/Y', 'd-m-Y', 'Y-m-d', 'm/d/Y'] as $format) {
            $dateTime = \DateTimeImmutable::createFromFormat($format, $value);

            if ($dateTime instanceof \DateTimeImmutable) {
                return $dateTime->format('Y-m-d');
            }
        }

        return $value;
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

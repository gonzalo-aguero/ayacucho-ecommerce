<?php

namespace App\Http\Controllers;

//require base_path().' vendor/autoload.php'; // Importar la librería PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\IOFactory;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class ExcelConversionController extends Controller
{
    /**
     * Show the UI
     */
    public function show(): View
    {
        return view('admin.excel-conversion');
    }

    public function process(Request $request): JsonResponse
    {

        // Cargar archivo Excel
        $inputFileName = base_path().'/storage/app/Base de Datos.xlsx';
        $spreadsheet = IOFactory::load($inputFileName);

        // Seleccionar la hoja
        $worksheet = $spreadsheet->getActiveSheet();

        // Obtener los datos de las celdas "Nombre" y "Edad" y almacenarlos en un array
        $data = array();
        foreach ($worksheet->getRowIterator() as $row) {
            $nombre = $worksheet->getCell('A' . $row->getRowIndex())->getValue();
            $edad = $worksheet->getCell('B' . $row->getRowIndex())->getValue();

            // Ignorar la primera fila (encabezados)
            if ($row->getRowIndex() !== 1) {
                $data[] = array(
                    'Nombre' => $nombre,
                    'Edad' => $edad
                );
            }
        }

        // Crear archivo JSON con los datos obtenidos
        $outputFileName = base_path().'/public_html/json/Productos.json';
        $file = fopen($outputFileName, 'w');
        fwrite($file, json_encode($data));
        fclose($file);

        return response()->json([
            'conversion' => true
        ]);
    }
}

<?php

namespace App\Http\Controllers;

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
    private function process_products(){
        // Cargar archivo Excel
        $inputFileName = base_path().'/'.config('app.excel_name');
        $spreadsheet = IOFactory::load($inputFileName);

        // Seleccionar la hoja
        $worksheet = $spreadsheet->getActiveSheet();

        // Obtener los datos de las celdas "Nombre" y "Edad" y almacenarlos en un array
        $data = array();
        foreach ($worksheet->getRowIterator() as $row) {
            // Ignorar la primera fila (encabezados)
            if ($row->getRowIndex() !== 1) {
                $id = $worksheet->getCell('A' . $row->getRowIndex())->getValue();
                $name = $worksheet->getCell('B' . $row->getRowIndex())->getValue();
                $price = $worksheet->getCell('C' . $row->getRowIndex())->getValue();
                $m2Price = $worksheet->getCell('D' . $row->getRowIndex())->getValue();
                $description = $worksheet->getCell('E' . $row->getRowIndex())->getValue();
                $image = $worksheet->getCell('F' . $row->getRowIndex())->getValue();
                $thumbnail = $worksheet->getCell('G' . $row->getRowIndex())->getValue();
                $category = $worksheet->getCell('H' . $row->getRowIndex())->getValue();
                $units = $worksheet->getCell('I' . $row->getRowIndex())->getValue();
                $showUnits = $worksheet->getCell('J' . $row->getRowIndex())->getValue();
                $m2ByUnit = $worksheet->getCell('K' . $row->getRowIndex())->getValue();
                $variationId = $worksheet->getCell('L' . $row->getRowIndex())->getValue();

                $data[] = array(
                    "id" => $id,
                    'name' => $name,
                    "price" => $price,
                    "m2Price" => $m2Price,
                    "description" => $description,
                    "image" => $image,
                    "thumbnail" => $thumbnail,
                    "category" => $category,
                    "units" => $units,
                    "showUnits" => $showUnits,
                    "m2ByUnit" => $m2ByUnit,
                    "variationId" => $variationId
                );
            }
        }

        // Crear archivo JSON con los datos obtenidos
        $outputFileName = base_path().'/public_html/json/Productos.json';
        $file = fopen($outputFileName, 'w');
        fwrite($file, json_encode($data));
        fclose($file);

    }
    public function process(Request $request): JsonResponse
    {
        $this->process_products();

        return response()->json([
            'conversion' => true
        ]);
    }
}

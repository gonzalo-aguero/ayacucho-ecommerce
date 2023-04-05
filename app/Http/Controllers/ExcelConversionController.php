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
                $id = (string) $id;// El campo "id" debe ser un string.

                //Ignorar filas cuya columna id este vacia
                if($id != ""){
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

                    // El campo "m2Price" debe ser del tipo float y, en caso de que sea una cadena
                    // vacía o igual a 0 debe ser remplazado por null.
                    $m2Price = trim($m2Price);
                    if (empty($m2Price) || $m2Price == 0) {
                        $m2Price = null;
                    } else {
                        $m2Price = (float) $m2Price;
                    }

                    //El campo "image" debe ser un string. En caso de ser una cadena vacía debe ser remplazado por null.
                    $image = trim($image);
                    if (empty($image)) {
                        $image = null;
                    } else {
                        $image = (string) $image;
                    }
                    //El campo "thumnail" debe tener el mismo formato que el campo "image".
                    $thumbnail = trim($thumbnail);
                    if (empty($thumbnail)) {
                        $thumbnail = null;
                    } else {
                       $thumbnail = (string) $thumbnail;
                    }

                    //El campo "category" debe ser un string. En caso de ser una cadena vacía
                    //debe ser remplazado por "Sin categoría".
                    $category = trim($category);
                    if (empty($category)) {
                        $category = "Sin categoría";
                    } else {
                        $category = (string) $category;
                    }

                    // El campo "showUnits" será una cadena que podrá contener "si" o "no",
                    // tanto en mayúsculas como en minúsculas. Se debe convertir las cadenas "si" a
                    // valores booleanos true y las cadenas "no" a valores booleanos false.
                    // En caso de ser una cadena vacía el valor final deberá ser false.
                    $showUnits = strtolower(trim($showUnits));
                    if ($showUnits == "si") {
                        $showUnits = true;
                    } else {
                        $showUnits = false;
                    }


                    //El campo "m2ByUnit" deberá ser un número del tipo float.
                    //En caso de ser una cadena vacía o cero se deberá reemplazar por null.
                    $m2ByUnit = isset($m2ByUnit) && $m2ByUnit !== "" && $m2ByUnit !== "0" ? floatval($m2ByUnit) : null;

                    //El campo "variationId" deberá ser un número entero. En caso de ser una
                    //cadena vacía se deberá remplazar por null.
                    $variationId = isset($variationId) && $variationId !== "" ? intval($variationId) : null;

                    $data[] = array(
                        "id" => $id,
                        'name' => (string) $name,
                        "price" => (float) $price,
                        "m2Price" => $m2Price,
                        "description" => (string) $description,
                        "image" => $image,
                        "thumbnail" => $thumbnail,
                        "category" => $category,
                        "units" => (int) $units,
                        "showUnits" => $showUnits,
                        "m2ByUnit" => $m2ByUnit,
                        "variationId" => $variationId
                    );
                }
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

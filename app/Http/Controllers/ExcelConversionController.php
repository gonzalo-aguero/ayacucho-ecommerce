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
    private function process_products($config){
        // Cargar archivo Excel
        $spreadsheet = IOFactory::load($config->inputFileName);

        // Seleccionar la hoja
        $worksheet = $spreadsheet->getSheetByName('Productos');

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
                        "units" => $units,
                        "showUnits" => $showUnits,
                        "m2ByUnit" => $m2ByUnit,
                        "variationId" => $variationId
                    );
                }
            }
        }

        // Crear archivo JSON con los datos obtenidos
        $outputFileName = "Productos.json";
        $file = fopen($config->json_path.$outputFileName, 'w');
        fwrite($file, json_encode($data));
        fclose($file);
        return true;
    }
    private function process_shippingZones($config) {
        $shipping_zones = array();
        $spreadsheet = IOFactory::load($config->inputFileName);
        $worksheet = $spreadsheet->getSheetByName('ZonasDeEnvio');
        $rows = $worksheet->toArray();

        // Iterar sobre las filas del archivo Excel
        for ($i = 1; $i < count($rows); $i++) {
            $shipping_zone = array(
                'name' => $rows[$i][0],
                'cost' => floatval($rows[$i][1])
            );
            array_push($shipping_zones, $shipping_zone);
        }

        // Crear archivo JSON con los datos obtenidos
        $outputFileName = "ZonasDeEnvio.json";
        $file = fopen($config->json_path.$outputFileName, 'w');
        fwrite($file, json_encode($shipping_zones));
        fclose($file);
        return true;
    }
    private function process_paymentMethods($config){
        $spreadsheet = IOFactory::load($config->inputFileName);//Cargar archivo Excel
        $worksheet = $spreadsheet->getSheetByName("MetodosDePago");// Seleccionar la hoja

        $data = array();
        foreach ($worksheet->getRowIterator() as $row) {
            if ($row->getRowIndex() !== 1) {// Ignorar la primera fila (encabezados)
                $name = $worksheet->getCell('A' . $row->getRowIndex())->getValue();
                $name = (string) $name;

                //Ignorar filas cuya columna "Nombre" este vacia
                if($name != ""){
                    $percent = $worksheet->getCell('B' . $row->getRowIndex())->getValue();
                    $note = $worksheet->getCell('C' . $row->getRowIndex())->getValue();
                    $data[] = array(
                        "name" => $name,
                        "percent" => (float) $percent,
                        "note" => (string) $note
                    );
                }
            }
        }

        // Crear archivo JSON con los datos obtenidos
        $outputFileName = "MetodosDePago.json";
        $file = fopen($config->json_path.$outputFileName, 'w');
        fwrite($file, json_encode($data));
        fclose($file);
        return true;
    }
    private function process_variations($config){
        $spreadsheet = IOFactory::load($config->inputFileName);//Cargar archivo Excel
        $worksheet = $spreadsheet->getSheetByName("Variaciones");// Seleccionar la hoja

        $data = array();
        //VariacionID	Titulo	OpcionID	Valor
        foreach ($worksheet->getRowIterator() as $row) {
            if ($row->getRowIndex() !== 1) {// Ignorar la primera fila (encabezados)
                $variationId = $worksheet->getCell('A' . $row->getRowIndex())->getValue();

                //Ignorar filas cuya columna "Nombre" este vacia
                if($variationId != "" && $variationId != null && $variationId != 0){
                    $variationId = (int) $variationId;
                    $optionId = (int) $worksheet->getCell('C' . $row->getRowIndex())->getValue();
                    $value = (string) $worksheet->getCell('D' . $row->getRowIndex())->getValue();
                    $units = (float) $worksheet->getCell('E' . $row->getRowIndex())->getValue();

                    $index = $variationId - 1;
                    if(isset($data[$index])){
                        //Si ya existe dicha variacion de agrega la nueva opcion.
                        array_push($data[$index]["options"], $value);
                    }else{
                        //Si no existe, se crea y se agrega la nueva opcion.
                        $title = (string) $worksheet->getCell('B' . $row->getRowIndex())->getValue();
                        array_push($data, array(
                            "title" => $title,
                            "options" => [$value]
                        ));
                    }
                }
            }
        }

        // Crear archivo JSON con los datos obtenidos
        $outputFileName = "Variaciones.json";
        $file = fopen($config->json_path.$outputFileName, 'w');
        fwrite($file, json_encode($data));
        fclose($file);
        return true;
    }
    public function process(): JsonResponse
    {
        $success = false;
        $config = (object) array(
            "json_path" => base_path()."/public_html/json/",
            "inputFileName" => base_path().'/'.config('app.excel_name')
        );

        if( $this->process_products($config) &&
            $this->process_variations($config) &&
            $this->process_paymentMethods($config) &&
            $this->process_shippingZones($config) ){
            $success = true;
        }
        return response()->json([
            'conversion' => $success
        ]);
    }
}

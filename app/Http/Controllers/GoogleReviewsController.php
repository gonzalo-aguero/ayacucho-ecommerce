<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class GoogleReviewsController extends Controller
{
    public function getImages(Request $request, Response $response)
    {
        $path = "./public/google-reviews/"; // Carpeta especificada por la propiedad "path"
        $images = [];

        // Obtiene el listado de archivos en la carpeta "path"
        $files = Storage::files($path);

        //dd($files);
        // Genera la URL para cada archivo de imagen
        foreach ($files as $file) {
            $url = Storage::url($file);
            array_push($images, $url);
        }

        // Retorna los archivos de imagen como un arreglo en formato JSON
        return response()->json($images);
    }
}


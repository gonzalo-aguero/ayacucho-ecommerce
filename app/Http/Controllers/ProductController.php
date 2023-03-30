<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    private $productID;
    /**
     * Show the UI
     */
    public function show(Request $request): View
    {
        // Get the products from its json file.
        $products = file_get_contents(public_path('json/Productos.json'));
        $products = json_decode($products);
        //$variations = file_get_contents(public_path('json/Variaciones.json'));
        //$variations = json_decode($variations);
        $this->productID = $request->productID;
        $product = array_filter($products, function($prod){
            return $prod->id == $this->productID;
        });

        return view('product-page', [
            "product" => array_values($product)[0]
        ]);
    }
}

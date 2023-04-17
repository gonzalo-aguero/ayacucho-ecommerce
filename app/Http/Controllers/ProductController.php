<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    private $productId;
    /**
     * Show the UI
     */
    public function show(Request $request): View
    {
        // Get the products from its json file and filter the requested product.
        $products = file_get_contents(public_path('json/Productos.json'));
        $products = json_decode($products);
        $this->productId = $request->productId;
        $products = array_filter($products, function($prod){
            return $prod->id == $this->productId;
        });
        $products = array_values($products);
        $product = $products[0];

        $hasVariations = false;
        if($product->variationId != null){
            // Get the variations from its json file and filter the requested product variation.
            $variations = file_get_contents(public_path('json/Variaciones.json'));
            $variations = json_decode($variations);
            $hasVariations = true;
        }

        if(count($products) > 0){
            return view('product-page', [
                "product" => $product,
                "variation" => $hasVariations ? $variations[$product->variationId - 1] : null,
            ]);
        }else{
            abort(404);
        }
    }
}

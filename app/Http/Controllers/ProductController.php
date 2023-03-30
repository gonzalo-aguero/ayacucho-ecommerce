<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Show the UI
     */
    public function show(Request $request): View
    {
        return view('product-page', [
            "productName" => $request->productName,
            "productID" => $request->productID
        ]);
    }
}

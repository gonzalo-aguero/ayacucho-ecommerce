<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function create(Request $req){
        $req->validate([
            'name' => 'required|max:5',
        ]);
        return $req["name"];
    }
}

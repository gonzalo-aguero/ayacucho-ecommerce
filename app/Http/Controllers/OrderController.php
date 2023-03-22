<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    private function title($text){
        return "● *$text:*\n";
    }
    private function dataItem($title, $value){
        return "   *$title:* ". $value ."\n";
    }
    private function generateURL(Request $req){
        $compPhoneNumber = config('company.phone_number');
        $compName = config("app.name");

        $text = "Hola *$compName*, esta es la información de mi pedido:\n\n"
            . $this->title("DATOS DEL CLIENTE")
            . $this->dataItem("Nombre", $req["name"])
            . $this->dataItem("DNI", $req["dni"])
            . $this->dataItem("Correo", $req["email"])
            . $this->dataItem("Teléfono", $req["telephone"])
            . $this->dataItem("Localidad", $req["city"])
            . $this->dataItem("Dirección", $req["Street address"])
            . "\n"
            . $this->title("DATOS DEL PEDIDO")
            . $this->dataItem("Método de pago", $req["paymentMethod"])
            . $this->dataItem("Entrega", $req["shippingZone"])
            . $this->dataItem("Nota", $req["note"])
            ;
        $urlEncodedText = urlencode($text);
        return "https://wa.me/$compPhoneNumber?text=$urlEncodedText";
    }
    public function create(Request $req){
        $req->validate([
            'name' => 'required|min:3|max:75',
        ]);

        return redirect($this->generateURL($req));
        //return $this->generateURL($req);
        return $req["name"];
    }
}

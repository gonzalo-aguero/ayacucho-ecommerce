<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    private function getBusinessPhoneNumber(){
        $result = Order::find(1);
        $number = $result->lastPhoneNumber;//current number in database
        $number1 = config('company.phone_number_1');//option 1
        $number2 = config('company.phone_number_2');//option 2

        //alternate number in database
        if($number == 1 && $number2 != null){
            $result->lastPhoneNumber = 2;
            $result->save();
        }else if($number == 2 && $number1 != null){
            $result->lastPhoneNumber = 1;
            $result->save();
        }

        //choose the correct number to return
        if($number == 1 && $number1 != null){
            $number = $number1;
        }else if($number == 2 && $number2 != null){
            $number = $number2;
        }else{
            abort(500, "No company phone number specified");
        }

        return $number;
    }
    private function title($text){
        return "*$text:*\n";
    }
    private function dataItem($title, $value){
        return "   *$title:* ". $value ."\n";
    }
    /**
     * Generates a WhatsApp URL with the order detail as message.
     * */
    private function generateURL(Request $request, $compPhoneNumber){
        $compName = config("app.name");

        // Get the cart from the cookies and the products from its json file.
        $products = file_get_contents(public_path('json/Productos.json'));
        $products = json_decode($products);
        $cart = json_decode($_COOKIE["cart"]);
        $productsText = "";
        // Add each cart item to the $text variable.
        foreach ($cart as $item) {
            $id = $products[$item->pos]->id;
            $description = $products[$item->pos]->description;

            $productsText .= $this->dataItem("[$id] x".$item->units, $description);
        }

        $text = "Hola *$compName*, esta es la información de mi pedido:\n\n"
            . $this->title("DATOS DEL CLIENTE")
            . $this->dataItem("Nombre", $request["name"])
            . $this->dataItem("DNI", $request["dni"])
            . $this->dataItem("Correo", $request["email"])
            . $this->dataItem("Teléfono", $request["telephone"])
            . $this->dataItem("Localidad", $request["city"])
            . $this->dataItem("Dirección", $request["streetaddress"])
            . "\n"
            . $this->title("PRODUCTOS")
            . $productsText
            . "\n"
            . $this->title("DATOS DEL PEDIDO")
            . $this->dataItem("Entrega en", $request["_shippingZone"])
            . $this->dataItem("Método de pago", $request["_paymentMethod"])
            . (strlen($request["note"]) > 0 ? $this->dataItem("Nota", $request["note"]) : "")
            . $this->dataItem("Total del carrito", $request["cartTotal"])
            . ($request["shippingCost"] != "$0" ? $this->dataItem("Costo de envío", $request["shippingCost"]) : "")
            . $this->dataItem("Total del pedido", $request["orderTotal"])
            ;

        $urlEncodedText = urlencode($text);
        return "https://wa.me/$compPhoneNumber?text=$urlEncodedText";
    }
    public function create(Request $request){
        //PENDING: FULL VALIDATION!!!
        $request->validate([
            'name' => 'required|min:3|max:75',
        ]);

        return redirect($this->generateURL($request, $this->getBusinessPhoneNumber()))->withoutCookie('cart');
    }
}

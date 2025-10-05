<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PhoneRotator;
use App\Models\Order;

class OrderController extends Controller
{
    private function getBusinessPhoneNumber(){
        $result = PhoneRotator::find(1);
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
     * Convierte valores monetarios desde el formato argentino/europeo a decimal
     * Formato de entrada: "$62.942,50" o "$1.000" o "$70.236,2"
     * Formato de salida: 62942.50, 1000.00, 70236.20
     */
    private function parseMoneyValue($value)
    {
        if (is_numeric($value)) {
            return (float) $value;
        }

        // Remover el símbolo de moneda
        $cleaned = str_replace('$', '', $value);

        // Si no tiene coma, es un valor entero (ej: "$1.000" = 1000.00)
        if (strpos($cleaned, ',') === false) {
            // Solo remover puntos (separadores de miles)
            return (float) str_replace('.', '', $cleaned);
        }

        // Si tiene coma, separar la parte decimal
        $parts = explode(',', $cleaned);
        $integerPart = str_replace('.', '', $parts[0]); // Remover separadores de miles
        $decimalPart = isset($parts[1]) ? $parts[1] : '00';

        // Asegurar que la parte decimal tenga 2 dígitos
        $decimalPart = str_pad($decimalPart, 2, '0', STR_PAD_RIGHT);

        return (float) ($integerPart . '.' . $decimalPart);
    }

    /**
     * Formatea un valor decimal al formato monetario argentino
     * Entrada: 70236.20 -> Salida: "$70.236,20"
     */
    private function formatMoneyValue($value)
    {
        return '$' . number_format((float) $value, 2, ',', '.');
    }

    /**
     * Procesa los datos del carrito y los convierte en un array estructurado
     * para almacenar en la base de datos.
     *
     * @return array Array con los items del carrito estructurados
     */
    private function processCartItems()
    {
        $products = file_get_contents(public_path('json/Productos.json'));
        $products = json_decode($products, true);
        $cart = json_decode($_COOKIE["cart"], true);

        $cartItems = [];

        foreach ($cart as $item) {
            $product = $products[$item['pos']] ?? null;

            $cartItems[] = [
                'position' => $item['pos'],
                'product_id' => $product['id'] ?? null,
                'description' => $product['description'] ?? 'Producto no encontrado',
                'units' => $item['units'],
                'option' => $item['option'] ?? null,
                'unit_price' => $product['price'] ?? null,
            ];
        }

        return $cartItems;
    }
    /**
     * Generates a WhatsApp URL containing the order details as a pre-filled message.
     *
     * This method constructs a WhatsApp URL that, when visited, opens a chat with the company's phone number
     * and pre-fills the message field with the customer's order information, including client data, products,
     * and order details.
     *
     * @param \Illuminate\Http\Request $request The HTTP request containing order and customer data.
     * @param string $compPhoneNumber The company's WhatsApp phone number to send the message to.
     * @return string The generated WhatsApp URL with the order details as a message.
     */
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

            if(isset($item->option)){
                $productsText .= $this->dataItem("[$id] x".$item->units, '('.$item->option.') '.$description);
            }else {
                $productsText .= $this->dataItem("[$id] x".$item->units, $description);
            }
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
        $request->validate([
            'name' => 'required|min:3|max:50',
            'dni' => 'required|min:5|max:12',
            'email' => 'max:50',
            'telephone' => 'required|min:7|max:18',
            'city' => 'required|min:2|max:50',
            'streetaddress' => 'required|min:5|max:50',
            'note' => 'max:250',
            '_shippingZone' => 'required|min:2|max:100',
            '_paymentMethod' => 'required|min:2|max:100',
            'cartTotal' => 'required|min:1|max:100',
            'shippingCost' => 'required|min:1|max:100',
            'orderTotal' => 'required|min:1|max:100',
        ]);

        // Obtener el número de teléfono de la empresa
        $companyPhone = $this->getBusinessPhoneNumber();

        // Generar la URL de WhatsApp
        $whatsappUrl = $this->generateURL($request, $companyPhone);

        // Procesar los items del carrito
        $cartItems = $this->processCartItems();

        // Registrar el pedido en la base de datos
        $order = Order::create([
            'customer_name' => $request->name,
            'customer_dni' => $request->dni,
            'customer_email' => $request->email,
            'customer_phone' => $request->telephone,
            'customer_city' => $request->city,
            'customer_address' => $request->streetaddress,
            'shipping_zone' => $request->_shippingZone,
            'payment_method' => $request->_paymentMethod,
            'customer_note' => $request->note,
            'cart_items' => $cartItems,
            'cart_total' => $this->parseMoneyValue($request->cartTotal),
            'shipping_cost' => $this->parseMoneyValue($request->shippingCost),
            'order_total' => $this->parseMoneyValue($request->orderTotal),
            'company_phone_used' => $companyPhone,
            'whatsapp_url' => $whatsappUrl,
        ]);

        // Redireccionar a WhatsApp y limpiar el carrito
        return redirect($whatsappUrl)->withoutCookie('cart');
    }

    /**
     * Obtiene todos los pedidos registrados con paginación
     * (Método útil para verificar los registros)
     */
    public function index(Request $request)
    {
        $query = Order::query();

        // Filtros opcionales
        if ($request->has('customer_dni')) {
            $query->where('customer_dni', 'LIKE', '%' . $request->customer_dni . '%');
        }

        if ($request->has('customer_phone')) {
            $query->where('customer_phone', 'LIKE', '%' . $request->customer_phone . '%');
        }

        if ($request->has('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->has('shipping_zone')) {
            $query->where('shipping_zone', $request->shipping_zone);
        }

        if ($request->has('date_from') && $request->has('date_to')) {
            $query->byDateRange($request->date_from, $request->date_to);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $orders,
            'message' => 'Pedidos obtenidos exitosamente'
        ]);
    }

    /**
     * Obtiene un pedido específico por su ID
     */
    public function show($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Pedido no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $order,
            'message' => 'Pedido obtenido exitosamente'
        ]);
    }

    /**
     * Obtiene estadísticas básicas de los pedidos
     */
    public function stats()
    {
        $totalOrders = Order::count();
        $todayOrders = Order::whereDate('created_at', today())->count();
        $thisMonthOrders = Order::whereMonth('created_at', now()->month)
                                ->whereYear('created_at', now()->year)
                                ->count();

        $popularPaymentMethods = Order::selectRaw('payment_method, COUNT(*) as count')
                                    ->groupBy('payment_method')
                                    ->orderBy('count', 'desc')
                                    ->get();

        $popularShippingZones = Order::selectRaw('shipping_zone, COUNT(*) as count')
                                   ->groupBy('shipping_zone')
                                   ->orderBy('count', 'desc')
                                   ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'total_orders' => $totalOrders,
                'today_orders' => $todayOrders,
                'this_month_orders' => $thisMonthOrders,
                'popular_payment_methods' => $popularPaymentMethods,
                'popular_shipping_zones' => $popularShippingZones,
            ],
            'message' => 'Estadísticas obtenidas exitosamente'
        ]);
    }
}

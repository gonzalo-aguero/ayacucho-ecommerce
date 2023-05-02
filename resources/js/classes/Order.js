"use strict";
class Order{
    constructor(){
    }
    total(){
        let orderTotal = 0;

        let cartTotal = Alpine.store("cart").total();

        const shippingZone = Alpine.store("selectedShippingZone");
        if(shippingZone !== undefined){
            orderTotal += shippingZone.cost;
        }else{
            console.log("Falta seleccionar la zona de envío.");
        }

        const paymentMethod = Alpine.store("selectedPaymentMethod");
        if(paymentMethod !== undefined){
            cartTotal = Alpine.store("paymentMethods").applyPercent(cartTotal, paymentMethod);
        }else{
            console.log("Falta seleccionar el método de pago.");
        }
        orderTotal += cartTotal;

        return orderTotal;
    }

}

export default Order;

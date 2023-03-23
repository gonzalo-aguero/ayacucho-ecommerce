"use strict";
class Order{
    constructor(){
    }
    total(){
        let orderTotal = 0;

        const cartTotal = Alpine.store("cart").total();
        orderTotal += cartTotal;

        const shippingZone = Alpine.store("selectedShippingZone");
        if(shippingZone !== undefined){
            orderTotal += shippingZone.cost;
        }else{
            console.log("Falta seleccionar la zona de envío.");
        }

        const paymentMethod = Alpine.store("selectedPaymentMethod");
        if(paymentMethod !== undefined){
            orderTotal = Alpine.store("paymentMethods").applyPercent(orderTotal, paymentMethod);
        }else{
            console.log("Falta seleccionar el método de pago.");
        }

        return orderTotal;
    }

}

export default Order;

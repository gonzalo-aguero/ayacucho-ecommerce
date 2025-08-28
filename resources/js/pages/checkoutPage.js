import { store } from '../helpers/store';
import PaymentMethods from '../classes/PaymentMethods';
import ShippingZones from '../classes/ShippingZones';
import Order from '../classes/Order';

export async function CHECKOUT_PAGE(){
    if(DEBUG) console.log("THIS IS THE CHECKOUT PAGE.");

    store("selectedPaymentMethod", undefined);
    store("selectedShippingZone", undefined);

    store("paymentMethods", new PaymentMethods());
    store("shippingZones", new ShippingZones());

    await store("paymentMethods").load();
    if(DEBUG) console.log("MÉTODOS DE PAGO: ",store("paymentMethods").methods);
    await store("shippingZones").load();
    if(DEBUG) console.log("ZONAS: ",store("shippingZones").zones);

    store("order", new Order(store("cart")));

    store("showSummary", ()=>{
        return (
            store("cart") !== undefined
            && store("paymentMethods") !== undefined
            && store("shippingZones") !== undefined
        );
    });
}

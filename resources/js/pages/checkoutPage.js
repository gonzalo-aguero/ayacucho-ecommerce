import { store } from '../helpers/store';
import PaymentMethodService from '../services/PaymentMethodService';
import ShippingZoneService from '../services/ShippingZoneService';
import Order from '../classes/Order';
import { gotoHome } from '../routing/navigation';

export async function CHECKOUT_PAGE(){
    if(DEBUG) console.log("THIS IS THE CHECKOUT PAGE.");

    // Validate cart state first
    const cart = store("cart");
    if (!cart || cart.length() === 0) {
        console.warn("Cart is empty, redirecting to home");
        gotoHome();
        return;
    }

    // Create instances of services
    const paymentMethodService = new PaymentMethodService();
    const shippingZoneService = new ShippingZoneService();

    // Store instances of services in global store
    store("paymentMethodService", paymentMethodService);
    store("shippingZoneService", shippingZoneService);

    // Initialize other store properties
    store("displayPaymentMethods", false); //it is used to show them when they are already loaded.
    store("displayShippingZones", false); //it is used to show them when they are already loaded.
    store("order", new Order(store("cart")));

    // Computed property to determine if summary can be shown
    store("showSummary", ()=>{
        return (
            store("cart") !== undefined
            && store("paymentMethodService") !== undefined
            && store("shippingZoneService") !== undefined
        );
    });

    // Load data
    await paymentMethodService.load();
    store("displayPaymentMethods", paymentMethodService.isLoaded());
    await shippingZoneService.load();
    store("displayShippingZones", shippingZoneService.isLoaded());

    if(DEBUG) console.log("MÉTODOS DE PAGO: ",paymentMethodService.methods);
    if(DEBUG) console.log("ZONAS DE ENVIO: ", shippingZoneService.zones);
}

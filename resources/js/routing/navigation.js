import { store } from '../helpers/store';

export function gotoCheckout(route){
    console.log(store("cart").length());
    if(store("cart").length() === 0){
        store("Notify").Warning("Su carrito está vacío.", 1250);
    }else location.href = route;
}

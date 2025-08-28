import { store } from '../helpers/store';

export function PRODUCT_PAGE(){
    if(DEBUG) console.log("This is the Product Page!");
    store("selectedVariation", undefined);
}

import { store } from '../utils/store';

export function PRODUCT_PAGE(){
    if(DEBUG) console.log("This is the Product Page!");

    const variationService = store("variationService");

    store("selectedVariation", undefined);
}

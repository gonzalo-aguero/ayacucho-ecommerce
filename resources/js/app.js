"use strict";
import Cart from './classes/Cart';
import ProductService from './services/productService';
import NotificationService from './services/notificationService';
import Notify from './notification/notify';
import VariationService from './services/variationService';
import { priceFormat, decimalFormat } from './utils/format';
import { store } from './utils/store';
import { Confirm } from './ui/confirm';
import { gotoCheckout } from './routing/navigation';
import { loadProducts } from './products/catalog';
import { HOME_PAGE } from './pages/homePage';
import { CHECKOUT_PAGE } from './pages/checkoutPage';
import { PRODUCT_PAGE } from './pages/productPage';
import GoogleReviews from './classes/GoogleReviews';
import { initProductSearchModal } from './components/productSearchModal';


if(DEBUG) console.log("Code working");

// Initialize components
initProductSearchModal();

document.addEventListener('alpine:init', async function(){
    const cart = new Cart(store);
    store('cart', cart);

    store('Notify', Notify);
    const notificationService = new NotificationService(Notify);
    store('notificationService', notificationService);

    const variationService = new VariationService();
    store("variationService", variationService);
    store('variations', variationService.options);

    const productService = new ProductService(cart, notificationService, variationService)
    store('productService', productService);
    store('products', productService.products);
    store('productsLength', productService.products.length);
    store('sortedProducts', productService.sortedProducts);
    store('productsToPrint', productService.productsToPrint);
    store("printedProductsMax", productService.printedProductsMax);

    // UI state
    store('cartOpened', false);
    store('searchModalOpened', false);
    store("ConfirmVisible", false);

    // Utility functions and formats
    store('priceFormat', priceFormat);
    store('decimalFormat', decimalFormat);
    store('Confirm', Confirm);
    store("gotoCheckout", gotoCheckout);

    await loadProducts();
    await variationService.load();
    store('variations', variationService.options);

    const googleReviews = new GoogleReviews();
    store('googleReviews', googleReviews);

    const path = window.location.pathname;
    if(path === "/") HOME_PAGE();
    else if(path === "/checkout") CHECKOUT_PAGE();
    else if(IS_PRODUCT_PAGE) PRODUCT_PAGE();
});

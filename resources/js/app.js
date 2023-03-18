//import './bootstrap';
import Cart from './classes/Cart';
import PaymentMethods from './classes/PaymentMethods';
import { StaticProduct } from './classes/Product';
import ShippingZones from './classes/ShippingZones';
import Notify from './Notification-Bar/notify';
//import {AxiosHeaders} from 'axios';
"use strict";

if(DEBUG) console.log("Code working");

// helper
function store(key, value){
    if(value === undefined){
        return Alpine.store(key);
    }else{
        Alpine.store(key, value);
    }
}


document.addEventListener('alpine:init', async function(){
    store('products', []);
    store('productsLength', 0);
    store('sortedProducts', []);
    store('productsToPrint', []);
    store("printedProductsMax", false);
    store('cart', new Cart());
    store('StaticProduct', StaticProduct);
    store('Notify', Notify);
    store('cartOpened', false);
    // *** HELPERS ***
    store('priceFormat', priceFormat);
    store('Confirm', Confirm);

    await loadProducts();
    console.log("PRODUCTOS CARGADOS 2");

    Notify.Settings = {
        soundsOff: false,
        animDuration: {
            success: 5000,
            warning: 5000,
            error: 5000
        }
    };

    const path = window.location.pathname;
    switch(path){
        case '/':
            HOME();
            break;
        case '/checkout':
            CHECKOUT();
            break;
    }



           //setTimeout(()=>{
        //let interval = setInterval(()=>{
            //printMoreProducts();
        //}, 3000);
    //}, 2000);

    //Livewire.emit("setProductsLoaded");
});
function sortByCategories(){
    const sortedProducts = [];
    let currCategory;
    let index;
    let category;
    store("products").forEach( product => {
        currCategory = product.category;
        // index of category
        index = sortedProducts.findIndex( el => el.category === currCategory);
        if(index === -1){// the category hasn't been added yet
            category = {
                category: currCategory,
                products: []
            };
            category.products.push(product);
            sortedProducts.push(category);
        }else{
            sortedProducts[index].products.push(product);
        }
    });

    //Object.assign(GLOBAL.sortedProducts, sortedProducts);
    store("sortedProducts", sortedProducts);
}
/**
 * Copy from "ordered products" at most "max" products to "productsToPrint"
 **/
function printProducts(max){
    let productsToPrint = [];
    let currCategory;

    if(max !== false){
        store("sortedProducts").forEach( categoryItem => {
            currCategory = {
                category: categoryItem.category,
                products: []
            };
            currCategory.products = categoryItem.products.slice(0, max);
            productsToPrint.push(currCategory);
        });
    }else{
        //Object.assign(productsToPrint, GLOBAL.sortedProducts);
        store("productsToPrint", store("sortedProducts"));// !
    }

    if(DEBUG) console.log("Products To Print:",productsToPrint);
    //store('productsToPrint', productsToPrint);
    store("productsToPrint", store("sortedProducts"));// !
}
/**
 * Update the "GLOBAL.printedProductsMax" and reprint products
 **/
function printMoreProducts(){
    const prevValue = store('printedProductsMax');
    store('printedProductsMax', prevValue + 10);
    printProducts(store('printedProductsMax'));
}
async function loadProducts(){
    await fetch("json/Productos.json")
        .then(response => response.json())
        .then(data => {
            store('products', data);
            store('productsLength', data.length);
            if(DEBUG) console.log("Productos sin ordenar:", store('products'));

            sortByCategories();
            store('sortedProducts', store("sortedProducts"));
            if(DEBUG) console.log("Productos ordenados:", store("sortedProducts"));

            printProducts(store("printedProductsMax"));
            console.log("PRODUCTOS CARGADOS");
        });
}
function priceFormat(value){
    return '$' + new Intl.NumberFormat("de-DE").format(
        value,
    );
}
function Confirm(message){
    return confirm(message);
}
function HOME(){
    console.log("THIS IS THE HOME PAGE.");
}
async function CHECKOUT(){
    console.log("THIS IS THE CHECKOUT PAGE.");

    store("paymentMethodSelected", undefined);
    store("shippingZoneSelected", undefined);

    store("paymentMethods", new PaymentMethods());
    store("shippingZones", new ShippingZones());

    //(async ()=>{
        await store("paymentMethods").load();
        console.log(store("paymentMethods").methods);
        await store("shippingZones").load();
    console.log("ZONAS:",store("shippingZones").zones);
    //})();

}

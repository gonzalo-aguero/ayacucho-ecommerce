//import './bootstrap';
import Cart from './cart';
import { StaticProduct } from './product';
import Notify from './Notification-Bar/notify';
import {AxiosHeaders} from 'axios';
"use strict";

var GLOBAL = {
    products: [],
    sortedProducts: [],
    printedProductsMax: false,
};

if(DEBUG) console.log("Code working");

document.addEventListener('alpine:init', async function(){
    Alpine.store('products', []);
    Alpine.store('productsLength', 0);
    Alpine.store('sortedProducts', []);
    Alpine.store('productsToPrint', []);
    Alpine.store('cart', new Cart());
    Alpine.store('StaticProduct', StaticProduct);
    Alpine.store('Notify', Notify);
    Alpine.store('cartOpened', false);

    // *** HELPERS ***
    Alpine.store('priceFormat', priceFormat);
    Alpine.store('Confirm', Confirm);

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

    console.log("PRODUCTOS CARGADOS 3");
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
    GLOBAL.products.forEach( product => {
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

    Object.assign(GLOBAL.sortedProducts, sortedProducts);
}
/**
 * Copy from "ordered products" at most "max" products to "productsToPrint"
 **/
function printProducts(max){
    let productsToPrint = [];
    let currCategory;

    if(max !== false){
        GLOBAL.sortedProducts.forEach( categoryItem => {
            currCategory = {
                category: categoryItem.category,
                products: []
            };
            currCategory.products = categoryItem.products.slice(0, max);
            productsToPrint.push(currCategory);
        });
    }else{
        Object.assign(productsToPrint, GLOBAL.sortedProducts);
    }

    //if(DEBUG) console.log("Products To Print:",productsToPrint);
    Alpine.store('productsToPrint', productsToPrint);
}
/**
 * Update the "GLOBAL.printedProductsMax" and reprint products
 **/
function printMoreProducts(){
    GLOBAL.printedProductsMax += 10;
    printProducts(GLOBAL.printedProductsMax);
}
async function loadProducts(){
    await fetch("json/Productos.json")
        .then(response => response.json())
        .then(data => {
            GLOBAL.products = data;
            Alpine.store('products', GLOBAL.products);
            Alpine.store('productsLength', GLOBAL.products.length);
            if(DEBUG) console.log("Productos sin ordenar:",GLOBAL.products);

            sortByCategories();
            Alpine.store('sortedProducts', GLOBAL.sortedProducts);
            if(DEBUG) console.log("Productos ordenados:", GLOBAL.sortedProducts);

            printProducts(GLOBAL.printedProductsMax);
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

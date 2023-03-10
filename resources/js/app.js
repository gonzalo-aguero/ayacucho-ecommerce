//import './bootstrap';
import Cart from './cart';
import Notify from './Notification-Bar/notify';
"use strict";

var GLOBAL = {
    products: [],
    sortedProducts: [],
    printedProductsMax: false,
};

if(DEBUG) console.log("Code working");

document.addEventListener('alpine:init', () => {
    Alpine.store('colors', ['Red', 'Orange', 'Yellow']);
    Alpine.store('products', []);
    Alpine.store('sortedProducts', []);
    Alpine.store('productsToPrint', []);
    loadProducts();

    Notify.Settings = {
        soundsOff: false,
        animDuration: {
            success: 5000,
            warning: 5000,
            error: 5000
        }
    };
    Alpine.store('Notify', Notify);

    setTimeout(()=>{
        let interval = setInterval(()=>{
            printMoreProducts();
        }, 3000);
    }, 2000);

    //Livewire.emit("setProductsLoaded");
    Alpine.store('cart', new Cart());
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

    if(DEBUG) console.log("Products To Print:",productsToPrint);
    Alpine.store('productsToPrint', productsToPrint);
}
/**
 * Update the "GLOBAL.printedProductsMax" and reprint products
 **/
function printMoreProducts(){
    GLOBAL.printedProductsMax += 10;
    printProducts(GLOBAL.printedProductsMax);
}
function loadProducts(){
    fetch("json/Productos.json")
        .then(response => response.json())
        .then(data => {
            GLOBAL.products = data;
            Alpine.store('products', GLOBAL.products);
            if(DEBUG) console.log("Productos sin ordenar:",GLOBAL.products);

            sortByCategories();
            Alpine.store('sortedProducts', GLOBAL.sortedProducts);
            if(DEBUG) console.log("Productos ordenados:", GLOBAL.sortedProducts);

            printProducts(GLOBAL.printedProductsMax);
        });
}

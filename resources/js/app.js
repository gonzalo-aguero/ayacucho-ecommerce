import './bootstrap';
"use strict";
console.log("code working");
const GLOBAL = {
    products: [],
    sortedProducts: []
};
document.addEventListener('alpine:init', () => {
    Alpine.store('colors', ['Red', 'Orange', 'Yellow']);
    Alpine.store('products', []);
    Alpine.store('sortedProducts', []);
    loadProducts();
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
function loadProducts(){
    fetch("json/Productos.json")
        .then(response => response.json())
        .then(data => {
            GLOBAL.products = data;
            Alpine.store('products', GLOBAL.products);
            console.log("Productos sin ordenar:",GLOBAL.products);

            sortByCategories();
            Alpine.store('sortedProducts', GLOBAL.sortedProducts);
            console.log("Productos ordenados:", GLOBAL.sortedProducts);
        });
}
window.onload = ()=>{
    Livewire.emit("setProductsLoaded");

    fetch("json/Variaciones.json")
        .then(response => response.json())
        .then(data => {
            //console.log(data);
        });

};


import { store } from '../utils/store';
import { handleError, logInfo } from '../utils/error';

export function sortByCategories(products){
    if(!Array.isArray(products)){
        throw new Error("The products parameter must be an array.");
    }

    const sortedProducts = [];
    let currCategory;
    let index;
    let category;

    products.forEach( product => {
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

    return sortedProducts;
}

/**
 * Copy from "sortedProducts" at most "max" products to "productsToPrint"
 * @param {number|Boolean} max - The maximum number of products to print. If set to `false`, all products will be printed.
 */
export function printProducts(max){
    let productsToPrint = [];
    let currCategory;
    const productService = store('productService');

    if(max >=  0){
        productService.sortedProducts.forEach( categoryItem => {
            currCategory = {
                category: categoryItem.category,
                products: []
            };
            // Take only "max" products from each category
            currCategory.products = categoryItem.products.slice(0, max);

            productsToPrint.push(currCategory);
        });
    }else{
        productsToPrint = productService.sortedProducts;
    }

    if(DEBUG) console.log("Products To Print:",productsToPrint);
    productService.productsToPrint = productsToPrint;
    store("productsToPrint", productsToPrint);
}

/**
 * Update the "printedProductsMax" and reprint products
 **/
export function printMoreProducts(){
    const prevValue = store('printedProductsMax');
    store('printedProductsMax', prevValue + 10);
    printProducts(store('printedProductsMax'));
}

export async function loadProducts(){
    await fetch(location.origin + "/json/Productos.json")
        .then(response => response.json())
        .then(data => {
            const productService = store('productService');

            productService.products = data;
            if(DEBUG) console.log("Productos sin ordenar:", store('products'));
            store('products', data);
            store('productsLength', data.length);

            productService.sortedProducts = sortByCategories(data);
            if(DEBUG) console.log("Productos ordenados:", productService.sortedProducts);
            store('sortedProducts', productService.sortedProducts);

            printProducts(productService.printedProductsMax);
        });
}

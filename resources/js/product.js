"use strict";
class Product{
    data;
    constructor(data){
        this.data = data;
    };
    /**
     * Calculates the equivalent units in square meters.
     **/
    squareMeters(units){
        if(units >= 0) return (units * this.data.m2ByUnit).toPrecision(3);
        else return 0;
    }
    productPage(){
        let url ='producto/' + this.data.name;
        url = url.replace(/ /g, '-').toLowerCase();
        return url;
    }
    // Is measurable per square meter
    squareMeterMeasurable(){
        return this.data.m2Price != null && this.data.m2ByUnit != null;
    }
    addToCart({units}){
        console.log("Controlar que se envíen las unidades por referencia.");
        if(Alpine.store('cart').add(this.data, units)){
            Alpine.store('Notify').Success('Agregado al carrito', 1500);
            units = 1;
        }else{
            Alpine.store('Notify').Error('Ha ocurrido un error al agregar al carrito', 2000);
        }
    }
}
class StaticProduct{
    /**
     * Calculates the equivalent units in square meters.
     **/
    static squareMeters(units, productData){
        if(units >= 0) return (units * productData.m2ByUnit).toPrecision(3);
        else return 0;
    }
    static productPage(productData){
        let url ='producto/' + productData.name + '/' + productData.id;
        url = url.replace(/ /g, '-').toLowerCase();
        return url;
    }
    /**
     * Returns TRUE if it's measurable in square meter.
     **/
    static measurableInM2(productData){
        return productData.m2Price != null && productData.m2ByUnit != null;
    }
    static addToCart({units}, productData){
        if(Alpine.store('cart').add(productData, units)){
            Alpine.store('Notify').Success('Agregado al carrito', 1500);
            units = 1;
        }else{
            Alpine.store('Notify').Error('Ha ocurrido un error al agregar al carrito', 2000);
        }
    }


}

export { Product, StaticProduct };

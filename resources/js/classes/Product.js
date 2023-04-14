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
        if(units >= 0) return (units * this.data.m2ByUnit).toFixed(2);
        else return 0;
    }
    productPage(){
        let url = location.origin + '/' + this.data.name + '/' + this.data.id;
        url = url.replace(/ /g, '-').toLowerCase();
        return url;
    }
    // Is measurable per square meter
    squareMeterMeasurable(){
        return this.data.m2Price != null && this.data.m2ByUnit != null;
    }
    addToCart({units}){
        console.log("Controlar que se envíen las unidades por referencia.");
        if(units > 0){
            if(Alpine.store('cart').add(this.data, units)){
                Alpine.store('Notify').Success('Agregado al carrito', 1500);
                units = 1;
            }else{
                Alpine.store('Notify').Error('Ha ocurrido un error al agregar al carrito', 2000);
            }
        }else Alpine.store('Notify').Warning('Debe agregar una cantidad mayor a cero', 1500);
    }
}
class StaticProduct{
    /**
     * Calculates the equivalent units in square meters.
     **/
    static squareMeters(units, productData){
        if(units >= 0) return (units * productData.m2ByUnit).toFixed(2);
        else return 0;
    }
    static productPage(productData){
        let url = location.origin + '/' + productData.name + '/' + productData.id;
        url = url.replace(/ /g, '-').toLowerCase();
        return url;
    }
    /**
     * Returns TRUE if it's measurable in square meter.
     **/
    static measurableInM2(productData){
        return productData.m2Price != null && productData.m2ByUnit != null;
    }
    /**
     * units se pasa como objecto para que se pase como referencia y poder actualizar su valor desde el origen.
     **/
    static addToCart(units = 1, productData){
        units = parseInt(units);
        let success = false;
        if(units > 0){
            if(StaticProduct.canBeAdded(units, productData)){
                if(Alpine.store('cart').add(productData, units)){
                    Alpine.store('Notify').Success('Agregado al carrito', 1500);
                    success = true;
                }else{
                    Alpine.store('Notify').Error('Ha ocurrido un error al agregar al carrito', 2000);
                }
            }else Alpine.store("Notify").Warning("No hay suficiente stock disponible.")
        }else Alpine.store('Notify').Warning('Debe agregar al menos una unidad', 1500);

        return success;
    }
    /**
     * Returns True if "units to be added + units already added"
     * do not exceed the available stock.
     **/
    static canBeAdded(units = 1, productData){
        let can = true;
        const unitsInCart = Alpine.store("cart").getUnits(productData.id);
        can = this.validUnits(unitsInCart + units, productData);
        return can;
    }
    /**
     * This returns true if the number of units is
     * not greater than the available stock.
     */
    static validUnits(units, productData){
        let isValid;
        if(this.measurableInM2(productData)){
            const m2ByUnit = productData.m2ByUnit;
            isValid = units*m2ByUnit <= productData.units;
        }else{
            isValid = units <= productData.units;
        }

        return isValid;
    }
    /**
     * Returns the maximum number of boxes or packages.
     * In the case of being ceramic, it returns the maximum
     * available number of boxes according to the available square meters.
     **/
    static maxAvailableUnits(productData){
        let max;
        if(this.measurableInM2(productData)){
            max = parseFloat(productData.units) / parseFloat(productData.m2ByUnit);
        }else{
            max = productData.units;
        }

        return parseInt(max);
    }
}
export { Product, StaticProduct };

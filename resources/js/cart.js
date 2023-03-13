"use strict";
class Cart{
    content;
    constructor(){
        this.content = [];// [{item, units}, {item, units}]
        this.load();
    }
    /**
     * Saves the position of the product passed as parameter in the products global array.
     **/
    add(item, units = 1){
        units = parseInt(units);
        //Product position in products array
        const posInProducts = Alpine.store('products').findIndex( prod => prod.id === item.id);
        //Product position in content array (it's -1 if it's not there)
        const posInCart = this.content.findIndex( prod => prod.pos === posInProducts);
        if(posInCart !== -1){
            this.content[posInCart].units += units;
        }else{
            this.content.push({
                pos: posInProducts,
                units
            });
        }
        this.save();
        return true;
    }
    get content(){
        return this.content;
    }
    /**
     * Make units=false to remove all units
     **/
    remove(item, units = false){
        let done = false;
        //Product position in products array
        const posInProducts = Alpine.store('products').findIndex( prod => prod.id === item.id);
        //Product position in content array (it's -1 if it's not there)
        const posInCart = this.content.findIndex( prod => prod.pos === posInProducts);
        if(posInCart !== -1){
            this.content.splice(posInCart, 1);
            this.save();
            done = true;
        }

        return done;
    }
    clear(){
        this.content = [];
        this.save();
    }
    /**
     * Save Cart in Cookies
     **/
    save(){
        const secondsInADay = (60*60*24);
        const days = 21*secondsInADay;
        const expirationDate = new Date(new Date().getTime() + days*1000).toUTCString();
        document.cookie = `cart=${JSON.stringify(this.content)}; expires=date-in-GMTString-format=${expirationDate};`;
    }
    load(){
        // Cookie format: cookie1=value; cookie2=value
        const exists = document.cookie.split(';').some( item => item.includes("cart"));
        if(exists){
            const cartCookie = document.cookie.split(';').find( item => item.startsWith("cart=")).slice(5);
            let cart = JSON.parse(cartCookie);
            cart = cart.map( prod => {
                return { pos: prod.pos, units: parseInt(prod.units) };
            });
            this.content = cart;
        }else this.save();
    }
    length(){
        return this.content.length;
    }
    total(){
        let total = 0;
        this.content.forEach( item => {
            const prod = Alpine.store('products')[item.pos];
            total += prod.price * item.units;
        });
        return total;
    }
};

export default Cart;

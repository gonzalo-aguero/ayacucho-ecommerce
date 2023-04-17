"use strict";
class Cart{
    content;
    constructor(){
        this.content = [];// [{item, units, ?option}, {item, units, ?option}]
        this.load();
    }
    /**
     * Saves the position of the product passed as parameter in the products global array.
     **/
    add(productData, units = 1, option){
        units = parseInt(units);
        //Product position in products array
        const posInProducts = Alpine.store('products').findIndex( prod => prod.id === productData.id);
        //Product position in content array (it's -1 if it's not there)
        let posInCart;
        if(productData.variationId !== null){
            posInCart = this.content.findIndex( prod => prod.pos === posInProducts && prod.option == option);
        }else{
            posInCart = this.content.findIndex( prod => prod.pos === posInProducts);
        }

        if(posInCart !== -1){
            this.content[posInCart].units += units;
        }else{
            this.content.push({
                pos: posInProducts,
                units,
                option
            });
        }
        this.save();
        return true;
    }
    /**
     * Returns the content array.
     **/
    get content(){
        return this.content;
    }
    /**
     * Given a product id (string), it returns its units in the cart.
     **/
    getUnits(id, option){
        let units = false;
        //Product position in products array
        const posInProducts = Alpine.store('products').findIndex( prod => prod.id === id);
        if(posInProducts !== -1){
            //Product position in content array
            const product = Alpine.store("products")[posInProducts];
            let posInCart = -1;
            if(undefined === option)
                posInCart = this.content.findIndex( prod => prod.pos === posInProducts);
            else{
                posInCart = this.content.findIndex( prod => prod.pos === posInProducts && prod.option === option);
            }

            if(posInCart !== -1){
                units = this.content[posInCart].units;
            }else units = 0;
        }

        return units;
    }
    remove(item){
        let done = false;
        //Product position in content array (it's -1 if it's not there)
        const posInCart = this.content.findIndex( prod => prod.pos === item.pos);

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
        document.cookie = `cart=${JSON.stringify(this.content)}; expires=${expirationDate}; max-age=${days*21};path=/; SameSite=Lax`;
    }
    load(){
        try{
            // Cookie format: cookie1=value; cookie2=value
            const exists = document.cookie.split(';').some( item => item.includes("cart"));
            if(exists){
                let ws;// have white space (bool). Created because a bug was found in the Firefox browser.
                let cartCookie = document.cookie.split(';').find( item => {
                    if(item.startsWith("cart=")){
                        ws = false;
                        return true;
                    }else if(item.startsWith(" cart=")){
                        ws = true;
                        return true;
                    }
                });
                if(ws) cartCookie = cartCookie.slice(6);
                else cartCookie = cartCookie.slice(5);

                let cart = JSON.parse(cartCookie);
                cart = cart.map( prod => {
                    return { pos: prod.pos, units: parseInt(prod.units), option: prod.option };
                });
                this.content = cart;
            }else this.save();
        }catch(err){
            this.save();
            console.error("Cart has been saved empty.\n", err);
        }
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

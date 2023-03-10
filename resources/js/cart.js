"use strict";
class Cart{
    constructor(){
        this.content = [];// [{item, units}, {item, units}]
        this.load();
    }
    add(item, units){
        this.content.push({
            item,
            units
        });
        return true;
    }
    content(){
        return this.content;
    }
    /**
     * Make units=false to remove all units
     **/
    remove(item, units){
        return null;
    }
    clearCart(){
        this.content = [];
    }
    /**
     * Save Cart in Cookies
     **/
    save(){
        const secondsInADay = (60*60*24);
        const days = 21*secondsInADay;
        const expirationDate = new Date(new Date().getTime() + days*1000).toUTCString();
        document.cookie = `cart=${JSON.stringify(this.content())}; expires=${expirationDate};`;
    }
    load(){
        const exists = document.cookie.split(';').some( item => item.includes("cart"));
        if(exists){
            const cartCookie = document.cookie.split(';')
            let cart;
            this.content = cart;
        }else this.save();
    }
};

export default Cart;

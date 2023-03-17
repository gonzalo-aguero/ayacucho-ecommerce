"use strict";
class PaymentMethods{
    static api = "./json/MetodosDePago.json";
    constructor(methods = []){
        this.methods = methods;
    }
    get _methods(){
        return this.methods;
    }
    set _methods(methods = []){
        this.methods = methods;
    }
    async load(){
        await fetch(PaymentMethods.api)
            .then(response => response.json())
            .then(data => {
                this.methods = data;
            });
    }
}

export default PaymentMethods;

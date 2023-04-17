"use strict";
class PaymentMethods{
    static api = location.origin + "/json/MetodosDePago.json";
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
    /**
     * Returns a formated text with the name and percent of the payment method.
     **/
    getText(method){
        const percent = method.percent;
        let text;
        if(percent === 0){
            text = method.name;
        }else if(percent < 0){
            text = method.name + " ( " + percent*(-1) + "% de descuento )";
        }else if(percent > 0){
            text = method.name + " ( " + percent + "% de recargo )";
        }
        return text;
    }
    /**
     * Returns all the formated texts with the name and percent of each payment method.
     **/
    texts(){
        let texts = [];
        for(let i = 0; i < this.methods.length; i++){
            texts[i] = {
                name: this.getText(this.methods[i])
            }
        }
        return texts;
    }
    /**
     * Returns the value of the price with the discount or surcharge percentage
     * corresponding to the payment method passed as a parameter.
     **/
    applyPercent(value, method){
        const percent = method.percent;
        let finalValue;
        if(percent === 0){
            finalValue = value;
        }else{
            finalValue = value * (1 + percent / 100);// The negative percentage already has the less sign.
        }
        return finalValue;
    }
}

export default PaymentMethods;

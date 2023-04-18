class Variations{
    static api = location.origin + "/json/Variaciones.json";
    constructor(){
        this.list = [];
    }
    async load(){
        await fetch(Variations.api)
            .then(response => response.json())
            .then(data => {
                this.list = data;
                if(DEBUG) console.log("Variaciones:", data);
            });
    }
    get(variationId){
        return this.list[variationId - 1];
    }
    getValues(variationId){
        let values = [];
        this.list[variationId - 1].options.forEach( option => {
            values.push(option.value);
        });
        return values;
    }
    getByIndex(variationIndex){
        return this.list[variationIndex];
    }
    /**
     * Finds and returns the option data according to the "variationId" and "value" (the option value).
     **/
    getByValue(variationId, optionValue){
        const variation = this.list.find( (variation, index) => index === variationId-1);
        let optionData;
        if(undefined !== variation){
            optionData = variation.options.find( (option) => option.value === optionValue);
        }

        return optionData;
    }
    /**
     * Returns an array with all the formated texts with the option value.
     * It's used in the variation selector.
     **/
    texts(variationId){
        let texts = [];
        const options = this.list[variationId - 1].options;
        for(let i = 0; i < options.length; i++){
            texts[i] = {
                name: options[i].value
            }
        }
        return texts;
    }
}

export default Variations;

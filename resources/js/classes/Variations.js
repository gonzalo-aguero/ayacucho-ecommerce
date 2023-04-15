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
    getByIndex(variationIndex){
        return this.list[variationIndex];
    }
    /**
     * Finds and returns the option value according to the variationId and the value of the option.
     **/
    getByName(variationId, value){
        const optionData = this.list.find( (variation, index) => index === variationId-1).options.find( (option) => option === value );
        console.log("optionData", optionData);
    }
    /**
     * Returns all the formated texts with the name.
     * It's used in the variation selector.
     **/
    texts(variationId){
        let texts = [];
        const options = this.list[variationId - 1].options;
        for(let i = 0; i < options.length; i++){
            texts[i] = {
                name: options[i]
            }
        }
        return texts;
    }
}

export default Variations;

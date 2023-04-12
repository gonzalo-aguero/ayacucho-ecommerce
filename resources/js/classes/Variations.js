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

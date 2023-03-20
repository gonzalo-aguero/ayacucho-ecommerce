"use strict";
class ShippingZones{
    static api = "./json/ZonasDeEnvio.json";
    constructor(zones = []){
        this.zones = zones;
    }
    get _zones(){
        return this.zones;
    }
    set _zones(methods = []){
        this.zones = methods;
    }
    async load(){
        await fetch(ShippingZones.api)
            .then(response => response.json())
            .then(data => {
                this.zones = data;
            });
    }
    /**
     * Returns all the formated texts with the name and cost of each shipping zone.
     **/
    texts(){
        let texts = [];
        for(let i = 0; i < this.zones.length; i++){
            texts[i] = {
                name: this.zones[i].name + ": $" + this.zones[i].cost
            }
        }
        return texts;
    }
}

export default ShippingZones;

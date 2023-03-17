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
}

export default ShippingZones;

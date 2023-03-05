import './bootstrap';
window.onload = ()=>{
    console.log("code working")
    Livewire.emit("setProductsLoaded");

    fetch("json/Productos.json")
        .then(response => response.json())
        .then(data => {
            console.log(data);
        });
    fetch("json/Variaciones.json")
        .then(response => response.json())
        .then(data => {
            console.log(data);
        });

};


"use strict";
async function convert(e){
    e.preventDefault();
    const form = document.getElementById("ajax-form");
    const url = 'excel-conversion';

    const formData = new FormData();
    formData.append("_token", form['_token'].value);

    const init = {
        body: formData,
        method: 'POST',
    };

    await fetch(url, init)
        .then(response => response.text())
        .then(data => {
            console.log(data);
        });
}

document.addEventListener('alpine:init', () => {
    Alpine.store('convert', convert);
});

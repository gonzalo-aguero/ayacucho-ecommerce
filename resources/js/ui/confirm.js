import { store } from '../helpers/store';

export function Confirm(message, callback){
    const container = document.getElementById("aux_black_transparent_bg");
    const modal = document.getElementById("confirm_modal");

    const messageElement = modal.querySelector("p");
    messageElement.innerText = message;

    const confirmButton = modal.querySelector(".confirm_btn");
    confirmButton.innerText = "Confirmar";

    var cancelButton = modal.querySelector(".cancel_btn");
    cancelButton.innerText = "Cancelar";

    container.classList.replace("hidden", "fixed");
    modal.classList.replace("hidden", "fixed");
    store("ConfirmVisible", true);

    function hideModal() {
        modal.classList.replace("fixed", "hidden");
        container.classList.replace("fixed", "hidden");
        cancelButton.removeEventListener("click", clickCancelBtnHandler);
        confirmButton.removeEventListener("click", clickConfirmBtnHandler);
        setTimeout(()=>{
            store("ConfirmVisible", false);
        }, 500);
    }

    function clickCancelBtnHandler(){
        hideModal();
        callback(false);
    }
    cancelButton.addEventListener("click", clickCancelBtnHandler);

    function clickConfirmBtnHandler(){
        hideModal();
        callback(true);
    }
    confirmButton.addEventListener("click", clickConfirmBtnHandler);
}

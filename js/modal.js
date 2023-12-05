
//---------------------------------SHOW MODAL

//Funktion, um das Modal sichtbar zu machen (wird beim drücken des Submit-Buttons via validateForm-Funktion ausgeführt (falls nichts ausgewählt wurde))
function showModal() {
    console.log("modal :)");
    var modal = document.getElementById("modal_done");
    console.log("modal: "+modal);
    modal.style.display = "flex";
}
//Funktion, um das Modal zu schliessen 
function closeModal() {
    var modal = document.getElementById("modal_done");
    modal.style.display = "none";
}
//Falls Modal sichtbar, und auf das X geklickt wird, wird das Modal geschlossen 
document.getElementById("close-modal").addEventListener('click', function () {
    closeModal();
});

//Falls Modal sichtbar, und ausserhalb des Modals geklickt wird, wird das Modal geschlossen 
document.getElementById("modal_done").addEventListener('click', function (event) {
    if (event.target === this) {
        closeModal();
    }
});

// Für Touch-Events auf dem IOS-System: falls Modal sichtbar, und ausserhalb des Modals / auf das X geklickt wird, wird das Modal geschlossen 
document.getElementById("modal_done").addEventListener('touchstart', function (event) {
    if (event.target === this) {
        closeModal();
    }
});

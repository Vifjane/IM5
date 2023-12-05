//----------------------CHECK SCREENSIZE

//Prüft die Screenbreite und falls groösser als 600px, wird Message angezeigt, dass Site mit Mobile aufrufen muss.
function checkScreenWidth() {
    var screenWidth = window.innerWidth;
    var message = document.querySelector('.message');
    var wrapper = document.querySelector('.wrapper');



    if (screenWidth < 600) {
        // Verstecke die Nachricht und zeige das Formular
        message.style.display = 'none';
        wrapper.style.display = 'block';

    } else {
        // Zeige die Nachricht und verstecke das Formular
        message.style.display = 'block';
        wrapper.style.display = 'none';
    }
}


// Füge Event-Listener für das Resize-Ereignis hinzu
window.addEventListener('resize', checkScreenWidth);

// Führe die Funktion direkt nach dem Laden aus
window.addEventListener('DOMContentLoaded', checkScreenWidth)


//-----------------------ALERT EMTY USER ID
function displayAlertErrorUserIdMissing() {
    var overlay = document.createElement('div');
    overlay.setAttribute('style', 'position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 1000;');
    var modal = document.createElement('div');
    modal.setAttribute('style', 'position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 20px; z-index: 1001;');
    modal.innerHTML = '<p>Ups, hier lief etwas schief. Bitte kontaktiere das Ausstellungspersonal beim Eingang, um weiterzufahren! (Fehlercode: 005)</p>';
    overlay.appendChild(modal);
    document.body.appendChild(overlay);
}
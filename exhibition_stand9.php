<html>

<head>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="js/checkscreensize_userid.js"></script>
</head>

<body>

    <?php
    //DB-Verbindung einbinden
    require_once('db_connection.php');

    //Wenn user_id nicht in URL
    if (empty($_GET['user_id'])) {
        //Falls keine User_id gefunden, unblockierbares Alert anzeigen
        echo "<script>displayAlertErrorUserIdMissing();</script>";
    } else {
        //Per GET die UserID auslesen
        $userId = $_GET['user_id'];


    ?>
        <div class='wrapper'>

            <h3>Stand 9</h3>
            <h2>Farblabor</h2>
            <p>Schön farbig, nicht? Nimm jetzt ein Foto im Farblabor auf und sende es als digitale Postkarte deinen Liebsten zu!</p>

            <form action="" method="post" enctype="multipart/form-data">
                <label for="imageInput" style="cursor: pointer;">Bild aufnehmen</label>
                <input type="file" accept="image/*" capture="environment" name="imageInput" id="imageInput">
                <input type="text" name="email" class="textfield" size="30" placeholder="Gib hier die Email an.">
                <input type="submit" class="button" name="submit" value="Email senden &rarr;">
            </form>

            <a class="redirect" href="exhibition_stand10.php?user_id=<?php echo $userId; ?>" class="">Überspringen</a>
            <div class="fortschritt">
                <a href="exhibition_progress.php?user_id=<?php echo $userId; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 100 125" enable-background="new 0 0 100 100" xml:space="preserve">
                        <rect x="5" y="5" width="39.1" height="38.2" />
                        <rect x="5" y="56.3" width="39.1" height="38.7" />
                        <rect x="55.9" y="5" width="39.1" height="38.2" />
                        <rect x="55.9" y="56.3" width="39.1" height="38.7" />
                    </svg>
                </a>
            </div>
        </div><!--ende wrapper-->
        <div class="message">
            <h1>Nutze dein Mobiltelefon, um die Seite aufzurufen.</h1>
        </div>
        <script src="js/modal.js"></script>
        <script>
            //auf werte des HTML zugreifen
            const imageInput = document.getElementById('imageInput');
            const capturedImage = document.getElementById('capturedImage');

            //EventListener (zu imageInput) hinzufügen (reagiert auf change-event von ImageInput), immer ausgeführt wenn Person Bild macht
            imageInput.addEventListener('change', () => {
                //Gemachtes Bild wird aus imageInput abgerufen und in variable gespeichert
                const selectedFile = imageInput.files[0];

                //wenn Bild gemacht wurde...
                if (selectedFile) {
                    //..neues File-Reader Objekt erstellt (damit Dateien lesen & verarbeiten kann)
                    const reader = new FileReader();

                    //Funktion wird ausgeführt wenn lesen von Datei abgeschlossen, Parameter enthält Infos zu Ereignis
                    reader.onload = function(e) {
                        //der src (quelle) wird auf aufgenommenes Bild angepasst
                        capturedImage.src = e.target.result;
                        //aufgenommenes Bild wird angezeigt, per default display none
                        capturedImage.style.display = 'block';
                    };
                    //File-Reader liest Inhalt von aufgenommenem Bild (als URL), nötig um das Bild anzuzeigen
                    reader.readAsDataURL(selectedFile);
                }
            });
        </script>
</body>

<?php

        //Wenn aufgenommenes Bild vorhanden
        if (isset($_FILES['imageInput'])) {
            // Die Werte per "Post" aus dem Formular holen
            $to = $_POST['email'];

            //Absender, Betreff, Nachricht festlegen
            $from = "vifjane@gmail.com";
            $subject = "Selfie";
            $message = "Here is the captured image.";

            // Eindeutigen Grenzwert (boundary) für die Trennung von Teilen der E-Mail erstellen
            $boundary = md5(uniqid());

            //Header von Email erstellen
            $headers = "From: $from\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: multipart/mixed; boundary = $boundary\r\n";

            //Body von Email erstellen
            $body = "--$boundary\r\n";
            $body .= "Content-Type: text/plain; charset=ISO-8859-1\r\n";
            $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
            $body .= chunk_split(base64_encode($message));

            //Pfad vom hochgeladenen Bild abrufen (temp-Datei)
            $imageData = $_FILES['imageInput']['tmp_name'];

            //wenn Bild erfolgreich hochgeladen...
            if (is_uploaded_file($imageData)) {
                //...aufgenommenes Bild laden (erzeugt neues Bild aus der Datei)
                $image = imagecreatefromjpeg($imageData);

                //...den Sticker laden (erzeugt neues Bild aus der Datei)
                $sticker = imagecreatefrompng('img/sticker.png');

                //die Koordinaten für den Sticker berechnen (unten rechts)
                $stickerWidth = imagesx($sticker); //Breite von Sticker ermitteln
                $stickerHeight = imagesy($sticker); //Höhe von Sticker ermitteln
                $capturedWidth = imagesx($image); //Breite von aufgenommenem Bild ermitteln
                $capturedHeight = imagesy($image); //Höhe von aufgenommenem Bild ermitteln
                $x = $capturedWidth - $stickerWidth; //rechnet x-Wert aus
                $y = $capturedHeight - $stickerHeight; //rechnet y-Wert aus

                //Sticker auf aufgenommenes Bild klatschen
                imagecopy($image, $sticker, $x, $y, 0, 0, $stickerWidth, $stickerHeight);


                //fertiges Bild speichern
                $finalImage = 'captured_image_with_sticker.jpg';
                imagejpeg($image, $finalImage); //gibt Bild in Datei aus

                //gibt Arbeitsspeicher des Bildes frei
                imagedestroy($image);

                //Email mit Bild-Anhang vorbereiten
                $filename = 'captured_image_with_sticker.jpg';
                $fileData = file_get_contents($finalImage); //Liest Datei in einem String
                $fileData = chunk_split(base64_encode($fileData)); //Zerlegt Sring in richtiges Format

                $body .= "--$boundary\r\n";
                $body .= "Content-Type: image/jpeg; name=\"$filename\"\r\n";
                $body .= "Content-Transfer-Encoding: base64\r\n";
                $body .= "Content-Disposition: attachment; filename=\"$filename\"\r\n\r\n";
                $body .= $fileData;

                //Mail senden
                if (mail($to, $subject, $body, $headers)) {
                    echo "Email erfolgreich versendet!";
                } else {
                    echo "Email konnte nicht versendet werden.";
                }
            } else {
                echo "Hochladen des Bildes fehlgeschlagen.";
            }
        }
    } ?>

</html>
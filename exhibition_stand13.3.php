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


        // Array initialisieren, um anschliessend Daten darin zu speichern
        $existingAnswer = [];
        // Alle Daten der Progress-Tabelle holen, bei welcher der Status 2 oder 3 lautet (2=Insert in DB, 3 = Bewertung erfolgt)
        $selectQuery = "SELECT * FROM `progress` WHERE user_id='$userId' AND (stand13_3='2' OR stand13_3='3')";
        $result = $connection->query($selectQuery);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Daten von Progress-Tabelle im Array speichern
                $existingAnswer[] = [
                    'user_id' => $row['user_id']
                ];
            }
        }

    ?>
        <div class='wrapper'>
            <h3>Stand 13</h3>
            <h1>Namenszauber</h1>
            <p>Dir werden hier Fragen mit zugehörigen Wörtern angezeigt. Entscheide dich jeweils intiutiv für ein Wort.</p>
            <p class="question">Welcher Energydrink liefert mehr Energie?</p>
            <form action="" method="post" onsubmit="return validateForm()">
                <div class="buttons">
                    <input type="radio" name="name" value="1" id="radio1">
                    <label for="radio1">Calmvigor</label>
                    <input type="radio" name="name" value="2" id="radio2">
                    <label for="radio2">Megavolt</label>
                </div><!--ende buttons-->
                <input type="submit" class="button" name="submit" value="Weiter &rarr;">

            </form>
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
        <!-- Modal -->
        <div id="modal_done" class="modal_done">
            <div class="modal-content">
                <span id="close-modal" class="close-modal">&times;</span>
                <p>Wähle eine Antwort aus!</p>
            </div>
        </div>
        <div class="message">
            <h1>Nutze dein Mobiltelefon, um die Seite aufzurufen.</h1>
        </div>
        <div id="overlay" class="overlay">
            <div class="overlay-content">
                <p class="alreadyanswered">Diese Frage wurde von dir schon beantwortet.</p>
                <a class="redirect" href="exhibition_progress.php?user_id=<?php echo $userId; ?>" class="">Zur Übersicht &rarr;</a>
            </div>
        </div>
</body>
<?php
        //wenn antwort schon in array (also in Tabelle progress) dann Modal anzeigen
        if (!empty($existingAnswer)) {
            echo '<script>document.getElementById("overlay").style.display = "block";</script>';
        } else {
            //Ansonsten eine 1 (für bearbeitet) in die Tabelle schreiben
            $sql_progress = "UPDATE `progress` SET `stand13_3` = '1' WHERE `user_id` = '$userId'";
            $connection->query($sql_progress);
        }

        // Array initialisieren, um anschliessend Daten darin zu speichern
        $existingAnswers = [];

        // Alles von der Tabelle des Standes aus DB holen
        $selectQuery = "SELECT * FROM `stand13`";
        $result = $connection->query($selectQuery);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Daten in Array speichern
                $existingAnswers[] = [
                    'user_id' => $row['user_id'],
                    'name3' => $row['name3']

                ];
            }
        }

        //Wenn auf submit gedrückt wurde...
        if (isset($_POST['submit'])) {
            // Die Werte per "Post" aus dem Formular holen
            $name = $_POST["name"];


            // Überprüfe, ob die UserID bereits in der Progress-Tabelle existiert (VIVI:evtl kann dies gelöscht werden?)
            if (in_array(['user_id' => $userId, 'name3' => $name], $existingAnswers, true)) {
                echo "Fehler: Diese Frage wurde von dir schon beantwortet.";
            } else {

                // Antwort wird in die Tabelle des Standes eingetragen(user_id und Antwort)
                $sql = "UPDATE `stand13` SET `name3` = '$name' WHERE `user_id` = '$userId'";
                //Der Status des aktuellen Standes wird auf 2 (2=Antwort in DB gespeichert) aktualisiert
                $sql2 = "UPDATE `progress` SET `stand13_3` = '2' WHERE `user_id` = '$userId'";
                $connection->query($sql2);

                if ($connection->query($sql) === TRUE) {
                    //Weiterleitung auf nächste Seite
                    header("Location: exhibition_stand13_feedback.php?user_id=$userId");
                    exit;
                } else {
                    echo "Error: " . $sql . "<br>" . $connection->error;
                }
            }
        }

?>
<script src="js/modal.js"></script>
<script>
    //Funktion, um zu überprüfen, ob eine Antwort gegeben wurde (wird bei klick auf Submit ausgeführt)
    function validateForm() {
        var name = document.querySelector('input[name="name"]:checked');

        //Wenn nichts ausgefüllt wurde
        if (!name) {
            // Zeige das Modal bei unvollständigen Feldern
            showModal();
            return false;
        }

        return true;
    }
</script>
<?php } ?>

</html>
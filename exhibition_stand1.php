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
        $selectQuery = "SELECT * FROM `progress` WHERE user_id='$userId' AND (stand1='2' OR stand1='3')";
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
            <h3>Stand 1</h3>
            <h1>Produktdesign</h1>
            <p>Auf den drei Sockeln siehst du drei Milchflaschen mit unterschiedlichen Designs.</p>
            <p class="question">Welche würdest du kaufen?</p>
            <form action="" method="post" onsubmit="return validateForm()">
                <div class="buttons">
                    <input type="radio" name="design" value="1" id="radio1">
                    <label for="radio1">Design 1</label>
                    <input type="radio" name="design" value="2" id="radio2">
                    <label for="radio2">Design 2</label>
                    <input type="radio" name="design" value="3" id="radio3">
                    <label for="radio3">Design 3</label>
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
        <!-- Modal, welches angezeigt wird, wenn nicht alle Felder ausgefüllt sind (dient der Funktion validateForm), per default unsichtbar-->
        <div id="modal_done" class="modal_done">
            <div class="modal-content">
                <span id="close-modal" class="close-modal">&times;</span>
                <p>Fülle alle Felder aus!</p>
            </div>
        </div>
        <!--Falls der Bildschirm grösser als 600px, wird diese Message angezeigt, per default unsichtbar-->
        <div class="message">
            <h1>Nutze dein Mobiltelefon, um die Seite aufzurufen.</h1>
        </div>
        <!--Falls der User diese Frage schon beantwortet hat, wird der Overlay angezeigt (beispielsweise wenn der user nach der Feedbacksite zurück geht), per default unsichtbar-->
        <div id="overlay" class="overlay">
            <div class="overlay-content">
                <p class="alreadyanswered">Diese Frage wurde von dir schon beantwortet.</p>
                <a class="redirect" href="exhibition_progress.php?user_id=<?php echo $userId; ?>" class="">Zur Übersicht &rarr;</a>
            </div>
        </div>
</body>
<?php

        //wenn antwort schon in array (also in Tabelle progress) dann Overlay anzeigen
        if (!empty($existingAnswer)) {
            echo '<script>document.getElementById("overlay").style.display = "block";</script>';
        } else {
            //Ansonsten eine 1 (für bearbeitet) in die Tabelle schreiben
            $sql_progress = "UPDATE `progress` SET `stand1` = '1' WHERE `user_id` = '$userId'";
            $connection->query($sql_progress);
        }

        //Wenn auf Submit gedrückt wurde...
        if (isset($_POST['submit'])) {

            // Die Werte per "Post" aus dem Formular holen
            $design = $_POST["design"];

            // Überprüfe, ob die UserID bereits in der Progress-Tabelle existiert (VIVI:evtl kann dies gelöscht werden?)
            if (in_array(['user_id' => $userId], $existingAnswer, true)) {
                echo "Fehler: Diese Frage wurde von dir schon beantwortet!!!.";
            } else {
                // Antwort wird in die Tabelle des Standes eingetragen(user_id und Antwort)
                $sql = "INSERT INTO `stand1` (`user_id`, `design_id`) VALUES ('$userId', '$design')";

                //Der Status des aktuellen Standes wird auf 2 (2=Antwort in DB gespeichert) aktualisiert
                $sql2 = "UPDATE `progress` SET `stand1` = '2' WHERE `user_id` = '$userId'";
                $connection->query($sql2);

                if ($connection->query($sql) === TRUE) {
                    echo "Data inserted successfully" . $sql;
                    //Weiterleitung auf nächste Seite
                    echo '<script>window.location.href = "exhibition_stand1_feedback.php?user_id=' . $userId . '";</script>';
                    exit;
                } else {
                    echo "Error: " . $sql2 . "<br>" . $connection->error;
                }
            }
        }
?>
<script src="js/modal.js"></script>
<script>
    //Funktion, um zu überprüfen, ob eine Antwort gegeben wurde (wird bei klick auf Submit ausgeführt)
    function validateForm() {
        var design = document.querySelector('input[name="design"]:checked');

        //Wenn Feld nicht ausgefüllt...
        if (!design) {
            // ...zeige das Modal
            showModal();
            return false;
        }

        return true;
    }
</script>
<?php } ?>

</html>
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
        $selectQuery = "SELECT * FROM `progress` WHERE user_id='$userId' AND (stand3='2' OR stand3='3')";
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
            <h3>Stand 3</h3>
            <h1>Product Placement</h1>
            <p>Schau dir den Film auf dem Display aufmerksam an.</p>
            <p class="question">Welche Marken hast du im Filmausschnitt gesehen?</p>
            <form action="" method="post" onsubmit="return validateForm()">
                <div class="buttons">
                    <div class="row">
                        <input type="checkbox" name="ford" value="1" id="checkbox1">
                        <label for="checkbox1">Ford</label>
                        <span class="checkmark" id="checkmark1"></span>
                        <input type="checkbox" name="heineken" value="1" id="checkbox2">
                        <label for="checkbox2">Heineken</label>
                        <span class="checkmark" id="checkmark2"></span>
                        <input type="checkbox" name="mcdonalds" value="1" id="checkbox3">
                        <label for="checkbox3">McDonalds</label>
                        <span class="checkmark" id="checkmark3"></span>
                        <input type="checkbox" name="blackberry" value="1" id="checkbox4">
                        <label for="checkbox4">Black Berry</label>
                        <span class="checkmark" id="checkmark4"></span>
                    </div>
                    <div class="row">

                        <input type="checkbox" name="landrover" value="1" id="checkbox5">
                        <label for="checkbox5">Land Rover</label>
                        <span class="checkmark" id="checkmark5"></span>
                        <input type="checkbox" name="sonyericsson" value="1" id="checkbox6">
                        <label for="checkbox6">Sony Ericsscon</label>
                        <span class="checkmark" id="checkmark6"></span>
                        <input type="checkbox" name="rolex" value="1" id="checkbox7">
                        <label for="checkbox7">Rolex</label>
                        <span class="checkmark" id="checkmark7"></span>
                        <input type="checkbox" name="omega" value="1" id="checkbox8">
                        <label for="checkbox8">Omega</label>
                        <span class="checkmark" id="checkmark8"></span>
                    </div>
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
                <p>Fülle alle Felder aus!</p>
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
            $sql_progress = "UPDATE `progress` SET `stand3` = '1' WHERE `user_id` = '$userId'";
            $connection->query($sql_progress);
            // The array is not empty.
        }

        // Array initialisieren, um anschliessend Daten darin zu speichern
        $existingStand3Brands = [];

        // Alles von der Tabelle des Standes aus DB holen
        $selectQuery = "SELECT * FROM `stand3`";
        $result = $connection->query($selectQuery);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Daten in Array speichern
                $existingStand3Brands[] = [
                    'user_id' => $row['user_id'],
                    'marke1' => $row['marke1'],
                    'marke2' => $row['marke2'],
                    'marke3' => $row['marke3'],
                    'marke4' => $row['marke4'],
                    'marke5' => $row['marke5'],
                    'marke6' => $row['marke6'],
                    'marke7' => $row['marke7'],
                    'marke8' => $row['marke8'],

                ];
            }
        }
        //Wenn auf submit gedrückt wurde...
        if (isset($_POST['submit'])) {
            // Die Werte per "Post" aus dem Formular holen
            $ford = $_POST["ford"];
            $heineken = $_POST["heineken"];
            $mcdonalds = $_POST["mcdonalds"];
            $blackberry = $_POST["blackberry"];
            $landrover = $_POST["landrover"];
            $sonyericsson = $_POST["sonyericsson"];
            $rolex = $_POST["rolex"];
            $omega = $_POST["omega"];

            // Überprüfe, ob die UserID bereits in der Progress-Tabelle existiert (VIVI:evtl kann dies gelöscht werden?)
            if (in_array(['user_id' => $userId], $existingStand3Brands, true)) {
                echo "Fehler: Diese Frage wurde von dir schon beantwortet.";
            } else {
                // Antwort wird in die Tabelle des Standes eingetragen(user_id und Antwort)
                $sql = "INSERT INTO `stand3` (`user_id`, `marke1`, `marke2`,`marke3`,`marke4`,`marke5`,`marke6`,`marke7`,`marke8`) VALUES ('$userId', '$ford', '$heineken', '$mcdonalds', '$blackberry', '$landrover', '$sonyericsson', '$rolex', '$omega')";

                //Der Status des aktuellen Standes wird auf 2 (2=Antwort in DB gespeichert) aktualisiert
                $sql2 = "UPDATE `progress` SET `stand3` = '2' WHERE `user_id` = '$userId'";
                $connection->query($sql2);

                if ($connection->query($sql) === TRUE) {
                    //Weiterleitung auf nächste Seite
                    echo '<script>window.location.href = "exhibition_stand3_feedback.php?user_id=' . $userId . '";</script>';
                    exit;
                } else {
                    echo "Error: " . $sql . "<br>" . $connection->error;
                }
            }
        }

?>
<script src="js/modal.js"></script>
<script>
    //Funktion, um zu überprüfen, ob eine Antwort gegeben wurde (wird via validateForm bei klick auf Submit ausgeführt)
    function isAnyCheckboxChecked() {
        var checkboxes = document.querySelectorAll('input[name="ford"], input[name="heineken"], input[name="mcdonalds"], input[name="blackberry"], input[name="landrover"], input[name="sonyericsson"], input[name="rolex"], input[name="omega"]');
        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].checked) {
                return true;
            }
        }
        return false;
    }

    //Funktion, um zu überprüfen, ob eine Antwort gegeben wurde (wird bei klick auf Submit ausgeführt)
    function validateForm() {

        //wenn keine Checkbox ausgefüllt wurde...
        if (!isAnyCheckboxChecked()) {
            // ...zeige das Modal
            showModal();
            return false;
        }

        return true;
    }
</script>
<?php } ?>

</html>
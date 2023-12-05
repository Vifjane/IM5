<html>

<head>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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


        // Alle Daten von der registrieren-Tabelle holen (bei welcher UserID der Get-UserID entspricht)
        $selectQuery1 = "SELECT * FROM `registrieren` WHERE user_id='$userId'";
        $result = $connection->query($selectQuery1);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Daten von Registrieren-Tabelle im Array speichern
                $character = [
                    'character' => $row['character']
                ];
            }
        }



        // Punkte von der progress-Tabelle holen
        $selectQuery = "SELECT punkte FROM `progress` WHERE user_id='$userId'";
        $result = $connection->query($selectQuery);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Daten von Progress-Tabelle im Array speichern
                $punkte = [
                    'punkte' => $row['punkte']
                ];
            }
        }

        //Entsprechender Status, Hintergrundbild und Feedbacktext anzeigen je nach Punktestand
        if ($punkte['punkte'] <= 6.25) {
            $status = "Werbungs-Novize";
            $feedbacktext = "Du bist auf dem Weg, mehr über Werbung zu lernen. Es gibt noch viele faszinierende Aspekte zu entdecken. Nimm dir Zeit, verschiedene Werbungen genauer unter die Lupe zu nehmen, um die Kniffe und Feinheiten zu erkennen, Übung macht schließlich den Meister! Weiter so, es gibt noch viel zu lernen!";
            $backgroundpath = "url('/img/novize.svg')";
        } elseif ($punkte['punkte'] <= 12.5) {
            $status = "Werbungs-Entdecker";
            $feedbacktext = "Nicht schlecht! Du hast einige Grundlagen der Werbung verstanden, aber es gibt noch Raum für Wachstum. Mit mehr Erfahrung und Interesse kannst du sicherlich zum Werbeexperten aufsteigen.";
            $backgroundpath = "url('/img/entdecker.svg')";
        } elseif ($punkte['punkte'] <= 18.75) {
            $status = "Werbungs-Wissender";
            $feedbacktext = " Gut gemacht! Du hast solide Kenntnisse in der Welt der Werbung. Dein Verständnis für Werbestrategien und -taktiken ist beachtlich. Es gibt immer Raum für Verbesserungen, aber du bist auf dem richtigen Weg!";
            $backgroundpath = "url('/img/wissender.svg')";
        } elseif ($punkte['punkte'] <= 25) {
            $status = "Marketing-Meister";
            $feedbacktext = "Herzlichen Glückwunsch! Du bist ein echter Werbekenner. Dein Wissen über Werbung ist beeindruckend. Du erkennst die Feinheiten und Nuancen der Branche. Keep it up!";
            $backgroundpath = "url('/img/meister.svg')";
        }




    ?>
        <div class='wrapper'>
            <h3>Dein Resultat</h3>
            <h1>Auswertung</h1>
            <div class="auswertung">
                <p>Du bist ein <br /><span class="markierung big"><?php echo $status ?></span></p>
                <!-- <img src="/img/magier.svg"> -->
                <div class="status" style="background-image: <?php echo $backgroundpath ?>;">
                    <img class="avatar" src="/img/<?php echo $character['character']; ?>.png">
                </div>
                <p><?php echo $feedbacktext; ?></p>

                <p>Ich hoffe du konntest in der Ausstellung viel lernen und bist nun auf die Werbung sensibilisiert!</p>
            </div>
        </div><!--ende wrapper-->
        <div class="message">
            <h1>Nutze dein Mobiltelefon, um die Seite aufzurufen.</h1>
        </div>
        <div id="overlay" class="overlay">
            <div class="overlay-content">
                <p class="alreadyanswered">Beantworte zuerst alle Fragen und sieh dir dann die Auswertung an.</p>
                <a class="redirect" href="exhibition_progress.php?user_id=<?php echo $userId; ?>" class="">Zur Übersicht &rarr;</a>
            </div>
        </div>
</body>
<?php

        //Prüfe, ob alle Stände den Status 3 haben
        $selectQuery2 = "SELECT * FROM `progress` WHERE user_id='$userId'";
        $result = $connection->query($selectQuery2);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Daten von Progress-Tabelle im Array speichern
                $allesAusgefuellt[] = [
                    'stand1' => $row['stand1'],
                    'stand3' => $row['stand3'],
                    'stand7_1' => $row['stand7_1'],
                    'stand7_2' => $row['stand7_2'],
                    'stand7_3' => $row['stand7_3'],
                    'stand8' => $row['stand8'],
                    'stand10' => $row['stand10'],
                    'stand11' => $row['stand11'],
                    'stand12' => $row['stand12'],
                    'stand13_1' => $row['stand13_1'],
                    'stand13_2' => $row['stand13_2'],
                    'stand13_3' => $row['stand13_3']
                ];
            }
        }
        $alleStaendeGefuellt = true;

        //Schleife für zweidimensionales Array
        foreach ($allesAusgefuellt as $stand) {
            foreach ($stand as $value) {
                //überprüfung, ob Wert nicht gleich 3
                if ($value != 3) {
                    $alleStaendeGefuellt = false;
                    break 2; // Breche beide Schleifen ab, wenn Wert nicht gleich 3 ist
                }
            }
        }

        //wenn nicht alle Stände als Status 3 (3=Punkte erhalten) besitzen
        if (!$alleStaendeGefuellt) {
            //Overflow wird angezeigt, dass zuerst alle Stände ausgefüllt werden müssen
            echo '<script>document.getElementById("overlay").style.display = "block";</script>';
            echo '<script>document.querySelector("body").style.overflow = "hidden";</script>';
            echo '<script>document.querySelector(".auswertung").style.display = "none";</script>';
        } else {
        }


?>
<script src="js/modal.js"></script>
<?php } ?>

</html>
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
        $chosenAnswer = [];

        // Alles von der Tabelle des Standes aus DB holen
        $selectQuery = "SELECT * FROM `stand13` WHERE user_id = $userId";
        $result = $connection->query($selectQuery);


        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Daten in Array speichern
                $chosenAnswer[] = [
                    'name1' => $row['name1'],
                    'name2' => $row['name2'],
                    'name3' => $row['name3']
                ];
            }
        }
        //Werte aus Array in eigene Variabeln zuweisen
        $name1 = $chosenAnswer[0]['name1'];
        $name2 = $chosenAnswer[0]['name2'];
        $name3 = $chosenAnswer[0]['name3'];



        // Zuordnung der Produktnamen basierend auf den Werten
        $selected_name1 = ($chosenAnswer['name1'] == 1) ? "Maluma" : "Energizier";
        $selected_name2 = ($chosenAnswer['name2'] == 1) ? "Thornax" : "Melodia";
        $selected_name3 = ($chosenAnswer['name3'] == 1) ? "Calmvigor" : "Megavolt";



        //Richtige Antwort zuweisen
        $mostSelectedWord1 = 1;
        $mostSelectedWord2 = 0;
        $mostSelectedWord3 = 1;

        //Counter auf 0 setzen
        $counter = 0;

        /*Für name1*/
        if ($name1 == $mostSelectedWord1) {
            $feedbacktext1 = "Die meisten Personen, wählen auch diesen Namen für das sanft-waschende Waschmittel.";
            $counter++;
        } else {
            $feedbacktext1 = "Die meisten Personen wählen <span class='markierung'>Maluma</span> für das sanft-waschende Waschmittel.";
        }
        /*Für name2*/
        if ($name2 == $mostSelectedWord2) {
            $feedbacktext2 = "Die meisten Personen, wählen auch diesen Namen für gute Kopfhörer, um klassische Musik zu hören.";
            $counter++;
        } else {
            $feedbacktext2 = "Die meisten Personen wählen <span class='markierung'>Melodia</span> für gute Kopfhörer, um klassische Musik zu hören.";
        }
        /*Für name3*/
        if ($name3 == $mostSelectedWord3) {
            $feedbacktext3 = "Die meisten Personen, wählen auch diesen Namen für den Energydrink, welcher mehr Energie liefert.";
            $counter++;
        } else {
            $feedbacktext3 = "Die meisten Personen wählen <span class='markierung'>Megavolt</span> für den Energydrink, welcher mehr Energie liefert.";
        }



        // ------- Punkte vergeben wenn Status = 2 --> 1.34 Punkte pro richtig Ausgewählte
        $selectQuery = "SELECT * FROM `progress` WHERE user_id = $userId";
        $result = $connection->query($selectQuery);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $statusStand13_1 = $row['stand13_1'];
            $statusStand13_2 = $row['stand13_2'];
            $statusStand13_3 = $row['stand13_3'];

            if (($statusStand13_1 == 2) and ($statusStand13_2 == 2) and ($statusStand13_3 == 2)) {
                setlocale(LC_NUMERIC, 'en_US.UTF-8'); // Use dot as the decimal separator

                // Punktzahl für diesen Stand ausrechnen
                $addPoints = $counter * 1.34;

                // Aktuelle Punktzahl aus DB und Punktzahl für diesen Stand zusammenzählen und in DB aktualisieren
                $sql = "UPDATE progress SET punkte = punkte + " . sprintf("%.2f", $addPoints) . " WHERE user_id = $userId";
                $connection->query($sql);
                //Progress-Tabelle auf Status 3 (3=Punktzahl erhalten) updaten
                $sql2 = "UPDATE progress SET stand13_1 = '3', stand13_2 = '3', stand13_3 = '3' WHERE user_id = $userId";
                $connection->query($sql2);
            } else {
                echo "Du hast bereits Punkte für diese Aufgabe bekommen!";
            }
        } else {
            echo "Es wurde kein entsprechender Fortschrittsdatensatz für den Benutzer gefunden. Bitte melde dich bei der Ausstellungsleitung am Eingang.";
        }
    ?>

        <div class='wrapper'>
            <h3>Stand 13</h3>
            <h2>Namenszauber</h2>
            <p>Du hast dich für folgende Namen entschieden:</p>
            <h2>Name 1</h2>
            <div class="buttons nohover">
                <input type="radio" name="name" value="1" id="radio1" disabled>
                <label for="radio1"><?php echo $selected_name1 ?></label>
            </div>
            <div class="feedback">
                <?php
                echo "<p>" . $feedbacktext1 . "</p>";

                if ($duft1 != $mostSelectedDuft1) {
                }
                ?>
            </div>
            <h2>Name 2</h2>
            <div class="buttons">
                <input type="radio" name="name" value="1" id="radio1" disabled>
                <label for="radio1"><?php echo $selected_name2 ?></label>
            </div>
            <div class="feedback">
                <?php
                echo "<p>" . $feedbacktext2 . "</p>";

                if ($duft2 != $mostSelectedDuft2) {
                }
                ?>
            </div>

            <h2>Name 3</h2>
            <div class="buttons">
                <input type="radio" name="name" value="1" id="radio1" disabled>
                <label for="radio1"><?php echo $selected_name3 ?></label>
            </div>
            <div class="feedback">
                <?php
                echo "<p>" . $feedbacktext3 . "</p>";

                if ($duft3 != $mostSelectedDuft3) {
                }
                ?>
            </div>
            <p>Lies jetzt auf der Tafel nach, weshalb die meisten Personen sich für diese Bilder entscheiden</p>
            <form action="" method="post">
                <input type="submit" class="button" name="submit" value="Zur Auswertung &rarr;">
            </form>
        </div><!--ende wrapper-->
        <div class="message">
            <h1>Nutze dein Mobiltelefon, um die Seite aufzurufen.</h1>
        </div>
</body>
<?php

        //Wenn auf submit gedrückt wurde...
        if (isset($_POST['submit'])) {

            //Weiterleitung auf nächste Seite
            header("Location: exhibition_auswertung.php?user_id=$userId");
            exit;
        }

?>

<script src="js/modal.js"></script>
<?php } ?>

</html>
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
        $answersStand7Duft = [];

        // Alles von der Tabelle des Standes aus DB holen
        $selectQuery = "SELECT * FROM `stand7` WHERE user_id = $userId";
        $result = $connection->query($selectQuery);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Daten in Array speichern
                $answersStand7Duft[] = [
                    'duft1' => $row['duft1'],
                    'duft2' => $row['duft2'],
                    'duft3' => $row['duft3']
                ];
            }
        }

        //Pfad des ausgewählten Bildes generieren
        $choosenImgPathScent1 = "img\duft1_" . $answersStand7Duft[0]['duft1'] . ".jpeg";
        $choosenImgPathScent2 = "img\duft2_" . $answersStand7Duft[0]['duft2'] . ".jpeg";
        $choosenImgPathScent3 = "img\duft3_" . $answersStand7Duft[0]['duft3'] . ".jpeg";


        //Richtige Antwort zuweisen
        $mostSelectedDuft1 = 1;
        $mostSelectedDuft2 = 3;
        $mostSelectedDuft3 = 1;

        $counter = 0;

        /*Für duft1*/
        if ($duft1 == $mostSelectedDuft1) {
            $feedbacktext1 = "Die meisten Personen asoziieren den Duft mit dem gleichen Bild, welches du gewählt hast.";
            $counter++;
        } else {
            $feedbacktext1 = "Die meisten Personen asoziieren den Duft mit dem folgenden Bild.";
            $feedbackImg1 = "img\duft1_1.jpeg";
        }
        /*Für duft2*/
        if ($duft2 == $mostSelectedDuft2) {
            $feedbacktext2 = "Die meisten Personen asoziieren den Duft mit dem gleichen Bild, welches du gewählt hast.";
            $counter++;
        } else {
            $feedbacktext2 = "Die meisten Personen asoziieren den Duft mit dem folgenden Bild.";
            $feedbackImg2 = "img\duft2_3.jpeg";
        }
        /*Für duft3*/
        if ($duft3 == $mostSelectedDuft3) {
            $feedbacktext3 = "Die meisten Personen asoziieren den Duft mit dem gleichen Bild, welches du gewählt hast.";
            $counter++;
        } else {
            $feedbacktext3 = "Die meisten Personen asoziieren den Duft mit dem folgenden Bild.";
            $feedbackImg3 = "img\duft3_1.jpeg";
        }



        // ------- Punkte vergeben wenn Status = 2 --> 1.34 Punkte pro richtig Ausgewählte
        $selectQuery = "SELECT * FROM `progress` WHERE user_id = $userId";
        $result = $connection->query($selectQuery);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $statusStand7_1 = $row['stand7_1'];
            $statusStand7_2 = $row['stand7_2'];
            $statusStand7_3 = $row['stand7_3'];

            if (($statusStand7_1 == 2) and ($statusStand7_2 == 2) and ($statusStand7_3 == 2)) {
                //Punkt als Dezimaloperator verwenden
                setlocale(LC_NUMERIC, 'en_US.UTF-8');

                // Punktzahl für diesen Stand ausrechnen
                $addPoints = $counter * 1.34;

                // Aktuelle Punktzahl aus DB und Punktzahl für diesen Stand zusammenzählen und in DB aktualisieren
                $sql = "UPDATE progress SET punkte = punkte + " . sprintf("%.2f", $addPoints) . " WHERE user_id = $userId";
                $connection->query($sql);

                $sql2 = "UPDATE progress SET stand7_1 = '3', stand7_2 = '3', stand7_3 = '3' WHERE user_id = $userId";
                $connection->query($sql2);
            } else {
                echo "Du hast bereits Punkte für diese Aufgabe bekommen!";
            }
        } else {
            echo "Es wurde kein entsprechender Fortschrittsdatensatz für den Benutzer gefunden.";
        }
    ?>

        <div class='wrapper'>
            <h3>Stand 7</h3>
            <h2>Duftassoziationen</h2>
            <p>Du hast dich für folgende Bilder entschieden:</p>
            <h2>Duft 1</h2>
            <img class="scentChoosenImg" src="<?php echo $choosenImgPathScent1 ?>" alt="Frische Image">
            <div class="feedback">
                <?php
                echo "<p>" . $feedbacktext1 . "</p>";

                if ($duft1 != $mostSelectedDuft1) {
                    echo "<img class='scentMostChoosenImg' src=" . $feedbackImg1 . ">";
                }
                ?>
            </div>
            <h2>Duft 2</h2>
            <img class="scentChoosenImg" src="<?php echo $choosenImgPathScent2 ?>" alt="Frische Image">
            <div class="feedback">
                <?php
                echo "<p>" . $feedbacktext2 . "</p>";

                if ($duft2 != $mostSelectedDuft2) {
                    echo "<img class='scentMostChoosenImg' src=" . $feedbackImg2 . ">";
                }
                ?>
            </div>
            <h2>Duft 3</h2>
            <img class="scentChoosenImg" src="<?php echo $choosenImgPathScent3 ?>" alt="Frische Image">
            <div class="feedback">
                <?php
                echo "<p>" . $feedbacktext3 . "</p>";

                if ($duft3 != $mostSelectedDuft3) {
                    echo "<img class='scentMostChoosenImg' src=" . $feedbackImg3 . ">";
                }
                ?>
            </div>
            <p>Lies jetzt auf der Tafel nach, weshalb die meisten Personen sich für diese Bilder entscheiden</p>
            <form action="" method="post">
                <input type="submit" class="button" name="submit" value="Zu Stand 8 &rarr;">
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
            header("Location: exhibition_stand8.php?user_id=$userId");
            exit;
        }

?>

<script src="js/modal.js"></script>
<?php } ?>

</html>
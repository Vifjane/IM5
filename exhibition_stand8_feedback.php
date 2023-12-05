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
        $answersStand8Haptik = [];

        // Alles von der Tabelle des Standes aus DB holen
        $selectQuery = "SELECT * FROM `stand8` WHERE user_id = $userId";
        $result = $connection->query($selectQuery);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Daten in Array speichern
                $answersStand8Haptik[] = [
                    'box1' => $row['box1'],
                    'box2' => $row['box2'],
                    'box3' => $row['box3']
                ];
            }
        }

        //Werte aus Array in eigene Variabeln zuweisen
        $box1 = $answersStand8Haptik[0]['box1'];
        $box2 = $answersStand8Haptik[0]['box2'];
        $box3 = $answersStand8Haptik[0]['box3'];


        //Assoziatives Array für Markennamen
        $brandNames = [
            1 => "Fanta",
            2 => "Evian",
            3 => "Rivella",
            4 => "Coca Cola",
            5 => "Pepita",
            6 => "Sprite",
            7 => "Valser",
            8 => "Knutwiler",
            9 => "Valais",
            10 => "Fuse Tea"
        ];


        //Richtige Antwort zuweisen
        $rightBoxContent1 = 4;
        $rightBoxContent2 = 2;
        $rightBoxContent3 = 6;

        //Counter auf 0 setzen
        $correctCount = 0;
        $feedbacktext = "Du hast folgende Antworten richtig ertastet: <br/><br/>";

        //Wenn Antwort von Box1 richtiger Antwort enspricht...
        if ($box1 == $rightBoxContent1) {
            //...Counter erhöhen
            $correctCount++;
            //Feedbacktext ergänzen
            $feedbacktext .= $brandNames[$box1] . " in Box 1 <br/>";
        }
        //Wenn Antwort von Box2 richtiger Antwort enspricht...
        if ($box2 == $rightBoxContent2) {
            //...Counter erhöhen
            $correctCount++;
            //Feedbacktext ergänzen
            $feedbacktext .= $brandNames[$box2] . " in Box 2 <br/>";
        }
        //Wenn Antwort von Box3 richtiger Antwort enspricht...
        if ($box3 == $rightBoxContent3) {
            //...Counter erhöhen
            $correctCount++;
            //Feedbacktext ergänzen
            $feedbacktext .= $brandNames[$box3] . " in Box 3  <br/></br>";
        }
        //Wenn keine Antwort richtig...
        if ($correctCount == 0) {
            $feedbacktext = "Du hast leider keine Marke richtig ertastet.";
        }
        //Falls nicht alle richtig gewählt wurden...
        elseif ($correctCount < 3) {
            $feedbacktext .= "Die richtigen Antwort wäre folgende: Coca Cola, Fanta, Valais";
        } else {
            $feedbacktext = rtrim($feedbacktext, ', '); // Komma und Abstände entfernen
            $feedbacktext .= "Insgesamt hast du $correctCount richtige Antworten. Gratuliere!";
        }



        // ------- Punkte vergeben wenn Status = 2 --> 1.34 Punkte pro richtig Ausgewählte
        $selectQuery = "SELECT stand8 FROM `progress` WHERE user_id = $userId";
        $result = $connection->query($selectQuery);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $status = $row['stand8'];

            if ($status == 2) {
                //Punkt als Dezimaloperator verwenden
                setlocale(LC_NUMERIC, 'en_US.UTF-8');

                // Punktzahl für diesen Stand ausrechnen
                $addPoints = $correctCount * 1.34;

                // Aktuelle Punktzahl aus DB und Punktzahl für diesen Stand zusammenzählen und in DB aktualisieren
                $sql = "UPDATE progress SET punkte = punkte + " . sprintf("%.2f", $addPoints) . " WHERE user_id = $userId";
                $connection->query($sql);
                //Progress-Tabelle auf Status 3 (3=Punktzahl erhalten) updaten
                $sql2 = "UPDATE progress SET stand8 = '3' WHERE user_id = $userId";
                $connection->query($sql2);
            } else {
                echo "Du hast bereits Punkte für diese Aufgabe bekommen!";
            }
        } else {
            echo "Es wurde kein entsprechender Fortschrittsdatensatz für den Benutzer gefunden.";
        }

    ?>

        <div class='wrapper'>
            <h3>Stand 8</h3>
            <h2>Tastbare Identitäten</h2>
            <p>Du hast dich für folgende Marken entschieden:</p>
            <?php
            echo "<p class='markierung'>" . $brandNames[$box1] . ", " . $brandNames[$box2] . ", " . $brandNames[$box3] . "</p>";
            ?>
            <div class="feedback">
                <?php
                echo "<p>" . $feedbacktext . "</p>";
                ?>
            </div>
            <p>Lies jetzt auf der Tafel nach, weshalb die Haptik in der Werbung eine wichtige Rolle spielt.</p>
            <form action="" method="post">
                <input type="submit" class="button" name="submit" value="Zu Stand 9 &rarr;">
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
            header("Location: exhibition_stand9.php?user_id=$userId");
            exit;
        }

?>

<script src="js/modal.js"></script>
<?php } ?>

</html>
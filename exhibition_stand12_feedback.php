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
        $selectQuery = "SELECT * FROM `stand12` WHERE user_id = $userId";
        $result = $connection->query($selectQuery);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Daten in Array speichern
                $chosenAnswer = [
                    'vino' => $row['vino']

                ];
            }
        }

        //Richtige Antworten speichern
        $trueAnswerImage = 2;


        //Wenn richtige Antwort gewählt wurde, dann...
        if ($chosenAnswer['vino'] == $trueAnswerImage) {

            $feedback = "Du hast dich für das Produkt in der <span class='markierung'>Traverne</span> entschieden!";
            $feedbackH2 = "Die meisten entscheiden sich für dieses Produkt.</br>";

            // ------- Punkte vergeben wenn Status = 2 --> 3 Punkte für richtig ausgewähltes
            $selectQuery = "SELECT stand12 FROM `progress` WHERE user_id = $userId";
            $result = $connection->query($selectQuery);


            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $status = $row['stand12'];

                if ($status == 2) {
                    setlocale(LC_NUMERIC, 'en_US.UTF-8'); // Use dot as the decimal separator

                    // Punktzahl für diesen Stand ausrechnen
                    $addPoints = 3;

                    // Aktuelle Punktzahl aus DB und Punktzahl für diesen Stand zusammenzählen und in DB aktualisieren
                    $sql = "UPDATE progress SET punkte = punkte + " . sprintf("%.2f", $addPoints) . " WHERE user_id = $userId";
                    $connection->query($sql);
                } else {
                    echo "Du hast bereits Punkte für diese Aufgabe bekommen!";
                }
            } else {
                echo "Es wurde kein entsprechender Fortschrittsdatensatz für den Benutzer gefunden. Bitte melde dich bei der Ausstellungsleitung am Eingang.";
            }
        } else {
            $feedback = "Du hast dich für Produkt im <span class='markierung'>Supermarkt</span> entschieden!";
            $feedbackH2 = "Meistens wählt man das Produkt in der Traverne.";
        }
        //Progress-Tabelle auf Status 3 (3=Punktzahl erhalten) updaten
        $sql2 = "UPDATE progress SET stand12 = '3' WHERE user_id = $userId";
        $connection->query($sql2);

    ?>
        <div class='wrapper'>
            <h3>Stand 12</h3>
            <h1><?php echo $feedback; ?></h1>
            <h2><?php echo $feedbackH2; ?> <br />Lies jetzt auf der Tafel nach, weshalb uns dieses Bild mehr anspricht!</h2>
            <form action="" method="post">
                <input type="submit" class="button" name="submit" value="Zum Stand 13 &rarr;">
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
            header("Location: exhibition_stand13.php?user_id=$userId");
            exit;
        }

?>
<script src="js/modal.js"></script>
<?php } ?>

</html>
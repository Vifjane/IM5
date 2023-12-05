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



        // Alle Daten der Stand-Tabelle holen, bei welcher die UserID der aus der URL entspricht
        $selectQuery = "SELECT * FROM `stand1` WHERE user_id = $userId";
        $result = $connection->query($selectQuery);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Daten von Stand-Tabelle im Array speichern
                $chosenAnswer = [
                    'design_id' => $row['design_id']
                ];
            }
        }

        //Richtige Antwort definieren (hier: 2)
        $trueAnswer = 2;

        //Wenn die gewählte Antwort der richtigen Antwort entspricht...
        if ($chosenAnswer['design_id'] == $trueAnswer) {

            //..definieren des Feedbacktextes
            $feedbackText = "Die meisten entscheiden sich für dieses Design, lies jetzt auf der Tafel nach, warum das so ist!";

            // ------- Punkte vergeben --> 2 Punkte für richtig ausgewähltes
            //Status von progress-Tabelle des aktuellen Standes abrufen
            $selectQuery = "SELECT stand1 FROM `progress` WHERE user_id = $userId";
            $result = $connection->query($selectQuery);

            //Wenn das Array nicht leer ist...
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                //Daten im Array $status speichern
                $status = $row['stand1'];

                //Wenn der Status 2 (2=Antwort in DB gespeichert) ist...
                if ($status == 2) {
                    // Der Punkt als Dezimaltrennzeichen definieren
                    setlocale(LC_NUMERIC, 'en_US.UTF-8');

                    // Punktzahl für diesen Stand ausrechnen (hier: 2)
                    $addPoints = 2;

                    // Aktuelle Punktzahl aus DB und Punktzahl für diesen Stand zusammenzählen und in DB aktualisieren
                    $sql = "UPDATE progress SET punkte = punkte + " . sprintf("%.2f", $addPoints) . " WHERE user_id = $userId";
                    $connection->query($sql);
                }
                //Falls der Status 3 entspricht
                else {
                    echo "Du hast bereits Punkte für diese Aufgabe bekommen!";
                }
            }
            //Falls das Array leer ist
            else {
                echo "Es wurde kein entsprechender Fortschrittsdatensatz für den Benutzer gefunden.";
            }
        }
        //Wenn die Antwort nicht der richtigen Antwort entspricht
        else {
            //..definieren des Feedbacktextes
            $feedbackText = "Die meisten entscheiden sich für <span class='markierung'>Design $trueAnswer</span>, lies jetzt auf der Tafel nach, warum das so ist!";
        }

        //Der Status des Standes auf 3 setzen (3=Punkte erhalten)
        $sql2 = "UPDATE progress SET stand1 = '3' WHERE user_id = $userId";
        $connection->query($sql2);


    ?>
        <div class='wrapper'>
            <h3>Stand 1</h3>
            <h2>Produktdesign</h2>
            <p>Du hast dich für <span class="markierung">Design <?php echo $chosenAnswer['design_id']; ?></span> entschieden!</p>
            <p><?php echo $feedbackText ?></p>
            <form action="" method="post">
                <input type="submit" class="button" name="submit" value="Zum Stand 2 &rarr;">
            </form>
        </div><!--ende wrapper-->
        <div class="message">
            <h1>Nutze dein Mobiltelefon, um die Seite aufzurufen.</h1>
        </div>
</body>
<?php
        //Wenn auf Submit gedrückt wurde...
        if (isset($_POST['submit'])) {
            //Weiterleitung auf nächste Seite
            header("Location: exhibition_stand2.php?user_id=$userId");
            exit;
        }
?>
<script src="js/modal.js"></script>
<?php } ?>

</html>
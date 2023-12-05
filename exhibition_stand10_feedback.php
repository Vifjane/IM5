<html>

<head>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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


        //Richtige Antworten speichern
        $trueAnswerSpeaker1 = 3;

        // Alles von der Tabelle des Standes aus DB holen
        $selectQuery = "SELECT * FROM `stand10` WHERE user_id='$userId'";
        $result = $connection->query($selectQuery);

        if ($result->num_rows > 0) {
            // Daten in Array speichern
            $row = $result->fetch_assoc();
            $answerSpeaker1 = $row['speaker1'];
        }

        //Wenn die gegebene Antwort richtig ist...
        if ($answerSpeaker1 == $trueAnswerSpeaker1) {
            $feedback = "Du hast die Person an der Stimme richtig erkannt!";


            // ------- Punkte vergeben wenn Status = 2 --> 2 Punkte für richtig ausgewähltes
            $selectQuery = "SELECT stand10 FROM `progress` WHERE user_id = $userId";
            $result = $connection->query($selectQuery);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $status = $row['stand10'];

                if ($status == 2) {
                    //Punkt als Dezimaloperator verwenden
                    setlocale(LC_NUMERIC, 'en_US.UTF-8');

                    // Punktzahl für diesen Stand ausrechnen
                    $addPoints = 2;

                    // Aktuelle Punktzahl aus DB und Punktzahl für diesen Stand zusammenzählen und in DB aktualisieren
                    $sql = "UPDATE progress SET punkte = punkte + " . sprintf("%.2f", $addPoints) . " WHERE user_id = $userId";
                    $connection->query($sql);
                } else {
                    echo "Du hast bereits Punkte für diese Aufgabe bekommen!";
                }
            } else {
                echo "Es wurde kein entsprechender Fortschrittsdatensatz für den Benutzer gefunden.";
            }
        } else {
            $feedback = "Leider nicht ganz richtig...";
        }
        //Progress-Tabelle auf Status 3 (3=Punktzahl erhalten) updaten
        $sql2 = "UPDATE progress SET stand10 = '3' WHERE user_id = $userId";
        $connection->query($sql2);
    ?>
        <div class='wrapper'>
            <h3>Stand 10</h3>
            <h1>Wer spricht?</h1>
            <h2>Sieh dir jetzt das passende Video zu der Stimme an!</h2>
            <!-- Video player -->
            <video controls>
                <source src="your-video-file.mp4" type="video/mp4">
            </video>
            <div class="feedback">
                <span class="info-icon" title="Additional information">
                    <i class="fa fa-info-circle" style="color:white"></i>
                </span>
                <span class="right-wrong-icon" title="Correction">
                    <i class="fa fa-circle-check" style="color:green"></i>
                </span>
                <p><?php echo $feedback; ?></p>
            </div><!--ende feedback-->
            <form action="" method="post">
                <input type="submit" class="button" name="submit" value="Weiter &rarr;">
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
            header("Location: exhibition_stand11.php?user_id=$userId");
            exit;
        }


?>
<script src="js/modal.js"></script>
<?php } ?>

</html>
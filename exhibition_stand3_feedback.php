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


        //Richtige Antworten speichern
        $trueAnswersText = "Ford, Heineken, Land Rover, Sony Ericsson, Omega";

        // Array initialisieren, um anschliessend Daten darin zu speichern
        $existingStand3Answer = [];

        // Alles von der Tabelle des Standes aus DB holen
        $selectQuery = "SELECT * FROM `stand3` WHERE user_id = $userId";
        $result = $connection->query($selectQuery);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Daten in Array speichern
                $existingStand3Answer = [
                    'marke1' => $row['marke1'],
                    'marke2' => $row['marke2'],
                    'marke3' => $row['marke3'],
                    'marke4' => $row['marke4'],
                    'marke5' => $row['marke5'],
                    'marke6' => $row['marke6'],
                    'marke7' => $row['marke7'],
                    'marke8' => $row['marke8']

                ];
            }
        }


        // Array initialisieren, um richtige Antworten darin zu speichern
        $trueAnswers = [
            'marke1' => 1,
            'marke2' => 1,
            'marke3' => 0,
            'marke4' => 0,
            'marke5' => 1,
            'marke6' => 1,
            'marke7' => 0,
            'marke8' => 1,
        ];

        // Array initialisieren, um die Markennamen darin zu speichern
        $brandNames = [
            'marke1' => 'Ford',
            'marke2' => 'Heineken',
            'marke3' => 'McDonalds',
            'marke4' => 'Black Berry',
            'marke5' => 'Land Rover',
            'marke6' => 'Sony Ericsson',
            'marke7' => 'Rolex',
            'marke8' => 'Omega'
        ];


        // Array initialisieren, um anschliessend Daten darin zu speichern
        $selectedBrands = [];

        // Check selected brands against correct answers
        foreach ($existingStand3Answer as $key => $value) {
            if ($value == 1 && isset($brandNames[$key])) {
                $selectedBrands[] = $brandNames[$key];
            }
        }


        //richtige Marken zählen & Feedback Ausgeben
        $counter = 0;
        $falsch_ausgewaehlt = [];
        $nicht_gesehen = [];
        $feedback_solution = '';

        foreach ($existingStand3Answer as $key => $value) {
            //überprüfung ob der aktuelle schlüssel im Array $trueanswers existiert und ob dieser gleich im $existingstand3answer ist

            if (isset($trueAnswers[$key]) && $trueAnswers[$key] == $value) {
                //Wenn der aktuelle Schlüssel im Array existiert UND $existingStand3Answer mit dem Wert $trueAnswers übereinstimmt
                $counter++;
            } else {
                //Wenn der wert von $existingStand3Answer = 0, dann wird Name von Schlüssel ($brandanswers[$key]) in Array $nicht_gesehen verschoben
                if ($value == 0) {
                    array_push($nicht_gesehen, $brandNames[$key]);
                } else {
                    //Wenn der wert von $existingStand3Answer nicht 0, dann wird Name von Schlüssel ($brandanswers[$key]) in Array $falsch _usgewählt verschoben
                    array_push($falsch_ausgewaehlt, $brandNames[$key]);
                }
            }
        }



        //Zählt die falsch ausgewählten Marken
        $falsch_ausgewaehlt_count = count($falsch_ausgewaehlt);
        if ($falsch_ausgewaehlt_count > 0) {
            if ($falsch_ausgewaehlt_count > 1) {
                $feedback_solution .= "Die Marken ";
            } else {
                $feedback_solution .= "Die Marke ";
            }
            $i = 0;
            foreach ($falsch_ausgewaehlt as $brand) {
                if ($i > 0) {
                    $feedback_solution .= ', ';
                }
                $feedback_solution .= "<span class='markierung'>" . $brand . "</span>";
                $i++;
            }
            if ($falsch_ausgewaehlt_count > 1) {
                $feedback_solution .= " sind nicht im Film vorgekommen.</br></br>";
            } else {
                $feedback_solution .= " ist nicht im Film vorgekommen.</br></br>";
            }
        }

        //Zählt die Marken, welche ausgewählt hätten werden müssen
        $nicht_gesehen_count = count($nicht_gesehen);
        //Falls es falsch ausgewählte gibt...
        if ($nicht_gesehen_count > 0) {
            //Überprüfung ob mehr als 1 falsch ausgewählte Marke
            if ($falsch_ausgewaehlt_count > 1) {
                //Feedback in mehrzahl
                $feedback_solution .= "Du hast die Marken ";
            }
            //Falls nur 1 falsch gewählt...
            else {
                //Feedback in einzahl
                $feedback_solution .= "Du hast die Marke ";
            }
            $i = 0;

            //Falsch ausgewählte Marke Brandnamen zugewiesen & zu Feedback hinzugefügt
            foreach ($nicht_gesehen as $brand) {
                if ($i > 0) {
                    $feedback_solution .= ', ';
                }
                $feedback_solution .= "<span class='markierung'>" . $brand . "</span>";
                $i++;
            }
            $feedback_solution .= " nicht gesehen.";
        }




        // ------- Punkte vergeben wenn Status = 2 --> 0.8 Punkte pro richtig Ausgewählte
        $selectQuery = "SELECT stand3 FROM `progress` WHERE user_id = $userId";
        $result = $connection->query($selectQuery);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $status = $row['stand3'];

            if ($status == 2) {

                //Punkt als Dezimaloperator verwenden
                setlocale(LC_NUMERIC, 'en_US.UTF-8');

                // Punktzahl für diesen Stand ausrechnen
                $addPoints = $counter * 0.8;

                // Aktuelle Punktzahl aus DB und Punktzahl für diesen Stand zusammenzählen und in DB aktualisieren
                $sql = "UPDATE progress SET punkte = punkte + " . sprintf("%.2f", $addPoints) . " WHERE user_id = $userId";
                $connection->query($sql);
                //Progress-Tabelle auf Status 3 (3=Punktzahl erhalten) updaten
                $sql2 = "UPDATE progress SET stand3 = '3' WHERE user_id = $userId";
                $connection->query($sql2);
            } else {
                echo "Du hast bereits Punkte für diese Aufgabe bekommen!";
            }
        } else {
            echo "Es wurde kein entsprechender Fortschrittsdatensatz für den Benutzer gefunden.";
        }
    ?>


        <div class='wrapper'>
            <h3>Stand 3</h3>
            <h1>Product Placement</h1>
            <p>Du hast <span class="markierung"><?php echo $counter; ?></span> von <span class="markierung">8</span> Marken richtig gewählt! </p>
            <p><?php echo $feedback_solution ?></p>
            <form action="" method="post">
                <input type="submit" class="button" name="submit" value="Zu Stand 4 &rarr;">
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
            header("Location: exhibition_stand4.php?user_id=$userId");
            exit;
        }

?>
<script src="js/modal.js"></script>
<?php } ?>

</html>
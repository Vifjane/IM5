<html>

<head>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
    <?php
    //DB-Verbindung einbinden
    require_once('db_connection.php');
    ?>
    <div class='wrapper'>
        <h3>Login</h3>
        <h1>Logge dich erneut ein!</h1>
        <form action="" method="post">
            <input class="textfield" size="40" type="text" name="name" id="name" placeholder="Gib hier dein gewählter Name ein" required>
            <br><br>
            <input type="submit" class="button" name="submit" value="Weiter">
        </form>

        <?php

        // Array initialisieren, um anschliessend Daten darin zu speichern
        $existingNamesAndCharacters = [];

        // Alles von der Tabelle registrieren aus DB holen
        $selectQuery = "SELECT * FROM `registrieren`";
        $result = $connection->query($selectQuery);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Daten in Array speichern
                $existingNamesAndCharacters[] = [
                    'user_id' => $row['user_id'],
                    'name' => $row['name']
                ];
            }
        }


        //Wenn auf submit gedrückt wurde...
        if (isset($_POST['submit'])) {
            // Die Werte per "Post" aus dem Formular holen
            $name = $_POST["name"];



            // Überprüfe, ob der Name existiert
            $nameExists = false;

            //Schleife (durchläuft jedes Element von Array)
            foreach ($existingNamesAndCharacters as $item) {
                //wenn name gleich name...
                if ($item['name'] == $name) {
                    $nameExists = true;
                    break;
                }
            }

            //wenn Name existiert..
            if ($nameExists) {
                //UserID aus Array auslesen
                $user_id = $existingNamesAndCharacters[0]['user_id'];
                //Weiterleitung auf ÜbersichtsSeite
                header("Location: exhibition_progress.php?user_id=$user_id&login=1");
                exit;
            } else {
                //falls kein solcher Name existiert
                echo "<p class='infobox'>Es existiert kein Benutzer mit deinem Namen!</p>";
            }
        }

        ?>
    </div><!--ende wrapper-->
    <div class="message">
        <h1>Nutze dein Mobiltelefon, um die Seite aufzurufen.</h1>
    </div>
</body>
<script src="js/script.js"></script>

</html>
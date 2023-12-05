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
    echo "<div class='wrapper'>";
    ?>
    <h3>Willkommen bei "<span class="markierung">Ad</span>Vantage?" </h3>
    <h1 class="title">Wähle einen Charakter und gib ihm einen Namen</h1>
    <form action="" method="post" onsubmit="return validateForm()">
        <div class="character-images">
            <input type="radio" id="frau1" name="character" value="frau_1">
            <label for="frau1"><img src="img\frau_1.png" alt="Broccoli Image"></label>

            <input type="radio" id="mann1" name="character" value="mann_1">
            <label for="mann1"><img src="img\mann_1.png" alt="Strawberry Image"></label>

            <input type="radio" id="frau2" name="character" value="frau_2">
            <label for="frau2"><img src="img\frau_2.png" alt="Strawberry Image"></label>

            <input type="radio" id="mann2" name="character" value="mann_2">
            <label for="mann2"><img src="img\mann_2.png" alt="Strawberry Image"></label>

            <input type="radio" id="frau3" name="character" value="frau_3">
            <label for="frau3"><img src="img\frau_3.png" alt="Strawberry Image"></label>

            <input type="radio" id="mann3" name="character" value="mann_3">
            <label for="mann3"><img src="img\mann_3.png" alt="Strawberry Image"></label>
        </div>
        <!--<label for="name">Name:</label>-->
        <input class="textfield" size="40" type="text" name="name" id="name" placeholder="Gib hier deinem Charakter einen Namen">
        <br><br>
        <input type="submit" class="button" name="submit" value="Weiter">
    </form>
    <script src="js/modal.js"></script>

    <?php

    // Array initialisieren, um anschliessend Daten darin zu speichern
    $existingNamesAndCharacters = [];

    // Alle Daten der registrieren-Tabelle holen
    $selectQuery = "SELECT * FROM `registrieren`";
    $result = $connection->query($selectQuery);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Daten von registrieren-Tabelle im Array speichern
            $existingNamesAndCharacters[] = [
                'user_id' => $row['user_id'],
                'name' => $row['name'],
                'character' => $row['character']
            ];
        }
    }

    // Die letzte ID aus dem Array holen
    end($existingNamesAndCharacters);
    $lastID = end($existingNamesAndCharacters)['user_id'];


    $currentUserID = $lastID + 1;


    //Wenn auf submit gedrückt wurde...
    if (isset($_POST['submit'])) {

        // Die Werte per "Post" aus dem Formular holen
        $name = $_POST["name"];
        $character = $_POST["character"];


        // Überprüfe, ob der Name bereits in der Datenbank existiert
        $nameExists = false;
        foreach ($existingNamesAndCharacters as $entry) {
            if ($entry['name'] === $name) {
                $nameExists = true;
                break;
            }
        }

        //wenn der Name bereits existiert
        if ($nameExists) {
            $errorMessage = "Fehler: Leider existiert der Name '$name' schon. Wähle einen anderen Namen!";
            echo '<div id="modal_done" class="modal_done">
                <div class="modal-content">
                    <span id="close-modal" class="close-modal"  onclick="closeModal()">&times;</span>
                    <p>' . $errorMessage . '</p>
                </div>
            </div>';
            echo "<script>showModal();</script>";
        } else {
            // Antwort wird in die Tabelle "registrieren" eingetragen
            $sql = "INSERT INTO `registrieren` (`name`, `character`) VALUES ('$name', '$character')";

            //Neuer Eintrag in der Progress-Tabelle mit User_id
            $sql2 = "INSERT INTO `progress` SET user_id = '$currentUserID'";
            $connection->query($sql2);

            if ($connection->query($sql) === TRUE) {
                echo "Data inserted successfully" . $sql;
                //Weiterleitung auf nächste Seite
                header("Location: exhibition_welcome.php?user_id=$currentUserID&name=$name");
                exit;
            } else {
                echo "Error: " . $sql . "<br>" . $connection->error;
            }
        }
    }


    ?>
    <!-- Modal -->
    <div id="modal_done" class="modal_done">
        <div class="modal-content">
            <span id="close-modal" class="close-modal" onclick="closeModal()">&times;</span>
            <p>Fülle alle Felder aus!</p>
        </div>
    </div>

    </div><!--ende wrapper-->
    <div id="overlay" class="overlay">
        <div class="overlay-content">
            <p class="alreadyanswered">Diese Frage wurde von dir schon beantwortet.</p>
            <a class="redirect" href="exhibition_progress.php?user_id=<?php echo $userId; ?>name=<?php echo $name; ?>" class="">Zur Übersicht &rarr;</a>
        </div>
    </div>
    <div class="message">
        <h1>Nutze dein Mobiltelefon, um die Seite aufzurufen.</h1>
    </div>
</body>
<script>
    //Funktion, um zu überprüfen, ob eine Antwort gegeben wurde (wird via validateForm bei klick auf Submit ausgeführt)
    function validateForm() {

        var character = document.querySelector('input[name="character"]:checked');
        var name = document.getElementById('name').value;

        if (!character || name.trim() === '') {
            // Zeige das Modal bei unvollständigen Feldern
            showModal();
            return false;
        }

        return true;
    }
</script>

</html>
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
    <div class='wrapper'>
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

            //Falls ein ?login= in URL ist, dann Meldung anzeigen (Dann ist der User über die Loginseite wieder in seinem Profil)
            if (isset($_GET['login'])) {
                echo "<p class='infobox'>Du bist erfolgreich wieder eingeloggt!</p>";
            }

        ?>

            <h3>Übersicht</h3>
            <h1>Fortschritt</h1>
            <p>Hier kannst du zwischen den verschiedenen Ständen hin und her springen und siehst, welche du schon gelöst hast.</p>
            <form action="" method="post">
                <div class="buttons">
                    <a id="stand1" class="progress" href="exhibition_stand1.php?user_id=<?php echo $userId ?>">1</a>
                    <a id="stand3" class="progress" href="exhibition_stand3.php?user_id=<?php echo $userId ?>">3</a>
                    <a id="stand7_1" class="progress" href="exhibition_stand7.php?user_id=<?php echo $userId ?>">7.1</a>
                    <a id="stand7_2" class="progress" href="exhibition_stand7.2.php?user_id=<?php echo $userId ?>">7.2</a>
                    <a id="stand7_3" class="progress" href="exhibition_stand7.3.php?user_id=<?php echo $userId ?>">7.3</a>
                    <a id="stand8" class="progress" href="exhibition_stand8.php?user_id=<?php echo $userId ?>">8</a>
                    <a id="stand9" class="progress" href="exhibition_stand9.php?user_id=<?php echo $userId ?>">9</a>
                    <a id="stand10" class="progress" href="exhibition_stand10.php?user_id=<?php echo $userId ?>">10</a>
                    <a id="stand11" class="progress" href="exhibition_stand11.php?user_id=<?php echo $userId ?>">11</a>
                    <a id="stand12" class="progress" href="exhibition_stand12.php?user_id=<?php echo $userId ?>">12</a>
                    <a id="stand13_1" class="progress" href="exhibition_stand13.php?user_id=<?php echo $userId ?>">13.1</a>
                    <a id="stand13_2" class="progress" href="exhibition_stand13.2.php?user_id=<?php echo $userId ?>">13.2</a>
                    <a id="stand13_3" class="progress" href="exhibition_stand13.3.php?user_id=<?php echo $userId ?>">13.3</a>
                </div><!--ende buttons-->
                <a id="auswertung" class="progress auswertung_button" href="exhibition_auswertung.php?user_id=<?php echo $userId ?>">Zur Auswertung &rarr;</a>

                <div id="legend">
                    <div class="row">
                        <div class="legende open"></div>Noch nicht ausgefüllt
                    </div>
                    <div class="row">
                        <div class="legende teilweise"></div>Teilweise gelöst
                    </div>
                    <div class="row">
                        <div class="legende done"></div>Bereits gelöst
                    </div>
                </div>
            </form>

            <!-- Modal -->
            <div id="modal_done" class="modal_done">
                <div class="modal-content">
                    <span id="close-modal" class="close-modal">&times;</span>
                    <p>Dieser Stand wurde bereits gelöst!</p>
                </div>
            </div>
    </div><!--ende wrapper-->
    <div id="overlay" class="overlay">
        <div class="overlay-content">
            <p class="alreadyanswered">Diese Frage wurde von dir schon beantwortet.</p>
            <a class="redirect" href="exhibition_progress.php?user_id=<?php echo $userId; ?>" class="">Zur Übersicht &rarr;</a>
        </div>
    </div>
    <div class="message">
        <h1>Nutze dein Mobiltelefon, um die Seite aufzurufen.</h1>
    </div>
</body>

<?php



            // Array initialisieren, um anschliessend Daten darin zu speichern
            $progress = [];

            // Alle Daten der Progress-Tabelle holen
            $selectQuery = "SELECT * FROM `progress` WHERE user_id='$userId'";
            $result = $connection->query($selectQuery);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Daten von Progress-Tabelle im Array speichern
                    $progress[] = [
                        'user_id' => $row['user_id'],
                        'stand1' => $row['stand1'],
                        'stand3' => $row['stand3'],
                        'stand7.1' => $row['stand7.1'],
                        'stand7.2' => $row['stand7.2'],
                        'stand7.3' => $row['stand7.3'],
                        'stand8.1' => $row['stand8.1'],
                        'stand10' => $row['stand10.1'],
                        'stand11' => $row['stand11'],
                        'stand12' => $row['stand12'],
                        'stand13.1' => $row['stand13.1'],
                        'stand13.2' => $row['stand13.2'],
                        'stand13.3' => $row['stand13.3']
                    ];
                    // Arrays initialisieren, um anschliessend Daten darin zu speichern
                    $done = [];
                    $teilweise = [];
                    $open = [];

                    //Schleife (durchläuft jedes Element)
                    foreach ($row as $key => $value) {
                        //Wenn Wert gleich 2 oder 3...
                        if ($value == 2 or $value == 3) {
                            //Schlüssel wird array done hinzugefügt
                            $done[] = $key;
                        }
                        //wenn Wert gleich 1
                        elseif ($value == 1) {
                            //Schlüssel wird array teilweise hinzugefügt
                            $teilweise[] = $key;
                        } else {
                            //Schlüssel wird array open hinzugefügt
                            $open[] = $key;
                        }
                    }
                }
            }

            //Schleife (durchläuft jedes Element in array done)
            foreach ($done as $id) {
                //Fügt Klasse 'done' hinzu
                echo "<script>document.getElementById('$id').classList.add('done');</script>";
            }
            //Schleife (durchläuft jedes Element in array teilweise)
            foreach ($teilweise as $id) {
                //Fügt Klasse 'teilweise' hinzu
                echo "<script>document.getElementById('$id').classList.add('teilweise');</script>";
            }
            //Schleife (durchläuft jedes Element in array open)
            foreach ($open as $id) {
                //Fügt Klasse 'open' hinzu
                echo "<script>document.getElementById('$id').classList.add('open');</script>";
            }


?>
<script>
    // Funktion, welche bei Klick auf Button ausgeführt wird
    function handleLinkClick(linkId) {
        var link = document.getElementById(linkId);
        var modal = document.getElementById('modal_done');
        var closeModal = document.getElementById('close-modal');
        //wenn der Link die Klasse "done" hat
        if (link.classList.contains('done')) {
            //modal anzeigen
            modal.style.display = 'flex';
        } else {
            //wenn der Link nicht die Klasse "done" hat, navigiere zum link
            window.location.href = link.href;
        }
        //Falls Modal sichtbar, und auf das X geklickt wird, wird das Modal geschlossen 
        closeModal.addEventListener('click', function() {
            modal.style.display = 'none';
        });

        //Falls Modal sichtbar, und ausserhalb des Modals geklickt wird, wird das Modal geschlossen 
        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });

        // Für Touch-Events auf dem IOS-System: falls Modal sichtbar, und ausserhalb des Modals / auf das X geklickt wird, wird das Modal geschlossen 
        window.addEventListener('touchstart', function(event) {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });
    }

    //Event-Listener überall hinzufügen, wo klasse Progress ist
    var links = document.querySelectorAll('.progress');
    links.forEach(function(link) {
        link.addEventListener('click', function(event) {
            //verhindert Standartverhalten
            event.preventDefault();
            handleLinkClick(link.id);
        });
    });
</script>
<script src="js/modal.js"></script>
<?php } ?>

</html>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="js/checkscreensize_userid.js"></script>
</head>

<body>
    <?php
    require_once('db_connection.php');

    //Wenn user_id nicht in URL
    if (empty($_GET['user_id'])) {
        //Falls keine User_id gefunden, unblockierbares Alert anzeigen
        echo "<script>displayAlertErrorUserIdMissing();</script>";
    } else {
        //Per GET die UserID auslesen
        $userId = $_GET['user_id'];
        $name = $_GET['name'];

    ?>
        <div class='wrapper'>
            <h3>Einführung</h3>
            <h2>Willkommen <?php echo $name ?>!</h2>
            <p>Herzlich Willkommen in der Ausstellung "AdVantage?"</p>
            <div class="infobox">
                <i class="fa-solid fa-mobile-screen-button"></i>
                <p>Bei jeder Station mit diesem Icon kannst du via dein Smartphone an dem Experiment teilnehmen.</p>
            </div>
            <p>Wir wünschen dir viel Spass in der Welt der Werbung!</p>
            <form action="" method="post">
                <input type="submit" class="button" name="submit" value="Zum Stand 1 &rarr;">

            </form>
            <div class="fortschritt">
                <a href="exhibition_progress.php?user_id=<?php echo $userId; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 100 125" enable-background="new 0 0 100 100" xml:space="preserve">
                        <rect x="5" y="5" width="39.1" height="38.2" />
                        <rect x="5" y="56.3" width="39.1" height="38.7" />
                        <rect x="55.9" y="5" width="39.1" height="38.2" />
                        <rect x="55.9" y="56.3" width="39.1" height="38.7" />
                    </svg>
                </a>
            </div>
        </div><!--ende wrapper-->
        <!--Falls der Bildschirm grösser als 600px, wird diese Message angezeigt, per default unsichtbar-->
        <div class="message">
            <h1>Nutze dein Mobiltelefon, um die Seite aufzurufen.</h1>
        </div>

</body>
<?php


        if (isset($_POST['submit'])) {

            //Weiterleitung auf nächste Seite
            header("Location: exhibition_stand1.php?user_id=$userId");
            exit;
        }

?>
<script src="js/modal.js"></script>
<?php } ?>

</html>
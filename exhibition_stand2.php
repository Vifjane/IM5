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

    ?>
        <div class='wrapper'>
            <h3>Stand 2</h3>
            <h2>Zeit ist Geld?</h2>
            <p>Diese Aufgabe ist am Computer zu lösen!</p>
            <div class="infobox">
                <svg xmlns:x="http://ns.adobe.com/Extensibility/1.0/" xmlns:i="http://ns.adobe.com/AdobeIllustrator/10.0/" xmlns:graph="http://ns.adobe.com/Graphs/1.0/" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 100 125" style="enable-background:new 0 0 100 100;" xml:space="preserve">
                    <switch>
                        <foreignObject requiredExtensions="http://ns.adobe.com/AdobeIllustrator/10.0/" x="0" y="0" width="1" height="1" />
                        <g i:extraneous="self">
                            <g>
                                <path d="M37.9,29.9h19l8.1-8.1c-0.6-0.4-1.3-0.6-2-0.6H37.1c-2.1,0-3.8,1.7-3.8,3.8v28.5l4.6-4.6V29.9z" />
                                <path d="M50,2.5C23.8,2.5,2.5,23.8,2.5,50S23.8,97.5,50,97.5c26.2,0,47.5-21.3,47.5-47.5S76.2,2.5,50,2.5z M50,9.8     c9.8,0,18.8,3.5,25.8,9.4L19.1,75.8c-5.8-7-9.4-16-9.4-25.8C9.8,27.8,27.8,9.8,50,9.8z M39.4,65.8L62.1,43v22.8H39.4z M53,72.3     c0,1.6-1.3,3-3,3c-1.6,0-3-1.3-3-3c0-1.6,1.3-3,3-3C51.6,69.3,53,70.6,53,72.3z M50,90.2c-9.8,0-18.8-3.5-25.8-9.4l9-9v3     c0,2.1,1.7,3.8,3.8,3.8h25.8c2.1,0,3.8-1.7,3.8-3.8V38.4l14.2-14.2c5.8,7,9.4,16,9.4,25.8C90.2,72.2,72.2,90.2,50,90.2z" />
                            </g>
                        </g>
                    </switch>
                </svg>
                <p>Setze dich an den Computer und mache den Entscheidungstest!</p>
            </div>
            <form action="" method="post">
                <input type="submit" class="button" name="submit" value="Zum Stand 3 &rarr;">

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
        <div class="message">
            <h1>Nutze dein Mobiltelefon, um die Seite aufzurufen.</h1>
        </div>
</body>
<?php

        //Wenn auf Submit gedrückt wurde...
        if (isset($_POST['submit'])) {
            //Weiterleitung auf nächste Seite
            header("Location: exhibition_stand3.php?user_id=$userId");
            exit; // Make sure to exit after the redirect
        }

?>
<script src="js/modal.js"></script>
<?php } ?>

</html>
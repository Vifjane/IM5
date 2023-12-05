# IM5

<h2>Lehrprojekt</h2>
In meinem Lehrpojekt mache ich eine interaktive und innovative Ausstellung namens "AdVantage?" zur Sensibilisierung für Beeinflussung in der Werbung. Vor Ort gibt es 12 Experimentierstationen, bei welchen die Personen die Beeinflussung mittels Experimente selbst erfahren können. Die Webanwendung ermöglicht es den Besuchenden, ihre Antworten auf ihren Handys abzugeben und erhalten am Ende der Ausstellung eine persönliche Auswertung über ihre Werbekenntnisse.

<h2>Hinweise für IM-Dozenten</h2>
Die Startseite heisst bewusst nicht index.php, da ich den Geldgebern jeweils diese Adresse für das Lesen meines Konzepts angegeben habe. Das Projekt wurde gezielt für Mobile-Devices mit einer maximalen Bildschirmbreite von 600 Pixeln konzipiert. Falls ihr die Website dennoch auf dem Laptop betrachten möchtet, aktiviert bitte das Responsive-Layout über die DevTools im Browser.

<h2>Technologien</h2>
Das Webprojekt wurde mit PHP, JavaScript, HTML und CSS entwickelt, wobei eine MySQL-Datenbank für die Speicherung und den Abruf von Daten zum Einsatz kam.

<h2>Datenbankstruktur</h2>
Folgende Tabellen habe ich für mein Projekt verwendet:</br>
progress: Speichert den Fortschritt eines Besuchenden.</br>
registrieren: Hier wird per auto increment die UserID (Primary Key) generiert und der Name sowie der Charakter des Besuchenden gespeichert.</br>
stand 1-13: Speichert die Antworten der Besuchenden für die entsprechenden Stände.</br>
Alle Tabellen verwenden die UserID als (Fremd-)Schlüssel.

<h2>Ablauf des Programms</h2>
Die Besuchenden können einen Charakter wählen, einen Namen eingeben und sich registrieren. Anschliessend sehen sie die Aufgabe des Standes. Diese können sie beantworten und erhalten auf der nächsten Seite eine Auswertung zur Aufgabe. Sie werden aufgefordert, noch vertiefende Informationen von der Tafel in der Ausstellung vor Ort zu entnehmen. Bei allen Aufgaben haben die Besuchenden die Möglichkeit je nach Besucherandrang zwischen den einzelnen Ständen hin und her zu springen, indem sie via Übersichtsseite einen anderen Stand auswählen. Bei jeder Antwort erhält der Besucher Punkte. Am Schluss wird die Punktzahl ausgewertet und der Besuchende erhält ein Feedback wie gut er sich bereits in der Welt der Werbung auskennt.

<h2>Datenschutz</h2>
Bei Stand 9 besteht die Möglichkeit, eine digitale Postkarte per E-Mail zu versenden. Weder das aufgenommene Bild noch die E-Mail-Adresse werden in der Datenbank gespeichert, um Datenschutzrichtlinien zu entsprechen.

<h2>Erarbeitung</h2>
Bevor ich zu programmieren begann, überlegte ich mir die Datenstruktur und erstellte in PHPmyAdmin die Tabellenstruktur. Anschliessend setzte ich mich ans Programmieren. Am Anfang gings etwas länger, bis ich wieder die Denkweise vom Coden angenommen hatte, aber anschliessend kam ich sehr gut in den Flow. Beim Schreiben von HTML, CSS und PHP benötigte ich keine Hilfe. Beim JavaScript holte ich meine alten Skripte hervor, um einige Funktionen zu übernehmen. Ebenfalls machte ich Gebrauch von ChatGPT.

<h2>Challenges</h2>
Es war eine Herausforderung, eine Oberfläche zu bauen, mit welcher ein Foto aufgenommen und anschliessend per Mail versendet werden kann. Ich habe schon diverse Skripts geschrieben, welche Mails über PHP versendet haben, jedoch noch nie mit Anhang. Das Skript musste immer wieder auf verschiedenen Devices getestet werden. Nun bin ich stolz darauf, dass ich diese Funktion implementieren konnte.</br></br>

Leider erkannte ich erst spät, dass eine Progress-Datenbank sinnvoll wäre, damit die User vor Ort bei Besucherandrang die Stände beliebig überspringen können. Ebenfalls kam ich später noch auf die Idee, dass Punkte ein spannender Anreiz wären, um bei den Aufgaben mitzumachen. So musste ich die vielen Skripts immer wieder anpassen, was mühsam war. Mit einer sorgfältigen Planung sowie einem ERM zu Beginn hätte dies vermieden werden können.


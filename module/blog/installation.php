<!DOCTYPE html>
<html>
 <head>
  <meta charset="UTF-8">
  <title>Datenbank-Tabelle erstellen</title>

  <style>
  body {
   font-family: Verdana, Arial, Sans-Serif;
  }
  a:link, a:visited {
   color: #0000EE;
   text-decoration: None;
  }
  a:hover {
   color: #EE0000;
  }
  </style>

 </head>
<body>

<?php
/*
 * Nachrichten - Installation - installation.php (utf-8)
 * - https://werner-zenk.de
 */

include "verbindung.php";

if ($verbindung->query("CREATE TABLE IF NOT EXISTS `" . $TABLE_PREFIX . "nachrichten` (
                                     `id` int(11) NOT NULL AUTO_INCREMENT,
                                     `kategorie` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
                                     `anzeige` tinyint(1) NOT NULL,
                                     `titel` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
                                     `autor` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
                                     `nachricht` text COLLATE utf8_unicode_ci NOT NULL,
                                     `bild` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
                                     `url` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
                                     `pin` tinyint(1) NOT NULL,
                                     `datum` datetime NOT NULL,
                                     PRIMARY KEY (`id`)
                                   ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci")) {
 echo '<h4>Die Datenbank-Tabelle `' . $TABLE_PREFIX . 'nachrichten` wurde erstellt.</h4>
 <p>&raquo; <a href="nachrichten_editor.php">Weiter zur Anmeldung</a></p>';
}
else {
 echo '<h4>Fehler beim erstellen der Datenbank-Tabelle.</h4>';
 print_r($verbindung->errorInfo());
}
?>

</body>
</html>
<?php
/*
 *  Event-Kalender - installation.php (utf-8)
 * - https://werner-zenk.de

  Dieses Script wird in der Hoffnung verteilt, dass es nützlich sein wird, aber ohne irgendeine Garantie;
  ohne auch nur die implizierte Gewährleistung der Marktgängigkeit oder Eignung für einen bestimmten Zweck.
  Weitere Informationen finden Sie in der GNU General Public License.
  Siehe Datei: license.txt - http://www.gnu.org/licenses/gpl.html

  Diese Datei und der gesamte "Event-Kalender" ist urheberrechtlich geschützt (c) 2018 Werner Zenk alle Rechte vorbehalten.
  Sie können diese Datei unter den Bedingungen der GNU General Public License frei verwenden und weiter verbreiten.
 */

include "verbindung.php";
$ausgabe = '';

if ($db->query("CREATE TABLE IF NOT EXISTS `" . $TABLE_PREFIX . "_kalender` (
                         `start` DATETIME NOT NULL,
                         `ende` DATETIME NOT NULL,
                         `name` VARCHAR(30) COLLATE utf8_unicode_ci NOT NULL,
                         `event` VARCHAR(50) COLLATE utf8mb4_unicode_ci NOT NULL,
                         `beschreibung` TEXT COLLATE utf8mb4_unicode_ci NOT NULL,
                         `prioritaet` TINYINT(1) NOT NULL DEFAULT '0',
                         `wiederholung` TINYINT(1) NOT NULL DEFAULT '0',
                         `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY
                        ) ENGINE = InnoDB DEFAULT CHARSET=utf8")):
 // Daten eintragen
 $db->query("INSERT INTO `" . $TABLE_PREFIX . "_kalender` (`start`, `ende`, `name`, `event`, `beschreibung`, `prioritaet`)
                     VALUES (NOW(), '1970-01-01 23:59:00', 'Werner', 'Event-Kalender', 'Danke das Sie sich für den Event-Kalender entschieden haben.\r\nViel Spaß damit!', 2);");

 $ausgabe = '<h4>&#10004; Die DB-Tabelle: `' . $TABLE_PREFIX . '_kalender` wurde erstellt.</h4>
 <p><a href="demo.htm">Event Kalender anzeigen</a></p>';
else:
 $ausgabe = '<h4>&#10008; Fehler beim erstellen der DB-Tabelle: `' . $TABLE_PREFIX . '_kalender`.</h4>' .
  print_r($db->errorInfo(), true);
endif;
?>
<!DOCTYPE html>
<html lang="de">
 <head>
  <meta charset="UTF-8">
  <title>Event-Kalender - Installation</title>

  <style>
  body {
   font-family: Verdana, Arial, Sans-Serif;
  }

  a:link, a:visited {
   color: #529EEA;
  }

  h2, h4 {
   font-weight: Normal;
  }
  </style>

 </head>
<body>

<h2>Event-Kalender - Installation</h2>

<?=$ausgabe;?>

</body>
</html>
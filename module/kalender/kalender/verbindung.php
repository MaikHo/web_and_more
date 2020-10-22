<?php
/*
 *  Event-Kalender - verbindung.php (utf-8)
 * - https://werner-zenk.de

  Dieses Script wird in der Hoffnung verteilt, dass es nützlich sein wird, aber ohne irgendeine Garantie;
  ohne auch nur die implizierte Gewährleistung der Marktgängigkeit oder Eignung für einen bestimmten Zweck.
  Weitere Informationen finden Sie in der GNU General Public License.
  Siehe Datei: license.txt - http://www.gnu.org/licenses/gpl.html

  Diese Datei und der gesamte "Event-Kalender" ist urheberrechtlich geschützt (c) 2018 Werner Zenk alle Rechte vorbehalten.
  Sie können diese Datei unter den Bedingungen der GNU General Public License frei verwenden und weiter verbreiten.
 */


// Verbindungsdaten zur Datenbank
//$DB_HOST = "localhost"; // Host-Adresse
//$DB_NAME = "test"; // Datenbankname
//$DB_BENUTZER = "root"; // Benutzername
//$DB_PASSWORT = ""; // Passwort

//$DB_HOST = "localhost"; // Host-Adresse
//$DB_NAME = "Test"; // Datenbankname
//$DB_BENUTZER = "MaikHo"; // Benutzername
//$DB_PASSWORT = "123456"; // Passwort
include "../../../config.php";
include "../../../".ADMIN_PFAD."einstellungen.php";
// Name (Administrator)
//$NAME = "user";

// Passwort (Administrator)
// Aus Sicherheitsgründen sollte das Passwort min. 8 Zeichen enthalten
//$NAME_PASS[$NAME] = "0000";

// Weitere Namen und Passwörter hinzufügen (Optional)
// $NAME_PASS["Ann Stecknaddel"] = "aua";
// $NAME_PASS["Kai Serschmarn"] = "lecker";


// Events direkt im Kalender eintragen, bearbeiten (ja/nein)
$DIREKTEINGABE = "ja"; // ja

// Kalenderwoche im Kalender anzeigen (ja/nein)
$KALENDERWOCHE_ANZEIGE = "ja"; // ja

// Uhrzeit im Kalender anzeigen (ja/nein)
$UHRZEIT_ANZEIGE = "ja"; // ja

// Wochentage (Mo, Di, Mi, ...) ausschreiben (ja/nein)
$WOCHEN_TAGE = "nein"; // nein

// Priorität im Kalender anzeigen (ja/nein)
$PRIORITAET_ANZEIGEN = "ja"; // ja

// Priorität - Zahl und Farbe anpassen
$PRIORITAET = [
 1 => "#FFD9D9", // Rot
 2 => "#FFFFB7", // Gelb
 3 => "#E1F5BC", // Grün
 4 => "#D5EAFF", // Blau
 5 => "#FFE0C1", // Orange
 6 => "#FFD2FF", // Violet
 7 => "#DEDEBE", // Khaki
 8 => "#B8DCDC", // Cyan
 9 => "#D7D7D7", // Grau
];

// Kalenderblatt anzeigen (ja/nein)
$KALENDERBLATT_ANZEIGE = "ja"; // ja

// Max. Anzahl der aktuellen Events
$AKTUELLE_EVENTS_ANZAHL = 5; // 5

// Feiertage anzeigen (ja/nein)
$FEIERTAGE_ANZEIGE = "ja"; // ja

// Den Namen im Kalender anzeigen (ja/nein)
$NAME_ANZEIGE = "nein"; // nein

// Folgende HTML-Tags können verwendet werden:
$HTML_TAGS = ""; // z.B: <img><b><u><ol><li><span>

// Event als iCal (ics-Datei) exportieren (ja/nein)
// Im Kalender wird dann ein Link angezeigt um den Event herunter zu laden.
$EVENTEXPORT = "nein"; // nein

// Standortbestimmung für die Anzeige von Sonnenauf.- und Sonnenuntergang
// Die geografische Länge und Breite des Standorts ermitteln Sie z.B. unter:
// https://werner-zenk.de/javascript/geolocation_api_bei_knopfdruck_koordianten_ausgeben.php.
$GEO_BREITE = "49.95"; // 49.95
$GEO_LAENGE = "10.95"; // 10.95

// Name der Datenbank-Tabelle (Vorzeichen)
// Ändern wenn z.B.: eine Tabelle mit dem Namen: "db_kalender" existiert!
$TABLE_PREFIX = "db"; // db

// Zeitzone setzen
// http://php.net/manual/de/timezones.europe.php
date_default_timezone_set("Europe/Berlin");

// PHP-Meldungen zum testen anzeigen (0/1)
error_reporting(1); // 1 = anzeigen

// PHP-Version überprüfen
if (version_compare(PHP_VERSION, '5.4.0') < 0)
 die('<p>Aktuelle PHP-Version: ' . PHP_VERSION . '<br>Voraussetzung mind.: 5.4.0</p>');

// Zeichensatz UTF-8 setzen
// Infos: https://werner-zenk.de/tipps/schriftzeichen_richtig_darstellen.php
$OPTION = [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"];

	// Verbindung zur Datenbank aufbauen
	try {
	 $db = new PDO("mysql:host=" . $DB_HOST . ";dbname=" . $DB_NAME, $DB_USER, $DB_PASSWORD,
	 [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"]);
	}
	catch (PDOException $e) {
	 exit("<h4>Verbindung fehlgeschlagen!</h4>" . $e->getMessage());
	}

?>
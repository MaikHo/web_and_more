<?php
/*
 * Nachrichten - Einstellungen - verbindung.php (utf-8)
 * https://werner-zenk.de

 * Hier können Sie die Ausgabe der
 * Nachrichten  individuell anpassen.
 */


// Verbindungsdaten zur Datenbank
//$DB_HOST = "localhost"; // Datenbank-Host
//$DB_NAME = "test"; // Datenbank-Name
//$DB_BENUTZER = "root"; // Datenbank-Benutzer
//$DB_PASSWORT = ""; // Datenbank-Passwort
include "../../config.php";
include "../../".ADMIN_PFAD."einstellungen.php";
//include_once "../../admin/einstellungen.php";
// Administratoren / Autoren festlegen
// Nur die Admin. können alle Nachrichten bearbeiten,
// löschen und freischalten.

$AUTOR = [

    "user" => [ # Benutzername
        'pass' => "0000", # Passwort
        'admin' => true # Admin. (true/false)
    ],

// Weitere Autoren / Administratoren

//    "user2" => [ # Benutzername
//        'pass' => "0002", # Passwort
//        'admin' => false # Admin. (true/false)
//    ],

//    "user3" => [ # Benutzername
//        'pass' => "0003", # Passwort
//        'admin' => false # Admin. (true/false)
//    ],

];


// Wie viele Nachrichten pro Seite anzeigen?
$NACHRICHTEN_SEITE = 3; // 3

// Name des Autors in der Nachricht anzeigen? (ja/nein)
$AUTOR_ANZEIGE = "ja"; // ja

// Kategorien anzeigen? (ja/nein)
$KATEGORIEN = "ja"; // ja

// Erste Kategorie (wird vorausgewählt) Optional
$ERSTE_KATEGORIE = "Aktuell"; // Aktuell

// Nachrichten-Liste der aktuellen Seite anzeigen? (ja/nein)
$NACHRICHTEN_LISTE = "nein"; // nein

// Navigation unten anzeigen? (ja/nein)
$NAVIGATION = "ja"; // ja

// Weitere Nachrichten (Überschriften) der aktuellen Kategorie anzeigen? (ja/nein)
$KATEGORIE_LISTE = "nein"; // nein

// Maximale Anzahl der Nachrichten in der Kategorien-Liste
$KATEGORIE_LISTE_ANZAHL = 12; // 12

// Seitenübersicht (Sitemap) anzeigen? (ja/nein)
$SITEMAP = "nein"; // nein

// Newsticker anzeigen? (ja/nein)
$NEWSTICKER = "nein"; // nein

// Newsticker - Anzahl der Nachrichten
$NEWSTICKER_ANZAHL = 5; // 5


// Gekürzte Nachrichten anzeigen? (ja/nein)
$KURZNACHRICHTEN = "nein"; // nein

// Länge der gekürzten Nachrichten
$KURZNACHRICHTEN_ZEICHEN = 1000; // 1000 Zeichen

// "NEU"-Markierung der Nachrichten anzeigen? (ja/nein)
$NEU_MARKIERUNG = "nein"; // nein

// X-tägige "NEU"-Markierung
$NEU_MARKIERUNG_TAGE = 7; // 7 Tage

// Formular zum suchen anzeigen? (ja/nein)
$SUCHFORM = "ja"; // ja

// Länge des minimalen Suchbegriffs (min. 3 Zeichen)
$SUCHBEGRIFF_MIN = 4; // 4

// Maximale Anzeige (Begrenzung) der Suchergebnisse
$SUCHERGEBNISSE_MAX = 25; // 25

// Auswahllisten (Sortierung / Anzahl) anzeigen? (ja/nein)
$AUSWAHLLISTEN = "ja"; // ja


// Verzeichnis in dem sich die Bilder befinden (relativ von dieser Datei)
$BILDPFAD = "bilder/"; // Benötigt Lese.- und Schreibrechte!

// Maximale Bild-Abmessungen (Breite und Höhe)
$MAX_BILD_BREITE = 320; // 320 Pixel
$MAX_BILD_HOEHE = 220; // 220 Pixel

// Die Dateigröße des Bildes das maximal hoch geladen werden darf (in Bytes).
$BILD_MAXGROESSE = 2097152; // (2097152 = 2MB) 1048576 Bytes = 1 MB

// Angabe der Bild-Mimetypen die hoch geladen werden dürfen.
$BILD_MIMETYPEN = [
"png" => "image/png",
"jpg" => "image/jpeg",
"jpg" => "image/pjpeg",
"jpeg" => "image/jpeg",
"gif" => "image/gif",
];

// Bilder mit Wasserzeichen markieren? (ja/nein)
$WASSERZEICHEN = "nein"; // nein

// Wasserzeichen Bild
// Es muss eine PNG-Bild-Datei sein!
$WASSERZEICHEN_BILD = "logo.png"; // logo.png

// Wasserzeichen Transparenz (0 - 100)
$WASSERZEICHEN_TRAN = 30; // 30 (65 = Standard!)


// Name der Tabelle (Vorzeichen)
// Nach der Installation bitte nicht mehr ändern!
$TABLE_PREFIX = "wbs_"; // wbs_

// Die Nachrichten automatisch freischalten? (0/1)
$NACHRICHTEN_CHECK = 0; // 0 (1 = Freischaltung)

// Welche HTML-Elemente in der Nachricht zulassen?
// Die HTML-Elemente durch Komma trennen.
$HTML_TAGS = "<h1>, <a>, <b>, <i>, <p>, <s>, <u>, <span>, <img>, <samp>, <mark>";

// Begrenzung der Nachricht bei der Eingabe
$MAX_NACHRICHT = 10000; // 10000 Zeichen

// PHP-Version überprüfen
if (version_compare(PHP_VERSION, '5.4.0') < 0)
 die('<p>Aktuelle PHP-Version: ' . PHP_VERSION . '<br>Voraussetzung mind.: 5.4.0</p>');

// PHP-Meldungen zum testen anzeigen (0/1)
error_reporting(0); // 1 = anzeigen

// Zeitzone setzen (ab PHP7 nicht mehr nötig!)
// http://php.net/manual/de/timezones.europe.php
date_default_timezone_set("Europe/Berlin");

 // Zeichensatz UTF-8 setzen
 // Infos: https://werner-zenk.de/tipps/schriftzeichen_richtig_darstellen.php
 $OPTION = [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"];

// Verbindung zur Datenbank aufbauen
try {
 $verbindung = new PDO("mysql:host=" . $DB_HOST . ";dbname=" . $DB_NAME,
  $DB_USER, $DB_PASSWORD, $OPTION);
}
catch (PDOException $e) {
 // Bei einer fehlerhaften Verbindung eine Nachricht ausgeben
 exit("Verbindung fehlgeschlagen! " . $e->getMessage());
}
?>
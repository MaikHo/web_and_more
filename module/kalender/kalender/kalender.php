<?php
/*
 * Event-Kalender - kalender.php (utf-8)
 * - https://werner-zenk.de

  Dieses Script wird in der Hoffnung verteilt, dass es nützlich sein wird, aber ohne irgendeine Garantie;
  ohne auch nur die implizierte Gewährleistung der Marktgängigkeit oder Eignung für einen bestimmten Zweck.
  Weitere Informationen finden Sie in der GNU General Public License.
  Siehe Datei: license.txt - http://www.gnu.org/licenses/gpl.html

  Diese Datei und der gesamte "Event-Kalender" ist urheberrechtlich geschützt (c) 2018 Werner Zenk alle Rechte vorbehalten.
  Sie können diese Datei unter den Bedingungen der GNU General Public License frei verwenden und weiter verbreiten.
 */
session_start();
if (!isset($_SESSION["login"])) {
 header("Location: ../index.php");
 exit; 
}

// PHP-Header UTF-8 senden
header('Content-Type: text/plain; charset=UTF-8');

include "verbindung.php";
include "kalenderfunktionen.php";

$monate = [1 => 'Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember'];
$wochentage = ['Sonntag', 'Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag', 'Sonntag']; // Sonntag-Sonntag

define('ANZEIGE', '<div id="anzeigeBeenden"><span class="navLink print" onClick="anzeigeBeenden()" title="Schließen">&#9587;</span></div>');
define('ANZEIGE2', '<div id="anzeigeBeenden2"><span class="navLink print" onClick="anzeigeBeenden2()" title="Schließen">&#9587;</span></div>');


// Kalender anzeigen
if (isset($_GET["kalender"])) {

 // Datum ermitteln
 $monat = isset($_GET["monat"]) ? $_GET["monat"] : date("n");
 $monat = ctype_digit($monat) ? intval($monat) : date("n");
 $jahr = isset($_GET["jahr"]) ? $_GET["jahr"] : date("Y");
 $jahr = ctype_digit($jahr) ? intval($jahr) : date("Y");
 $jahr = (($monat < 1) ? $jahr-=1 : (($monat > 12) ? $jahr+=1 : $jahr));
 $monat = (($monat < 1) ? 12 : (($monat > 12) ? 1 : $monat));

 // Jahr überprüfen (http://de.wikipedia.org/wiki/Jahr-2038-Problem)
 // Ok: Win10 64bit PHP7 (eg. nur bis 2087!)
 if ($_SERVER["SERVER_NAME"] != "localhost") {
  if ($jahr < 1970 || $jahr > 2037) {
   $jahr = date("Y");
  }
 }


 // CSS - Priorität
 $css = '<style>';
 foreach ($PRIORITAET as $nr => $color) {
  $css .= '.p' . $nr . '{background-color:' . $color . ';font-size:10px;margin-top:3px;}';
 }
 $css .= '</style>';


 // Events des gewählten Monats auslesen
 $select = $db->prepare("SELECT `start`, `ende`, `event`, `beschreibung`, `prioritaet`, `id`
                                       FROM `" . $TABLE_PREFIX . "_kalender`
                                       WHERE YEAR(`start`) = :jahr AND MONTH(`start`) = :monat
                                             OR (MONTH(`start`) = :monat AND :jahr >= YEAR(`start`) AND `wiederholung` = 1)
                                       ORDER BY `start` ASC");
 if ($select->execute([':jahr' => $jahr,
                                 ':monat' => $monat])) {
  $events = $select->fetchAll();

  // Events für jeden Tag sammeln und verlinken
  $daten = [];
  for ($i = 1; $i <= date("t", mktime(0, 0, 0, $monat, 1, $jahr)); $i++) {
   foreach ($events as $event) {
    $prioritaet = $PRIORITAET_ANZEIGEN == "ja" && $event["prioritaet"] > 0 ? ' p' . $event["prioritaet"]  : '';
    sscanf($event["start"], "%4s-%2s-%2u %5s", $dbJahr, $dbMonat, $dbTag, $dbUhr);
    $ende = substr($event["ende"], 11, 8) != "23:59:00" ? '-' . substr($event["ende"], 11, 5) : '';
    $zeit = $UHRZEIT_ANZEIGE == "ja" ? '<span class="uhrzeit">' . $dbUhr . $ende . '</span>&thinsp;' : '';
    if ($i == $dbTag) {
     if (isset($daten[$i])) {
      $daten[$i] .= '<div class="' . $prioritaet . '">' . $zeit . '<span class="eventLink' . $prioritaet . '" onClick="zeigeEvent(' . $event["id"] . ',' .  $dbTag . ')" ' . title($dbTag, $dbMonat, $dbJahr, $dbUhr . $ende, $event["beschreibung"]) . '>' . $event["event"] . '</span></div>';
     }
     else {
      $daten[$i] = '<div class="' . $prioritaet . '">' . $zeit . '<span class="eventLink" onClick="zeigeEvent(' . $event["id"] . ',' .  $dbTag . ')" ' . title($dbTag, $dbMonat, $dbJahr, $dbUhr . $ende, $event["beschreibung"]) . '>' . $event["event"] . '</span></div>';
     }
    }
   }
  }


  // Navigation
  $kalender = $css . '<table id="kalender">
<tr>
 <td id="navigation" colspan="7">
 <span>
 <span class="navLink print" onClick="zeigeKalender(' . $jahr . ', ' . ($monat - 1) . ')" title="Einen Monat zurück">&#9668;</span>
 <span class="navLink navBlock" onClick="zeigeKalender(' . $jahr . ', ' . $monat . ', true)" title="Monat auswählen">' . $monate[$monat] . '</span>
 <span class="navLink print" onClick="zeigeKalender(' . $jahr . ', ' . ($monat + 1) . ')" title="Einen Monat vorwärts">&#9658;</span>&emsp;
 </span>
 <span>
 <span class="navLink print" onClick="zeigeKalender(' . date("Y,n") . ')" title="Aktueller Kalender">&#9673;</span>&nbsp;
 <span class="navLink print" onClick="zeigeKalender(' . $jahr . ', ' . $monat . ', false, false, true)" title="Optionen">&#9724;</span>&nbsp;
 <span class="navLink print" onClick="zeigeAktuelleEvents()" title="Aktuelle Events">&#9670;</span>&emsp;
 </span>
 <span>
 <span class="navLink print" onClick="zeigeKalender(' . ($jahr - 1) . ', ' . $monat . ')" title="Ein Jahr zurück">&#9668;</span>
 <span class="navLink navBlock" onClick="zeigeKalender(' . $jahr . ', ' . $monat . ', false, true)" title="Jahr auswählen">' . $jahr . '</span>
 <span class="navLink print" onClick="zeigeKalender(' . ($jahr + 1) . ', ' . $monat . ')" title="Ein Jahr vorwärts">&#9658;</span>
 </span>
 </td>
</tr>';


  // Monate-Auswahl
  if (isset($_GET["monate"])) {
   $kalender .= '<tr><th colspan="7" id="kalenderOptionen" class="print">';
   $kalender .= '<div style="display: Inline-Block; width: 95%;">';
   foreach ($monate as $i => $m) {
    $kalender .= '<span class="navLink" onClick="zeigeKalender(' . ($jahr) . ', ' . $i . ')" title="Monat ' . $m . ' anzeigen">' .
    ($i == date("n") ? '<b>' . $m . '</b>' : ($i == $_GET["monat"] ? '<u>' . $m . '</u>' : $m)) . '</span> ';
   }
   $kalender .= '</div>' . ANZEIGE2 . '</th></tr>';
  }


  // Jahre-Auswahl
  if (isset($_GET["jahre"])) {
   $kalender .= '<tr><th colspan="7" id="kalenderOptionen" class="print">';
   $kalender .= '<div style="display: Inline-Block; width: 95%;">';
   for ($i = ($_GET["jahr"] - 5); $i <= ($_GET["jahr"] + 5); $i++) {
    $kalender .= '<span class="navLink" onClick="zeigeKalender(' . ($i) . ', ' . $monat . ')" title="Jahr ' . $i . ' anzeigen">' .
    ($i == date("Y") ? '<b>' . $i . '</b>' : ($i == $_GET["jahr"] ? '<u>' . $i . '</u>' : $i)) . '</span> ';
   }
   $kalender .= ' <span class="navLink" onClick="zeigeKalenderEingabe(' . $monat . ')" title="Gewünschtes Jahr eingeben"><b>&#8943;</b></span>';
   $kalender .= '</div>' . ANZEIGE2 . '</th></tr>';
  }


  // Kalenderwoche / Suche
  if (isset($_GET["suche"])) {
   $kw = kalenderwoche($tag, $monat, $jahr);
   $kalenderwocheAW = '<select id="kwoche">';
   foreach (range(1, 53) as $i) {
    $kalenderwocheAW .= '<option value="' . $i . '"' . ($i == $kw ? ' selected="selected"' : '') . '>' . $i . '</option>';
   }
   $kalenderwocheAW .= '</select>';
   $kalender .= '<tr><th colspan="7" id="kalenderOptionen" class="print">
   <div style="display: Inline-Block; width: 94%;">
   <form id="suchform" action="javascript:suche()">
   <span class="navBlock"><label>Kalenderwoche: ' . $kalenderwocheAW . '</label>
   <input type="button" value="los" onClick="zeigeKalenderwoche()"></span>&emsp;
   <span class="navBlock"><input type="search" name="suchbegriff" placeholder="Suchbegriff ..." required="required">
   <input type="hidden" name="jahr" id="jahr" value="' . $_GET["jahr"] . '">
   <input type="button" value="los" onClick="suche()"></span>
   </form>
   </div>' . ANZEIGE2 . '</th></tr>';
  }


  // Wochentage
  $kalender .= '<tr>';
  for ($i = 1; $i <= 7; $i++) {
   $wtnamen = $WOCHEN_TAGE == "ja" ? $wochentage[$i] : mb_substr($wochentage[$i], 0, 2);
   $kalender .= chr(13) . '<th';
   if ($i == 6 || $i == 7) {
    $kalender .= ' class="wochenende"';
   }
   else {
    $kalender .= ' class="wochentag"';
   }
   $kalender .= ' width="14.285714%"><span class="navLink" onClick="wochenTage(' . $jahr . ', \'' . $wochentage[$i] . '\')" title="' . $wochentage[$i] . ' (Alle anzeigen)">' . $wtnamen . '</span></th>';
  }
  $kalender .= '</tr>' . chr(13);


  // Länge des akt. Monats
  $monatslaenge = date("t", mktime(0, 0, 0, $monat, 1, $jahr));
  // Länge des letzten Monats
  $altmonatslaenge = $monat > 1 ? date("t", mktime(0, 0, 0, ($monat - 1), 1, $jahr)) : 31;
  // Erster Wochentag im akt. Monat
  $ersterwochentag = date("w", mktime(0, 0, 0, $monat, 1, $jahr)) == 0 ? 7 : date("w", mktime(0, 0, 0, $monat, 1, $jahr));


  // Kalendertage des letzten Monats
  for ($i = 1; $i < $ersterwochentag; $i++) {
   $kalender .= '  <td class="keintag" onClick="zeigeKalender(' . $jahr . ', ' . ($monat - 1) . ')" title="Zum Monat ' . ($monat-1 < 1 ? $monate[12] : $monate[$monat-1]) . '">' .
    (($altmonatslaenge - ($ersterwochentag - 1)) + $i) . '</td>';
  }


  // Aktuelle Kalendertage
  for ($tag = 1; $tag <= $monatslaenge; $tag++) {
   $rest = ($tag + $ersterwochentag -1) %7;
   $kalenderwoche = kalenderwoche($tag, $monat, $jahr);
   $feiertag = $FEIERTAGE_ANZEIGE == "ja" ? feiertag($tag, $monat, $jahr) : '';
   $feiertag = $FEIERTAGE_ANZEIGE == "ja" && !empty($feiertag)  ? '<span class="feiertag">' . $feiertag . '</span>' : '';
   $blatt = $KALENDERBLATT_ANZEIGE == "ja"  ? '<span class="navLink print" onClick="zeigeKalenderblatt(' . $tag . ',' . $monat . ',' . $jahr . ')" title="' . datum($tag, $monat, $jahr) . ' KW ' . $kalenderwoche . '&#10;Kalenderblatt anzeigen">&#9663;</span>' : '';
   $kalender .= '<td class="' . (($tag == date("j") &&
                                              $monat == date("n") &&
                                              $jahr == date("Y")) ? 'heute ' : '') . 'eintag" id="n' . $tag . '">' .
   '<span class="navLink" onClick="zeigeTagesansicht(' . $tag . ',' . $monat . ',' . $jahr . ')" title="' . datum($tag, $monat, $jahr) . ' KW ' . $kalenderwoche . '&#10;Tagesansicht anzeigen">' . $tag . '</span>' .
   $blatt . $feiertag . (isset($daten[$tag]) ? '<br>' . $daten[$tag] : '') . '</td>' . chr(13);
   if ($rest == 0) $kalender .= '</tr>' . chr(13) . '<tr>';
  }


  // Kalendertage des nächsten Monats
  $neumonat = 1;
  for ($i = $rest; $i < 7; $i++) {
   $kalender .= '<td class="keintag" onClick="zeigeKalender(' . $jahr . ', ' . ($monat + 1) . ')" title="Zum Monat ' . ($monat+1 > 12 ? $monate[1] : $monate[$monat+1]) . '">' . $neumonat . '</td>';
   $neumonat++;
  }
  echo $kalender . '</tr>' . 
   '<tr><td id="anzeige" colspan="7" style="visibility: hidden;"></td></tr>' .
  '</table>';
 }
}


// Event eintragen
if (isset($_POST["eintragen"])) {

 // Benutzer überprüfen
 if (isset($_SESSION["login"])) {

  // Event vorhanden
  if (trim($_POST["event"]) != "") {

   // Datum zusammensetzen
   $start = $_POST["jahr"] . '-' . sprintf("%02s", $_POST["monat"]) . '-' . sprintf("%02s", $_POST["tag"]) . ' ' . $_POST["stunde"] . ':' . $_POST["minute"] . ':' . "01";
   $ende = $_POST["jahr"] . '-' . sprintf("%02s", $_POST["monat"]) . '-' . sprintf("%02s", $_POST["tag"]) . ' ' . $_POST["stunde2"] . ':' . $_POST["minute2"] . ':00';
   $_POST["beschreibung"] = strip_tags(trim($_POST["beschreibung"]), $HTML_TAGS);
   $wiederholung = isset($_POST["wiederholung"]) ? 1 : 0;

   // Eintragen
   $insert = $db->prepare("INSERT INTO `" . $TABLE_PREFIX . "_kalender`
                                         SET
                                           `start`= :start,
                                           `ende`= :ende,
                                           `name`= :name,
                                           `event`= :event,
                                           `beschreibung`= :beschreibung,
                                           `prioritaet`= :prioritaet,
                                           `wiederholung`= :wiederholung");
   if ($insert->execute([':start' => $start,
                                  ':ende' => $ende,
                                  ':name' => $_POST["name"],
                                  ':event' => strip_tags($_POST["event"]),
                                  ':beschreibung' => $_POST["beschreibung"],
                                  ':prioritaet' => $_POST["prioritaet"],
                                  ':wiederholung' => $wiederholung])) {
    echo $_POST["jahr"] . '|' . $_POST["monat"];
   }
  }
  else {
   echo ANZEIGE . '<p id="fehler"><mark class="fehler">&#10008;</mark> Es wurde kein Event angegeben!</p>';
  }
 }
 else {
  echo ANZEIGE . '<p id="fehler"><mark class="fehler">&#10008;</mark> Der Name oder das Passwort ist falsch!</p>';
 }
}


// Event kopieren oder aktualisieren
if (isset($_POST["aktualisieren"])) {

 // Datum überprüfen
 if (checkdate($_POST["monat"], $_POST["tag"], $_POST["jahr"])) {

  // Benutzer überprüfen
  if (isset($_SESSION["login"])) {

   // Event vorhanden
   if (trim($_POST["event"]) != "") {

    // Name aus der DB-Tabelle holen
    $select = $db->prepare("SELECT `name`
                                         FROM `" . $TABLE_PREFIX . "_kalender`
                                         WHERE `id` = :id");
    $select->execute([':id' => $_POST["id"]]);
    $status = $select->fetch();

    // Berechtigung des Benutzers überprüfen
    if (trim($_POST["name"]) === $status["name"] ||
        trim($_POST["name"]) === $_SESSION["benutzer"]) {

     // Datum zusammensetzen
     $start = $_POST["jahr"] . '-' . sprintf("%02s", $_POST["monat"]) . '-' . sprintf("%02s", $_POST["tag"]) . ' ' . $_POST["stunde"] . ':' . $_POST["minute"] . ':' . "01";
     $ende = $_POST["jahr"] . '-' . sprintf("%02s", $_POST["monat"]) . '-' . sprintf("%02s", $_POST["tag"]) . ' ' . $_POST["stunde2"] . ':' . $_POST["minute2"] . ':00';
     $_POST["beschreibung"] = strip_tags(trim($_POST["beschreibung"]), $HTML_TAGS);

     // Event kopieren
     if (isset($_POST["kopieren"])) {
      $insert = $db->prepare("INSERT INTO `" . $TABLE_PREFIX . "_kalender`
                                            SET
                                              `start`= :start,
                                              `ende`= :ende,
                                              `name`= :name,
                                              `event`= :event,
                                              `beschreibung`= :beschreibung,
                                              `prioritaet`= :prioritaet");
      if ($insert->execute([':start' => $start,
                                     ':name' => $_POST["name"],
                                     ':ende' => $ende,
                                     ':event' => strip_tags($_POST["event"]),
                                     ':beschreibung' => $_POST["beschreibung"],
                                     ':prioritaet' => $_POST["prioritaet"]])) {
       echo intval($_POST["jahr"]) . '|' . intval($_POST["monat"]);
      }
     }
     // Event aktualisieren
     else {
      $wiederholung = isset($_POST["wiederholung"]) ? 1 : 0;
      $update = $db->prepare("UPDATE `" . $TABLE_PREFIX . "_kalender`
                                              SET
                                                `start`= :start,
                                                `ende`= :ende,
                                                `event`= :event,
                                                `beschreibung`= :beschreibung,
                                                `prioritaet`= :prioritaet,
                                                `wiederholung`= :wiederholung
                                               WHERE
                                                `id` = :id");
      if ($update->execute([':start' => $start,
                                        ':ende' => $ende,
                                        ':event' => strip_tags($_POST["event"]),
                                        ':beschreibung' => $_POST["beschreibung"],
                                        ':prioritaet' => $_POST["prioritaet"],
                                        ':wiederholung' => $wiederholung,
                                        ':id' => $_POST["id"]])) {
       echo intval($_POST["jahr"]) . '|' . intval($_POST["monat"]);
      }
     }
    }
    else {
     echo ANZEIGE . '<p id="fehler"><mark class="fehler">&#10008;</mark> Sie haben keine Berechtigung den Event zu ändern!</p>';
    }
   }
   else {
    echo ANZEIGE . '<p id="fehler"><mark class="fehler">&#10008;</mark> Es wurde kein Event angegeben!</p>';
   }
  }
  else {
   echo ANZEIGE . '<p id="fehler"><mark class="fehler">&#10008;</mark> Der Name oder das Passwort ist falsch!</p>';
  }
 }
 else {
  echo ANZEIGE . '<p id="fehler"><mark class="fehler">&#10008;</mark> <strong>' .
   $_POST["tag"] . '.' . $_POST["monat"] . '.' . $_POST["jahr"] . '</strong> - Ungültiges Datum!</p>';
 }
}


// Event löschen
if (isset($_POST["loeschen"])) {

 // Benutzer überprüfen
 if (isset($_SESSION["login"])) {

  // Jahr, Monat und Name aus der DB-Tabelle holen
  $select = $db->prepare("SELECT YEAR(`start`) AS jahr, MONTH(`start`) AS monat, `name`
                                        FROM `" . $TABLE_PREFIX . "_kalender`
                                        WHERE `id` = :id");
  $select->execute([':id' => $_POST["id"]]);
  $status = $select->fetch();

  // Berechtigung des Benutzers überprüfen
  if (trim($_POST["name"]) === $status["name"] ||
      trim($_POST["name"]) === $_SESSION["benutzer"]) {

   // Löschen
   $delete = $db->prepare("DELETE FROM `" . $TABLE_PREFIX . "_kalender`
                                          WHERE `id`= :id");
   if ($delete->execute([':id' => $_POST["id"]])) {
    echo $status["jahr"] . '|' . $status["monat"];
    }
   }
   else {
    echo ANZEIGE . '<p id="fehler"><mark class="fehler">&#10008;</mark> Sie haben keine Berechtigung den Event zu löschen!</p>';
   }
  }
  else {
  echo ANZEIGE . '<p id="fehler"><mark class="fehler">&#10008;</mark> Es konnte kein Benutzer mit den eingegeben Daten gefunden werden!</p>';
 }
}


// Formular zeigen
if (isset($_GET["form"])) {
 echo ANZEIGE . '<form id="Form" method="post">';

 switch ($_GET["form"]) {

  // Eintragen
  case 'eintragen':
   $abgelaufen = eventAktuell(sprintf("%02s", $_GET["tag"]), sprintf("%02s", $_GET["monat"]), $_GET["jahr"], 23, 59, "23:59:00");
   $kalenderwoche = $KALENDERWOCHE_ANZEIGE == "ja" ? ' <span title="Kalenderwoche">KW&nbsp;' . kalenderwoche($_GET["tag"], $_GET["monat"], $_GET["jahr"]) . '</span>' : '';
   echo '<mark class="mark">Event eintragen</mark><br>
            &#9782; ' . datum($_GET["tag"], $_GET["monat"], $_GET["jahr"]) . $kalenderwoche . ' ' . $abgelaufen . '<br>
            ' . auswahlUhrzeit() . auswahlUhrzeit2() . '<br>
            <input type="checkbox" name="wiederholung" id="wiederholung"> <label for="wiederholung" title="Jährliche Wiederholung (für Geburtstage, Feiertage etc.)">&#11118; Jährl. Wiederholung</label><br>
            <label>&#9655; <input type="text" name="event" id="event" size="28" maxlength="50" required="required" placeholder="Event" spellcheck="true"></label>
            ' . auswahlPrioritaet(0, $PRIORITAET) . '<br>
            <textarea name="beschreibung" id="textarea" placeholder="Beschreibung (Optional)"></textarea><br>
            <input type="hidden" name="tag" value="' . $_GET["tag"] . '">
            <input type="hidden" name="monat" value="' . $_GET["monat"] . '">
            <input type="hidden" name="jahr" value="' . $_GET["jahr"] . '">
            <input type="hidden" name="eintragen">';
  break;

  // Aktualisieren
  case 'aktualisieren':
   $select = $db->prepare("SELECT `start`, `ende`, `event`, `beschreibung`,  `prioritaet`, `wiederholung`, `id`
                                          FROM `" . $TABLE_PREFIX . "_kalender`
                                          WHERE `id` = :id");
   $select->execute([':id'=>$_GET["id"]]);
   $event = $select->fetch();
   sscanf($event["start"], "%4s-%2s-%2s %2s:%2s", $dbJahr, $dbMonat, $dbTag, $dbStunde, $dbMinute);
   sscanf($event["ende"], "%4s-%2s-%2s %2s:%2s", $a, $b, $c, $dbStunde2, $dbMinute2);
   $ende = substr($event["ende"], 11, 8) != "23:59:00" ? '-' . substr($event["ende"], 11, 5) : '';
   $abgelaufen = eventAktuell($dbTag, $dbMonat, $dbJahr, $dbStunde, $dbMinute, $event["ende"]);
   $kalenderwoche = $KALENDERWOCHE_ANZEIGE == "ja" ? ' <span title="Kalenderwoche">KW&nbsp;' . kalenderwoche($dbTag, $dbMonat, $dbJahr) . '</span>' : '';
   $wiederholung = $event["wiederholung"] == 1 ? ' checked="checked"' : '';
   $wiederholungMK = $event["wiederholung"] == 1 ? ' <span title="Jährliche Wiederholung">&#11118;</span>' : '';
   echo '<mark class="mark">Event aktualisieren</mark><br>
            &#9782; ' . datum($dbTag, $dbMonat, $dbJahr) . $kalenderwoche . ' ' . $wiederholungMK . '&nbsp;<span class="nowrap">&#9684; ' . $dbStunde . ':' . $dbMinute . $ende . ' Uhr' . $abgelaufen . '</span><br>' .
            auswahlTag($dbTag) . auswahlMonat($dbMonat) . auswahlJahr($dbJahr) . '<br>' .
            auswahlUhrzeit($dbStunde, $dbMinute) . auswahlUhrzeit2($dbStunde2, $dbMinute2) . '<br>
            <input type="checkbox" name="kopieren" id="kopieren">  <label for="kopieren">&#128461; Event kopieren</label> 
            <span class="nowrap"><input type="checkbox" name="wiederholung" id="wiederholung"' . $wiederholung . '> <label for="wiederholung" title="Jährliche Wiederholung (für Geburtstage, Feiertage etc.)">&#11118; Jährl. Wiederholung</label></span><br>
             <label>&#9655; <input type="text" name="event" id="event" value="' . $event["event"] . '" size="28" maxlength="50" required="required" placeholder="Event" spellcheck="true"></label>
             ' . auswahlPrioritaet($event["prioritaet"], $PRIORITAET) . '<br>
            <textarea name="beschreibung" id="textarea" placeholder="Beschreibung (Optional)">' . $event["beschreibung"] . '</textarea><br>
            <input type="hidden" name="id" value="' . $event["id"] . '">
            <input type="hidden" name="aktualisieren">';
  break;

  // Löschen
  case 'loeschen':
   $select = $db->prepare("SELECT `start`, `ende`, `event`, `wiederholung`, `id`
                                          FROM `" . $TABLE_PREFIX . "_kalender`
                                          WHERE `id` = :id");
   $select->execute([':id'=>$_GET["id"]]);
   $event = $select->fetch();
   sscanf($event["start"], "%4s-%2s-%2s %2s:%2s", $dbJahr, $dbMonat, $dbTag, $dbStunde, $dbMinute);
   $ende = substr($event["ende"], 11, 8) != "23:59:00" ? '-' . substr($event["ende"], 11, 5) : '';
   $abgelaufen = eventAktuell($dbTag, $dbMonat, $dbJahr, $dbStunde, $dbMinute, $event["ende"]);
   $kalenderwoche = $KALENDERWOCHE_ANZEIGE == "ja" ? ' <span title="Kalenderwoche">KW&nbsp;' . kalenderwoche($dbTag, $dbMonat, $dbJahr) . '</span>' : '';
   $wiederholung = $event["wiederholung"] == 1 ? ' <span title="Jährliche Wiederholung">&#11118;</span>' : '';
   echo '<mark class="mark">Event löschen</mark><br>
            &#9782; ' . datum($dbTag, $dbMonat, $dbJahr) . $kalenderwoche . ' ' . $wiederholung . '&nbsp;<span class="nowrap">&#9684; 
            ' . $dbStunde . ':' . $dbMinute . $ende . ' Uhr' . $abgelaufen . '</span><br>
            &#9655; <b> ' . $event["event"] . '</b><br>
            <input type="hidden" name="id" value="' . $event["id"] . '">
            <input type="hidden" name="loeschen">';
  break;
 }

 // Benutzername und Passwort
 echo '<label class="hidden">&#9711; <input type="text" name="name" id="name" size="15" required="required"
 placeholder="Name" autocomplete="username"></label>&nbsp;
  <label class="hidden">&bull;&bull;&bull; <input type="password" name="passwort" id="passwort" size="12" required="required"
 placeholder="Passwort" autocomplete="current-password" onKeyDown="if (event.key == \'Enter\') sendeFormular()"></label>&nbsp;
  <input type="button" value="OK" onClick="sendeFormular()">
 </form>';
}


// Event
if (isset($_GET["event"])) {
 $select = $db->prepare("SELECT `start`, `ende`, `name`, `event`, `beschreibung`, `prioritaet`, `wiederholung`, `id`
                                       FROM `" . $TABLE_PREFIX . "_kalender`
                                       WHERE `id` = :id");
 $select->execute([':id' => $_GET["id"]]);
 $event = $select->fetch();
 sscanf($event["start"], "%4s-%2s-%2s", $dbJahr, $dbMonat, $dbTag);
 echo ANZEIGE . '<div id="aktevents"><mark class="mark">Event</mark>' .
  anzeigen($event["start"], $event["ende"], $event["name"], $event["event"], $event["beschreibung"], $event["prioritaet"], $event["wiederholung"], $event["id"],
    $DIREKTEINGABE, $PRIORITAET_ANZEIGEN, $PRIORITAET, $NAME_ANZEIGE, $KALENDERWOCHE_ANZEIGE, $EVENTEXPORT, $KALENDERBLATT_ANZEIGE) .
  '</div>';
}


// Tagesansicht
if (isset($_GET["tagesansicht"])) {
 echo ANZEIGE . '<div id="aktevents"><mark class="mark">Tagesansicht ' . $_GET["tag"] . ' ' . monat($_GET["monat"]) . ' ' . $_GET["jahr"] . '</mark>';
 $select = $db->prepare("SELECT `start`, `ende`, `name`, `event`, `beschreibung`, `prioritaet`, `wiederholung`, `id`
                                       FROM `" . $TABLE_PREFIX . "_kalender`
                                       WHERE `start` LIKE :start
                                       ORDER BY `start` ASC");
 $select->execute([':start' => $_GET["jahr"] . '-' . sprintf("%02s", $_GET["monat"]) . '-' . sprintf("%02s", $_GET["tag"]) . '%']);
 $events = $select->fetchAll();
 if ($select->rowCount() > 0) {
  echo ' (' . $select->rowCount() . ($select->rowCount() == 1 ? ' Eintrag' : ' Einträge') . ')';
  foreach ($events as $event) {
  echo anzeigen($event["start"], $event["ende"], $event["name"], $event["event"], $event["beschreibung"], $event["prioritaet"], $event["wiederholung"], $event["id"],
    $DIREKTEINGABE, $PRIORITAET_ANZEIGEN, $PRIORITAET, $NAME_ANZEIGE, $KALENDERWOCHE_ANZEIGE, $EVENTEXPORT, $KALENDERBLATT_ANZEIGE);
  }
 }
 else {
  $direkteingabe = $DIREKTEINGABE == "ja" ? '<span class="eventLink" onClick="zeigeFormular(\'eintragen\',' . abs($_GET["tag"]) . ',' . abs($_GET["monat"]) . ',' . $_GET["jahr"] . ',null,true)" title="Event eintragen">Eintragen</span>&nbsp; ' : '';
  $kw = kalenderwoche($_GET["tag"], $_GET["monat"], $_GET["jahr"]);
  $kalenderwoche = $KALENDERWOCHE_ANZEIGE == "ja" ? '<span class="eventLink" onClick="zeigeKalenderwoche2(' . $_GET["jahr"] . ',' . $kw . ')" title="Kalenderwoche ' . $kw . ' anzeigen">Kalenderwoche</span>&nbsp; ' : '';
  $kalenderblatt = $KALENDERBLATT_ANZEIGE == "ja" ? '<span class="eventLink" onClick="zeigeKalenderblatt(' . abs($_GET["tag"]) . ',' . abs($_GET["monat"]) . ',' . $_GET["jahr"] . ')" title="Kalenderblatt anzeigen">Kalenderblatt</span>' : '';
  echo '<p><mark class="mark">&#10149;</mark> Kein Event vorhanden!<br>' . $direkteingabe . $kalenderwoche . $kalenderblatt . '</p>';
 }
 echo '</div>';
}


// Aktuelle Events
if (isset($_GET["aktuelleevents"])) {
 echo ANZEIGE . '<div id="aktevents"><mark class="mark">Aktuelle Events</mark>';
 $select = $db->query("SELECT `start`, `ende`, `name`, `event`, `beschreibung`, `prioritaet`, `wiederholung`, `id`
                                    FROM `" . $TABLE_PREFIX . "_kalender` 
                                    WHERE (TO_DAYS(`start`) - TO_DAYS(NOW())) >= 0 AND (TO_DAYS(`start`) - TO_DAYS(NOW())) <= 30
                                    ORDER BY `start` ASC LIMIT " . $AKTUELLE_EVENTS_ANZAHL);
 $events = $select->fetchAll();
 if ($select->rowCount() > 0) {
  foreach ($events as $event) {
  echo anzeigen($event["start"], $event["ende"], $event["name"], $event["event"], $event["beschreibung"], $event["prioritaet"], $event["wiederholung"], $event["id"],
    $DIREKTEINGABE, $PRIORITAET_ANZEIGEN, $PRIORITAET, $NAME_ANZEIGE, $KALENDERWOCHE_ANZEIGE, $EVENTEXPORT, $KALENDERBLATT_ANZEIGE);
  }
  echo '</div>';
 }
 else {
  echo '<p><mark class="mark">&#10149;</mark> Keine aktuellen Events vorhanden!</p></div>';
 }
}


// Suche
if (isset($_POST["suchbegriff"])) {
 if ($_POST["suchbegriff"] != "") {
  echo  ANZEIGE . '<div id="aktevents"><mark class="mark">Suche</mark>';
  $select = $db->prepare("SELECT `start`, `ende`, `name`, `event`, `beschreibung`, `prioritaet`, `wiederholung`, `id`
                                        FROM `" . $TABLE_PREFIX . "_kalender` 
                                        WHERE (
                                            `start` LIKE :suchbegriff OR
                                            `name` LIKE :suchbegriff OR
                                            `event` LIKE :suchbegriff OR
                                            `beschreibung` LIKE :suchbegriff)
                                        AND YEAR(`start`) = :jahr
                                        ORDER BY `start` ASC");
  $select->execute([":suchbegriff" => '%' . $_POST["suchbegriff"] . '%',
                              ":jahr" => $_POST["jahr"]]);
  $events = $select->fetchAll();
  $gefunden = $select->rowCount();
  if ($gefunden > 0) {
   echo '<br><mark class="mark">' . $gefunden . ($gefunden == 1 ? ' Ergebnis' : ' Ergebnisse') . ' für das Jahr ' . $_POST["jahr"] . '</mark>';
   foreach ($events as $event) {
    echo anzeigen($event["start"], $event["ende"], $event["name"], $event["event"], $event["beschreibung"], $event["prioritaet"], $event["wiederholung"], $event["id"],
    $DIREKTEINGABE, $PRIORITAET_ANZEIGEN, $PRIORITAET, $NAME_ANZEIGE, $KALENDERWOCHE_ANZEIGE, $EVENTEXPORT, $KALENDERBLATT_ANZEIGE);
   }
   echo '</div>';
  }
  else {
   echo '<p><mark class="mark">&#10149;</mark> Keine Events im Jahr ' . $_POST["jahr"] . ' gefunden die dem Suchbegriff entsprechen!</p></div>';
  }
 }
}


// Wochentage
if (isset($_GET["wochentage"])) {
 echo ANZEIGE . '<div id="aktevents"><mark class="mark">Wochentag: ' . $_GET["wochentag"] . '  ' . $_GET["jahr"] . '</mark><br>';
 $weekday = ['Montag'=>'Monday', 'Dienstag'=>'Tuesday', 'Mittwoch'=>'Wednesday', 'Donnerstag'=>'Thursday', 'Freitag'=>'Friday', 'Samstag'=>'Saturday', 'Sonntag'=>'Sunday'];
 $wt = $weekday[$_GET["wochentag"]];
 $select = $db->prepare("SELECT `start`, `ende`, `name`, `event`, `beschreibung`, `prioritaet`, `wiederholung`, `id`
                                       FROM `" . $TABLE_PREFIX . "_kalender` 
                                       WHERE DAYNAME(`start`) = :wt AND YEAR(`start`) = :jahr
                                       ORDER BY `start` ASC");
 $select->execute([':wt' => $wt,
                           ':jahr' => $_GET["jahr"]]);
 $events = $select->fetchAll();
 if ($select->rowCount() > 0) {
  foreach ($events as $event) {
   echo anzeigen($event["start"], $event["ende"], $event["name"], $event["event"], $event["beschreibung"], $event["prioritaet"], $event["wiederholung"], $event["id"],
    $DIREKTEINGABE, $PRIORITAET_ANZEIGEN, $PRIORITAET, $NAME_ANZEIGE, $KALENDERWOCHE_ANZEIGE, $EVENTEXPORT, $KALENDERBLATT_ANZEIGE);
  }
 }
 else {
  echo '<p><mark class="mark">&#10149;</mark> Keine Einträge!</p></div>';
 }
}


// Kalenderwoche
if (isset($_GET["kalenderwoche"])) {
 echo ANZEIGE . '<div id="aktevents"><mark class="mark">Kalenderwoche ' . $_GET["kwoche"] . '</mark><br>';
 echo 'Montag: ' . date("d.m.Y", strtotime('' . $_GET["jahr"] . '-W' . sprintf("%02s", $_GET["kwoche"]) . '')) . ' bis ' .
        'Sonntag: ' . date("d.m.Y", strtotime('' . $_GET["jahr"] . '-W' . sprintf("%02s", $_GET["kwoche"]) . '-7')) . '<br>';
 $select = $db->prepare("SELECT `start`, `ende`, `name`, `event`, `beschreibung`, `prioritaet`, `wiederholung`, `id`
                                       FROM `" . $TABLE_PREFIX . "_kalender` 
                                       WHERE WEEK(start, 1) = :kw AND YEAR(`start`) = :jahr
                                       ORDER BY `start` ASC");
 $select->execute([':kw' => $_GET["kwoche"],
                           ':jahr' => $_GET["jahr"]]);
 $events = $select->fetchAll();
 if ($select->rowCount() > 0) {
  foreach ($events as $event) {
   echo anzeigen($event["start"], $event["ende"], $event["name"], $event["event"], $event["beschreibung"], $event["prioritaet"], $event["wiederholung"], $event["id"],
    $DIREKTEINGABE, $PRIORITAET_ANZEIGEN, $PRIORITAET, $NAME_ANZEIGE, $KALENDERWOCHE_ANZEIGE, $EVENTEXPORT, $KALENDERBLATT_ANZEIGE);
  }
 }
 else {
  echo '<p><mark class="mark">&#10149;</mark> Keine Einträge!</p></div>';
 }
}


// Kalenderblatt
if (isset($_GET["kalenderblatt"])) include "kalenderblatt.php";
?>
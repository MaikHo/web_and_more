<?php
/*
 *  Event-Kalender (Editor) - kalendereditor.php (utf-8)
 * - https://werner-zenk.de

  Dieses Script wird in der Hoffnung verteilt, dass es nützlich sein wird, aber ohne irgendeine Garantie;
  ohne auch nur die implizierte Gewährleistung der Marktgängigkeit oder Eignung für einen bestimmten Zweck.
  Weitere Informationen finden Sie in der GNU General Public License.
  Siehe Datei: license.txt - http://www.gnu.org/licenses/gpl.html

  Diese Datei und der gesamte "Event-Kalender" ist urheberrechtlich geschützt (c) 2018 Werner Zenk alle Rechte vorbehalten.
  Sie können diese Datei unter den Bedingungen der GNU General Public License frei verwenden und weiter verbreiten.
 */


session_start();
include "verbindung.php";

// Editor: Status-Anzeige in Millisekunden (JavaScript)
$STATUSZEIT = 6000; // 6000

// Länge der Beschreibung
$BESCHREIBUNG_MAX = 30; // 30


// Anmeldung überprüfen
if (isset($_POST["anmeldung"])) {
 if (isset($NAME_PASS[trim($_POST["name"])]) && 
     $NAME_PASS[trim($_POST["name"])] === $_POST["passwort"]) {
   session_regenerate_id();
   $_SESSION["name"] = $_POST["name"];
   header("Location: kalendereditor.php");
 }
}
?>
<!DOCTYPE html>
<html lang="de">
  <meta charset="UTF-8">
 <head>
  <title>Kalender - Editor</title>

  <style>
  body, table {
   font-family: Verdana, Arial, Sans-Serif;
   font-size: 0.92rem;
   cursor: Default;
  }

  a:link, a:visited {
   color: #0078D7;
   text-decoration: None;
  }

  a:hover {
   text-decoration: Underline;
  }

  table {
   border-spacing: 1px;
   background-color: #7A7A7A;
   width: 100%;
  }

  th, td {
   white-space: Nowrap;
   padding: 3px;
  }

  th {
   font-weight: Normal;
  }

  tr:nth-child(even) {
   background-color: Whitesmoke;
  }

  tr:nth-child(odd) {
   background-color: White;
  }

  tr:hover {
   background-color: #EEF7FD;
  }

  td:hover {
   background-color: #DBEDFB;
  }

  th {
   background-color:#E0E0E0;
   font-weight: Normal;
  }

  fieldset {
   background-color: #FAFAFA;
   white-space: Nowrap;
   padding-left: 20px;
   display: Inline-Block;
   margin: 1% 0 0 1.5%;
  }

  legend {
   padding: 0px 10px 0px 10px;
  }

  /* Formularfelder */
  input[type="text"],
  input[type="password"],
  input[type="search"],
  input[type="number"],
  input[type="checkbox"],
  button[type="button"],
  textarea,
  select {
   border: Solid 1px #9A9A9A;
   font-family: Verdana, Arial, Sans-Serif;
   font-size: 0.95rem;
   margin-top: 2px;
   margin-bottom: 2px;
   caret-color: #FF4500;
  }

  input[type="text"]:focus,
  input[type="password"]:focus,
  input[type="search"]:focus,
  input[type="number"]:focus,
  input[type="checkbox"]:focus,
  button[type="button"]:focus,
  textarea:focus,
  select:focus {
   border: Solid 1px #0078D7;
  }

  input[type="button"],
  input[type="submit"],
  button[type="button"] {
   border: Solid 1px #9A9A9A;
   background-color: #E1E1E1;
   font-size: 0.95rem;
  }

  input[type="button"]:hover,
  input[type="submit"]:hover,
  button[type="button"]:hover {
   border: Solid 1px #0078D7;
   background-color: #E5F1FB;
  }

  textarea {
   width: 450px;
   min-width: 450px;
   max-width: 450px;
   height: 200px;
   min-height: 200px;
   max-height: 400px;
   resize: Vertical;
  }

  h2 {
   font-size: 1.11rem;
   font-weight: Normal;
   display: Inline;
  }

  label.login {
   display: Inline-Block;
   width: 7rem;
  }

  label.mark {
   display: Inline-Block;
   width: 100%;
  }

  input[type=radio]:checked + label {
   color: #0078D7;
  }

  input[type=checkbox]:checked + label {
   color: #0078D7;
  }

  .markRow {
    box-shadow: Inset 0px 0px 10px 100px #E5F1FB;
  }

  mark.mark {
   font-weight: Bold;
   background-color: Transparent;
   color: #529EEA;
  }

  span#status {
   color: #0078D7;
   font-size: 0.95rem;
   font-weight: Normal;
   background-color: #EEF7FD;
  }
  </style>

  <script>
  // Ausgewählter Datensatz in die Formularfelder einfügen
  function einfuegen(Tag, Monat, Jahr, Stunde, Minute, Stunde2, Minute2, Event, Prioritaet, Wiederholung, Beschreibung) {
   document.Form.tagAktuell.value = Tag;
   document.Form.monatAktuell.value = Monat;
   document.Form.jahrAktuell.value = Jahr;
   document.Form.stunde.selectedIndex = Stunde;
   document.Form.minute.selectedIndex = Minute;
   document.Form.stunde2.selectedIndex = Stunde2;
   document.Form.minute2.selectedIndex = Minute2;
   document.Form.event.value = Event;
   document.Form.prioritaet.selectedIndex = Prioritaet;
   document.Form.wiederholung.checked = Wiederholung;
   document.Form.beschreibung.value = Beschreibung.replace(new RegExp('~', 'gi'), "\r\n");
   document.Form.aktion.selectedIndex = 1;
   document.Form.event.focus();
  }

  // Tabellenzeile markieren
  function markRow(ID) {
   for (var i = 1; i <= document.getElementsByName("id").length; i++) {
    document.getElementById("r"+i).classList.remove("markRow");
   }
   if (ID != "x") {
    document.getElementById("r"+ID).classList.add("markRow");
   }
  }

  // Sicherheitsabfrage vor dem löschen
  function sicherheit() {
   if (document.getElementById("aktion") != null) {
    if (document.getElementById("aktion").selectedIndex == 2) {
     if (confirm("Eintrag löschen?")) {
      return true;
     }
     else {
      return false;
     }
    }
   }
  }

  function bbCode() {
   if (document.getSelection) {
    var field = document.getElementById("beschreibung");
    var startPos = field.selectionStart;
    var endPos = field.selectionEnd;
    var txt = field.value.substring(startPos, endPos);
    var bs = prompt("BBCode: b, i, s, q, u, mark, color, code", "b");
    if (bs != "" &&
     bs != null) {
     field.value = field.value.substring(0, startPos) +
      "[" + bs + "]" + txt + "[/" + bs + "]" +
      field.value.substring(endPos, field.length);
     field.focus();
    }
   }
  }

  // Statusmeldung anzeigen
  function status(meldung) {
   document.getElementById("status").innerHTML = "&nbsp;" + meldung + "&nbsp;";
   window.setTimeout(function () {
    document.getElementById("status").innerHTML = "";
   },<?=$STATUSZEIT;?>);
  }
  </script>

 </head>
<body>

<form name="Form" action="kalendereditor.php" method="post" onSubmit="return sicherheit();">
<fieldset>
 <legend>
 <h2>Kalender - Editor</h2> 

<?php
// Anmeldung
if (!isset($_SESSION["name"]) ||
     isset($_GET["abmeldung"])) {
 echo ' </legend>
 <label class="login" for="name">&#9711; Name: </label> <input type="text" name="name" id="name" required="required" autocomplete="username"><br>
 <label class="login" for="passwort">&bull;&bull;&bull; Passwort:</label> <input type="password" name="passwort" id="passwort" required="required" autocomplete="current-password"> 
 <input type="submit" name="anmeldung" value="Anmelden">
';

 // Abmeldung
 if (isset($_GET["abmeldung"])) {

  // Session und Cookies löschen
  $_SESSION = [];
  if (ini_get("session.use_cookies")) {
   $params = session_get_cookie_params();
   setcookie(session_name(), '', time() - 42000, $params["path"],
    $params["domain"], $params["secure"], $params["httponly"]);
  }
  session_destroy();
  echo '<p><mark class="mark">&#10004;</mark> Abmeldung erfolgreich.</p>';
 }
 exit('</fieldset></form></body></html>');
}
else {
 echo '&emsp;&#9711; <a href="kalendereditor.php?abmeldung" title="Klicken Sie hier um sich abzumelden.">' . $_SESSION["name"] . '</a> <span id="status"></span></legend>';
}
?>

<?php
// DB-Tabelle bearbeiten
if (isset($_POST["execute"],
            $_SESSION["name"])) {

 // Aktionen ausführen
 if (isset($_POST["aktion"])) {
  if (in_array($_POST["aktion"], ["eintragen", "aktualisieren", "loeschen"])) {
   if (!empty($_POST["tagAktuell"]) &&
       !empty($_POST["monatAktuell"]) &&
       !empty($_POST["jahrAktuell"]) &&
       !empty($_POST["event"])) {

    // Datum überprüfen
    if (checkdate($_POST["monatAktuell"],
                        $_POST["tagAktuell"],
                        $_POST["jahrAktuell"])) {

     // Event eintragen
     if ($_POST["aktion"] == "eintragen") {

      // Datum zusammensetzen
      $start = $_POST["jahrAktuell"] . '-' . $_POST["monatAktuell"] . '-' . $_POST["tagAktuell"] . ' ' . $_POST["stunde"] . ':' . $_POST["minute"] . ':' . "01";
      $ende = $_POST["jahrAktuell"] . '-' . $_POST["monatAktuell"] . '-' . $_POST["tagAktuell"] . ' ' . $_POST["stunde2"] . ':' . $_POST["minute2"];
      $_POST["beschreibung"] = strip_tags(trim($_POST["beschreibung"]), $HTML_TAGS);

      // Jährliche Wiederholung
      $wiederholung = isset($_POST["wiederholung"]) ? 1 : 0;

      // Eintragen
      $insert = $db->prepare("INSERT INTO `" . $TABLE_PREFIX . "_kalender`
                                            SET
                                              `start`= :start,
                                              `ende`= :ende,
                                              `event`= :event,
                                              `beschreibung`= :beschreibung,
                                              `prioritaet`= :prioritaet,
                                              `wiederholung`= :wiederholung,
                                              `name`= :name");
      if ($insert->execute([':start' => $start,
                                     ':event' => strip_tags($_POST["event"]),
                                     ':ende' => $ende,
                                     ':beschreibung' => $_POST["beschreibung"],
                                     ':prioritaet' => $_POST["prioritaet"],
                                     ':wiederholung' => $wiederholung,
                                     ':name' => $_SESSION["name"]])) {
       echo '<script>status("Der Event wurde eingetragen.")</script>';
      }
     }

     // Event aktualisieren
     if ($_POST["aktion"] == "aktualisieren") {

      // Event ausgewählt
      if (isset($_POST["id"])) {

       // Name aus der DB-Tabelle holen
       $select = $db->prepare("SELECT `name`
                                            FROM `" . $TABLE_PREFIX . "_kalender`
                                            WHERE `id` = :id");
       $select->execute([':id'=>$_POST["id"]]);
       $status = $select->fetch();

       // Benutzer überprüfen
       if ($status["name"] === $_SESSION["name"] ||
           $_SESSION["name"] === $NAME) {

        // Datum zusammensetzen
        $start = $_POST["jahrAktuell"] . '-' . $_POST["monatAktuell"] . '-' . $_POST["tagAktuell"] . ' ' . $_POST["stunde"] . ':' . $_POST["minute"] . ':' . "01";
        $ende = $_POST["jahrAktuell"] . '-' . $_POST["monatAktuell"] . '-' . $_POST["tagAktuell"] . ' ' . $_POST["stunde2"] . ':' . $_POST["minute2"];
        $_POST["beschreibung"] = strip_tags(trim($_POST["beschreibung"]), $HTML_TAGS);

        // Jährliche Wiederholung
        $wiederholung = isset($_POST["wiederholung"]) ? 1 : 0;

        // Aktualisieren
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
                                         ':event' => strip_tags($_POST["event"]),
                                         ':ende' => $ende,
                                         ':beschreibung' => $_POST["beschreibung"],
                                         ':prioritaet' => $_POST["prioritaet"],
                                         ':wiederholung' => $wiederholung,
                                         ':id' => $_POST["id"]])) {
         echo '<script>status("Der Eintrag wurde aktualisiert.")</script>';
        }
       }
       else {
        echo '<script>status("Sie können diesen Event nicht aktualisieren!")</script>';
       }
      }
      else {
       echo '<script>status("Es wurde kein Event zum aktualisieren ausgewählt!")</script>';
      }
     }

     // Event löschen
     if ($_POST["aktion"] == "loeschen") {

      // Event ausgewählt
      if (isset($_POST["id"])) {

       // Name aus der DB-Tabelle holen
       $select = $db->prepare("SELECT `name`
                                            FROM `" . $TABLE_PREFIX . "_kalender`
                                            WHERE `id` = :id");
       $select->execute([':id'=>$_POST["id"]]);
       $status = $select->fetch();

       // Berechtigung überprüfen
       if ($status["name"] === $_SESSION["name"] ||
           $_SESSION["name"] === $NAME) {

        // Löschen
        $delete = $db->prepare("DELETE FROM `" . $TABLE_PREFIX . "_kalender`
                                              WHERE
                                              `id` = :id");
        if ($delete->execute([':id'=>$_POST["id"]])) {

         echo '<script>status("Der Event wurde gelöscht.")</script>';
        }
       }
       else {
        echo '<script>status("Sie können diesen Event nicht löschen!")</script>';
       }
      }
      else {
       echo '<script>status("Es wurde kein Event zum löschen ausgewählt!")</script>';
      }
     }
    }
    else {
     echo '<script>status("' . $_POST["tagAktuell"] . '.' . $_POST["monatAktuell"] . '.' . $_POST["jahrAktuell"] . ' - Ungültiges Datum!")</script>';
    }
   }
  }
 }
}


// Formular
// Auswahlliste Uhrzeit
$uhr = '<label>&#9684; <select name="stunde" size="1">';
foreach (range(0, 23) as $stunde) {
 $uhr .= '<option' . ($stunde == 8 ? ' selected="selected"' : '') . '>' . sprintf("%02s", $stunde) . '</option>';
}
$uhr .= '</select></label>';
$uhr .= ' <label>: <select name="minute" size="1">';
foreach (range(0, 59) as $minute) {
 $uhr .= '<option' . ($minute == 0 ? ' selected="selected"' : '') . '>' . sprintf("%02s", $minute) . '</option>';
}
$uhr .= '</select></label>';

// Auswahlliste Uhrzeit 2
$uhr2 = '&emsp; (<label>bis <select name="stunde2" size="1">';
foreach (range(0, 23) as $stunde) {
 $uhr2 .= '<option' . ($stunde == 23 ? ' selected="selected"' : '') . '>' . sprintf("%02s", $stunde) . '</option>';
}
$uhr2 .= '</select></label>';
$uhr2 .= ' <label>: <select name="minute2" size="1">';
foreach (range(0, 59) as $minute) {
 $uhr2 .= '<option' . ($minute == 59 ? ' selected="selected"' : '') . '>' . sprintf("%02s", $minute) . '</option>';
}
$uhr2 .= '</select></label>) Uhr';

$jahr = isset($_POST["jahr"]) ? $_POST["jahr"] : date("Y");
$monat = isset($_POST["monat"]) ? $_POST["monat"] : date("n");
$suche = isset($_POST["suche"]) ? $_POST["suche"] : '';

// Auswahlliste Monat
$auswahlMonat = '<select name="monat" onChange="document.Form.submit()">';
foreach ([1 => 'Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember']
 as $monatszahl => $Monat) {
 $auswahlMonat .= '  <option value="' . $monatszahl . '"' .
  ((isset($_POST["monat"]) ? $_POST["monat"] : date("n")) == $monatszahl ? ' selected="selected"' : "") .
  '>' . $Monat . '</option>';
}
$auswahlMonat .= ' </select>';

// Auswahlliste Jahr
$auswahlJahr = '<select name="jahr" onChange="document.Form.submit()">';
 for ($zaehler = 1971; $zaehler <= 2035; $zaehler++) {
 $auswahlJahr .= '  <option value="' . $zaehler . '"' .
  ((isset($_POST["jahr"]) ? $_POST["jahr"] : date("Y")) == $zaehler ? ' selected="selected"' : "") .
  '>' . $zaehler . '</option>';
}
$auswahlJahr .= ' </select>';

// Auswahlliste Priorität
$auswahlPrioritaet = '<select name="prioritaet"><option value="0">0</option>';
foreach ($PRIORITAET as $zaehler => $prio) {
 $auswahlPrioritaet .= '  <option value="' . $zaehler . '" style="background-color: ' . $prio . ';">' . $zaehler . '</option>';
}
$auswahlPrioritaet .= ' </select>';


echo '<p>' . $auswahlMonat . '&nbsp; ' . $auswahlJahr .
 '&nbsp; <input type="search" name="suche" size="15" value="' . $suche . '" placeholder="Suche ...">
&nbsp; <input type="submit" value="Anzeigen" formnovalidate="formnovalidate" onClick="if(document.Form.aktion.selectedIndex != 0)document.Form.aktion.selectedIndex = 0">
</p>';

// Suchen
if (isset($_POST["suche"]) &&
    $_POST["suche"] != "") {
 $suche = strip_tags(trim($_POST["suche"]));
 $select = $db->query("SELECT `start`, `ende`, `name`, `event`, `beschreibung`, `prioritaet`, `wiederholung`,  `id`
                                       FROM `" . $TABLE_PREFIX . "_kalender`
                                       WHERE (
                                        `start` LIKE '%" . $suche . "%' OR
                                        `name` LIKE '%" . $suche . "%' OR
                                        `event` LIKE '%" . $suche . "%' OR
                                        `beschreibung` LIKE '%" . $suche . "%')");
}
// Monat auslesen
else {
 $select = $db->prepare("SELECT `start`, `ende`, `name`, `event`, `beschreibung`, `prioritaet`, `wiederholung`,  `id`
                                       FROM `" . $TABLE_PREFIX . "_kalender`
                                       WHERE YEAR(`start`) = :jahr AND MONTH(`start`) = :monat
                                             OR (MONTH(`start`) = :monat AND :jahr >= YEAR(`start`) AND `wiederholung` = 1)
                                       ORDER BY `start` ASC");
 $select->execute([':jahr' => $jahr, ':monat' => $monat]);
}
$events = $select->fetchAll(PDO::FETCH_ASSOC);

// Events ausgeben
if ($select->rowCount() > 0) {
 echo '<table>
<tr>
 <th width="10%">&#9782; Datum</th>
 <th width="10%">&#9684; Uhr</th>
  <th>&#9655; Event</th>
  <th>&equiv; Beschreibung</th>
  <th width="10%">&#9711; Name&thinsp;</th>
</tr>';

 foreach ($events as $z => $event) {
  $prioritaet = $event["prioritaet"] > 0 ? '<span style="background-color: ' . $PRIORITAET[$event["prioritaet"]] . '; padding: 0 5px 0 5px;">' . $event["prioritaet"] . '</span>' : '';
  sscanf($event["start"], "%4s-%2s-%2s %2s:%2s", $dbJahr, $dbMonat, $dbTag, $dbStunde, $dbMinute);
  sscanf($event["ende"], "%4s-%2s-%2s %2s:%2s", $a, $b, $c, $dbStunde2, $dbMinute2);
  $ende = substr($event["ende"], 11, 8) != "23:59:00" ? '-' . substr($event["ende"], 11, 5) : '';
  $wiederholung = $event["wiederholung"] == 1 ? true : false;
  $wiederholungANZ = $event["wiederholung"] == 1 ? " &#11118;" : "";
  $beschreibung = strip_tags($event["beschreibung"]);
  $beschreibung = preg_replace('/\[.*?\](.*)\[\/.*?\]/isU', '$1', $beschreibung);
  echo "<tr id=\"r" . ($z+1) . "\">
 <td>" .
  "<input type='radio' name='id' id='n" . $event["id"] . "' value='" . $event["id"] . "'" .
   " onClick='einfuegen(\"" .
                                         $dbTag . '", "' .
                                         $dbMonat . '", "' .
                                         $dbJahr . '", "' .
                                         $dbStunde . '", "' .
                                         $dbMinute . '", "' .
                                         $dbStunde2 . '", "' .
                                         $dbMinute2 . '", "' .
                                         addSlashes($event["event"]) . '", "' .
                                         $event["prioritaet"] . '", "' .
                                         $wiederholung . '", "' .
                                         (str_replace(["\r\n", "\'"], ["~"], addSlashes($event["beschreibung"]))) . "\"); markRow(" . ($z+1) . ")'>" .
     " <label class='mark' for='n" . $event["id"] . "'>" . $dbTag . "." . $dbMonat . "." . $dbJahr . $wiederholungANZ . " &thinsp;</label>
 </td>
 <td> <label class='mark' for='n" . $event["id"] . "'>" . $dbStunde . ":" . $dbMinute . $ende . "</label> </td>
 <td> " . $prioritaet . "<label class='mark' for='n" . $event["id"] . "'>&thinsp;" . $event["event"] . "</label> </td>
 <td> <label class='mark' for='n" . $event["id"] . "'>" .
  mb_substr($beschreibung, 0, $BESCHREIBUNG_MAX) .
 (mb_strlen($beschreibung) > $BESCHREIBUNG_MAX ? ' &hellip;' : '') .
 "&thinsp;</label></td>
 <td> <label class='mark' for='n" . $event["id"] . "'>" . $event["name"] . "</label> </td>
 </tr>";
 }
 echo "</table>";
}
else {
 echo '<script>status("Keine Events gefunden!")</script>';
}
?>

<p>&#9782; 
 <label>Tag: <input type="number" name="tagAktuell" value="<?=date("j");?>" min="1" max="31" pattern="[0-9]{1,2}" required="required" autocomplete="off" style="width: 45px;"></label> &nbsp;
 <label>Monat: <input type="number" name="monatAktuell" value="<?=date("n");?>" min="1" max="12" pattern="[0-9]{1,2}" required="required" autocomplete="off" style="width: 45px;"></label> &nbsp;
 <label>Jahr: <input type="number" name="jahrAktuell" value="<?=date("Y");?>" min="1971" max="2087" pattern="[0-9]{4}" required="required" autocomplete="off" style="width: 65px;"></label>&emsp;
 <input type="checkbox" name="wiederholung" id="wiederholung"> <label for="wiederholung" title="Jährliche Wiederholung (für Geburtstage, Feiertage etc.)">&#11118; Jährl. Wiederholung</label>
 </p>

<p>
 <?=$uhr.$uhr2;?> &emsp;
</p>

<p>
 <label>&#9655; <input type="text" name="event" size="35" maxlength="50" required="required" autocomplete="off" spellcheck="true" placeholder="Event"></label>&nbsp;
 <label>Priorität: <?=$auswahlPrioritaet;?></label>
</p>

<p>
 <textarea name="beschreibung" id="beschreibung" rows="8" cols="40" placeholder="Beschreibung (Optional)"></textarea>
</p>

<p>
 <input type="button" value="BBCode" onClick="bbCode()"> &nbsp;
 <input type="button" value="Abbrechen" onDblClick="markRow('x'); document.Form.reset();" title="Formulareingaben abbrechen, bitte doppelt klicken!"> &emsp; 
 <select name="aktion" id="aktion" size="1" required="required">
  <option value="eintragen">&#10004; Eintragen</option>
  <option value="aktualisieren">&equiv; Aktualisieren</option>
  <option value="loeschen">&#10008; Löschen</option>
 </select> &nbsp;
 <input type="submit" name="execute" value="Ausführen">
 </p>

<p><code>[b][/b]</code>, <code>[i][/i]</code>, <code>[s][/s]</code>, <code>[q][/q]</code>, <code>[u][/u]</code>,<br>
<code>[mark][/mark]</code>, <code>[color=#FF0000][/color]</code>, <code>[code][/code]</code></p>

</fieldset>
</form>

</body>
</html>
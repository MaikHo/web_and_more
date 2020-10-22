<?php
/*
 * Nachrichten - Editor - nachrichten_editor.php (utf-8)
 * https://werner-zenk.de
 */

session_start();
include "verbindung.php";

// Anmeldung überprüfen



session_start();
if (!isset($_SESSION["login"])) {
 header("Location: ../register/anmeldung.php");
 exit;
}
if ($_SESSION["Moderator"] === false){
	echo 'Es fehlt Ihnen an Berechtigung hier etwas zu bearbeiten';
	exit;
}






?>
<!DOCTYPE html>
<html lang="de">
 <head>
  <meta charset="UTF-8">
  <title>Nachrichten Editor</title>

  <style>
  body, table, input, textarea, select {
   font-family: Verdana, Tahoma, Arial, Sans-Serif;
   font-size: 0.95rem;
   cursor: Default;
  }

  :root {
   --main-color: #0078D7;
   --main-border-color: #7A7A7A;
  }

  a:link, a:visited {
   color: var(--main-color);
   text-decoration: None;
  }
  a:hover {
   color: #EE0000;
  }

  fieldset {
   background-color: #FAFAFA;
   white-space: Nowrap;
   padding: 0 10px 5px 5px;
   border: Solid 1px var(--main-border-color);
   border-radius: 4px;
   display: Inline-Block;
  }

  fieldset legend {
   padding: 5px;
  }

  fieldset legend span {
   font-size: 1.2rem;
  }

  input[readonly="readonly"] {
   background-color: #FAFAFA;
   border: Solid 1px #ABADB3;
  }

  textarea {
   font-family: Verdana, Arial, Sans-Serif;
   width: 590px;
   min-width: 600px;
   max-width: 900px;
   height: 250px;
   min-height: 250px;
  }

  span.ok {
   color: #35B000;
   font-weight: Bold;
  }
  span.ko {
   color: #FF0000;
   font-weight: Bold;
  }

  input[type="number"] {
   width: 55px;
  }

  label.login {
   display: Inline-Block;
   width: 5rem;
  }

  input[type=radio]:checked + label,
  input[type=checkbox]:checked + label {
   color: var(--main-color);
  }

  input[type=checkbox]:checked + label#bild_del {
   color: Red;
  }

  code#zeichen {
   font-size: 0.80rem;
  }

  span.symbol {
   font-size: 1.2rem;
   color: var(--main-color);
  }

  span#counter {
   background-color: #F0F0F0;
   padding: 1px 5px 1px 5px;
   border: Solid 1px var(--main-border-color);
  }

  input[type="text"]:required,
   input[type="password"]:required,
   input[type="url"]:required,
   select:required,
   textarea:required {
   background: #EEF7FD;
   border: Solid 1px  var(--main-border-color);
  }

  input[type="text"]:valid,
   input[type="password"]:valid,
   input[type="url"]:valid,
   select:valid,
   textarea:valid {
   background: #FFFFFF;
   border: Solid 1px var(--main-border-color);
  }

  /* Tabelle */
  table {
   width: 100%;
   border-spacing: 1px;
  }

  td.titel {
   font-family: Tahoma;
  }

  td.nachricht {
   font-family: Arial;
  }

  th.bild_url {
   font-family: Arial;
   font-weight: Normal;
   font-size: 0.70rem;
  }

  #tabelle td,
  #tabelle th {
   outline: Solid 1px var(--main-border-color);
  }

  table#tabelle {
   background-color: var(--main-border-color);
  }

  table#tabelle th, table#tabelle td {
   white-space: Nowrap;
   padding: 3px;
  }

  table#tabelle tr:nth-child(even) {
   background-color: Whitesmoke;
  }

  table#tabelle tr:nth-child(odd) {
   background-color: White;
  }

  tr#tabellenkopf > th:nth-child(even) {
   background-color: #C8C8C8;
  }

  tr#tabellenkopf > th:nth-child(odd) {
   background-color: #D7D7D7;
  }

  table#tabelle tr:hover {
   background-color: #EDF9FE;
  }

  table#tabelle td:hover {
   background-color: #DAF1FC;
  }

  table#tabelle th {
   font-weight: Normal;
  }

  .markRow {
   box-shadow: Inset 0px 0px 10px 100px #EDF9FE;
  }

  label.mark {
   display: Inline-Block;
   width: 100%;
  }

  label:hover {
   color: var(--main-color);
  }
  </style>

  <script>
  "use strict";

  // Ausgewählte Nachricht in die Formularfelder einfügen
  function einfuegen(Anzeige, Titel, Autor, Nachricht, Bild, Url, Kategorie, Pin, Datum) {
   document.Form.anzeige.selectedIndex = Anzeige;
   document.Form.titel.value = Titel;
   document.Form.autor.value = Autor;
   document.Form.nachricht.value = Nachricht.replace(new RegExp('~', 'gi'), "\r\n");
   document.Form.bildname.value = Bild;
   document.Form.url.value = Url;
   document.Form.kategorie.value = Kategorie;
   document.Form.pin.checked = Pin;
   document.Form.datum.value = Datum;
   zeichen();
  }

  // URL im neuen Fenster/Tab anzeigen
  function zeigeUrl(url) {
   if ((url.substring(0, 7)) == "http://" ||
       (url.substring(0, 8)) == "https://") {
    window.open(url, "_blank", "width=750, height=800, left=100, top=100, toolbar=1, scrollbars=1");
   }
  }

  // Ein.- und ausblenden
  function blende(ID) {
   document.getElementById(ID).style.display= (document.getElementById(ID).style.display=="none") ? "block" : "none";
  }

  // Neue Elemente in die Auswahlliste einfügen/löschen
  function auswahl() {
   document.Form.aktion.options[0] = new Option("", "");
   document.Form.aktion.options[1] = new Option("\u2731 Nachricht ändern", "aendern", false);
   document.Form.aktion.options[1].style.color="#4DB53F";
   if ("<?=$_SESSION['admin'] == true ? '1' : '0';?>" == "1") {
    document.Form.aktion.options[2] = new Option("\u2718 Nachricht löschen", "loeschen");
    document.Form.aktion.options[2].style.color="#EE0000";
   }
  }
  function auswahlLoeschen() {
   document.Form.aktion.options[0] = new Option(" ", "");
   document.Form.aktion.options[1] = new Option("\u00B6 Nachricht eintragen", "eintragen");
   document.Form.aktion.options[1].style.color="#0000EE";
   document.Form.aktion.options[2] = new Option("", "");
   document.Form.aktion.options[document.Form.aktion.length - 1] = null;
   document.getElementById("zeichen").innerHTML = "";
  }

  // Anzahl Wörter / Zeichen
  function zeichen() {
   var wort = document.Form.nachricht.value.split(" ");
    document.getElementById("zeichen").innerHTML = wort.length  + " / " + (document.Form.nachricht.value.length + 1);
  }

  // Textarea Schriftgröße und Schriftfarbe
  function setTextarea() {
   if (document.Form.fontsize.value.length >= 1) {
    document.Form.nachricht.style.fontSize=document.Form.fontsize.value+"px";
   }
   if (document.Form.fontcolor.value.length == 7) {
    document.Form.nachricht.style.color=document.Form.fontcolor.value;
   }
  }

  // Vorschau-Fenster
  function zeigeVorschau() {
   var text = document.Form.nachricht.value;
   if (text != "") {
    var Fenster = window.open("fensterln", "fenster", "width=800, height=600, left=100, top=100");
    Fenster.document.open("text/html");
    Fenster.document.write("<!DOCTYPE html><html><head><meta charset='UTF-8'>" +
     "<title>Vorschau<\/title><\/head><body><pre style='font-family: Verdana, Sans-Serif;'>" +
     text + "<\/pre><\/body><\/html>");
    Fenster.document.close();
    Fenster.focus();
   }
  }

  function button(taste) {
   if (document.getSelection)  {
    var field = document.Form.nachricht;
    var startPos = field.selectionStart;
    var endPos = field.selectionEnd;
    var txt = field.value.substring(startPos, endPos);
    if (txt != "") {
     field.value = field.value.substring(0, startPos) +
      "<" + taste + ">" + txt + "</" + taste + ">" +
      field.value.substring(endPos, field.length);
    }
    else {
     document.Form.nachricht.value=document.Form.nachricht.value +=
      "<" + taste + "></" + taste + ">";
    }
   }
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
  </script>

 </head>
<body>

<form name="Form" action="mysql_nachrichten.php" method="post" enctype="multipart/form-data" accept-charset="UTF-8" onReset="auswahlLoeschen()">
<fieldset>
 <legend>
 <span>Nachrichten Editor</span> 

<?php
// Anmeldung
if (!isset($_SESSION["autor"]) ) { 
 
}
else {
 echo "&mdash; <a href='mysql_nachrichten.php?abmeldung' title=' " . $_SESSION["autor"] . ", klicke hier um dich abzumelden'>Abmelden</a></legend>";
}
?>

<noscript><p class="ko">Der Editor benötigt JavaScript!</p></noscript>

<?php
// Interne Einstellungen
$statuszeit = 5000; // Status-OK-Anzeige in Millisekunden (JavaScript)
$adminAnzeige = $_SESSION["admin"] != true ? ' style="display:none"' : ''; // Eingabefeld und Admin-Freischaltung
$kurzeNachricht = isset($_POST["kurze_nachricht"]) ? $_POST["kurze_nachricht"] : 40;

// Textarea Schriftgröße und Schriftfarbe
$fontsize = isset($_POST["fontsize"]) ? $_POST["fontsize"] : 12;
$fontcolor = isset($_POST["fontcolor"]) ? $_POST["fontcolor"] : "#000000";
$buttons = buttons($HTML_TAGS);
?>

<?php
// Nachrichten bearbeiten
if (isset($_SESSION["autor"], $_POST["aktion"])) {
 if (in_array($_POST["aktion"], ["eintragen", "aendern", "loeschen"])) {

  // Nachricht eintragen
  if ($_POST["aktion"] == "eintragen") {
   if ($_POST["kategorie"] != "" &&
       $_POST["titel"] != "" &&
       $_POST["nachricht"] != "") {

    // Bild hochladen
    $bildname = "";
    if (is_uploaded_file($_FILES["bild"]["tmp_name"]) &&
        $_FILES["bild"]["error"] === UPLOAD_ERR_OK) {
     $dateiendung = strtolower(substr($_FILES["bild"]["name"], -3, 3));
     list($breite, $hoehe) = getImageSize($_FILES["bild"]["tmp_name"]);
     if (in_array($dateiendung, array_keys($BILD_MIMETYPEN)) &&
         in_array($_FILES["bild"]["type"], $BILD_MIMETYPEN) &&
         $breite > 0 && $hoehe > 0) {
      if ($_FILES["bild"]["size"] <= $BILD_MAXGROESSE) {
       if (move_uploaded_file($_FILES["bild"]["tmp_name"], $BILDPFAD . $_FILES["bild"]["name"])) {
        if (file_exists($BILDPFAD . $_FILES["bild"]["name"])) {
         $bildname = date("Y-m-d_h-i-s") . "." . $dateiendung;
         if (rename($BILDPFAD . $_FILES["bild"]["name"], $BILDPFAD . $bildname)) {
          if ($breite > $MAX_BILD_BREITE || $hoehe > $MAX_BILD_HOEHE) {
           bildAnpassen($BILDPFAD . $bildname, $breite, $hoehe, $dateiendung);
          }
          echo "<p id='BD'><span class='ok'>&#10003;</span> Das Bild &bdquo;<i>" . $bildname . "</i>&rdquo; wurde hoch geladen.</p>" .
           "<script>window.setTimeout(\"blende('BD')\", " . $statuszeit . ");</script>";
         }
        }
        else {
         echo "<p><span class='ko'>&#10008;</span> Das Bild konnte nicht hoch geladen werden!</p>";
        }
       }
      }
     }
     else {
      echo "<p><span class='ko'>&#10008;</span> Dieses Bildformat ist nicht erlaubt!</p>";
     }
    }
    if ($_FILES["bild"]["name"] != "" && $bildname == "") {
     echo "<p><span class='ko'>&#10008;</span> Das Bild ist leider zu groß, maximal: " .
      number_format(($BILD_MAXGROESSE / 1024 / 1024), 1, ",", ".") . " MB!</p>";
    }
 
    $anzeige = $_SESSION["admin"] == true ? $_POST["anzeige"] : $NACHRICHTEN_CHECK;
    $_POST["kategorie"] = delSonderzeichen($_POST["kategorie"]);
    $_POST["titel"] = delSonderzeichen($_POST["titel"]);
    $_POST["nachricht"] = strip_tags($_POST["nachricht"], $HTML_TAGS);
    $_POST["url"] = delSonderzeichen($_POST["url"]);
    $pin = isset($_POST["pin"]) ? 1 : 0;
    $kommando = $verbindung->prepare("INSERT INTO `nachrichten`
                                                              SET
                                                                `kategorie`= :kategorie,
                                                                `anzeige`= :anzeige,
                                                                `titel`= :titel,
                                                                `autor`= :autor,
                                                                `nachricht`= :nachricht,
                                                                `bild` = :bild,
                                                                `url` = :url,
                                                                `pin` = :pin,
                                                                `datum`= NOW()");
    if ($kommando->execute([':kategorie'=>$_POST["kategorie"],
                                           ':anzeige'=>$anzeige,
                                           ':titel'=>$_POST["titel"],
                                           ':autor'=>$_SESSION["autor"],
                                           ':nachricht'=>$_POST["nachricht"],
                                           ':bild'=>$bildname,
                                           ':url'=>$_POST["url"],
                                           ':pin'=>$pin])) {
     echo "<p id='OK'><span class='ok'>&#10003;</span> Die Nachricht &bdquo;<i>" . $_POST["titel"] . "</i>&rdquo; wurde eingetragen.</p>" .
      "<script>window.setTimeout(\"blende('OK')\", " . $statuszeit . ");</script>";
    }
    else {
     echo "<p><span class='ko'>&#10008;</span> Fehler beim schreiben der Nachricht!</p>";
     //print_r($kommando->errorInfo());
    }
   }
  }

  // Nachricht bearbeiten
  if ($_POST["aktion"] == "aendern") {
   if (isset($_POST["id"])) {
    if ($_POST["autor"] == $_SESSION["autor"] ||
        $_SESSION["admin"] == true) {

     // Bild hoch laden
     $bildname = isset($_POST["bild_del"]) ? "" : $_POST["bildname"];
     if (is_uploaded_file($_FILES["bild"]["tmp_name"]) &&
         $_FILES["bild"]["error"] === UPLOAD_ERR_OK) {
      $dateiendung = strtolower(substr($_FILES["bild"]["name"], -3, 3));
      list($breite, $hoehe) = getImageSize($_FILES["bild"]["tmp_name"]);
      if (in_array($dateiendung, array_keys($BILD_MIMETYPEN)) &&
          in_array($_FILES["bild"]["type"], $BILD_MIMETYPEN) &&
          $breite > 0 && $hoehe > 0) {
       if ($_FILES["bild"]["size"] <= $BILD_MAXGROESSE) {
        if ($_POST["bildname"] !="" &&
            file_exists($BILDPFAD . $_POST["bildname"])) {
         unlink($BILDPFAD . $_POST["bildname"]);
        }
        if (move_uploaded_file($_FILES["bild"]["tmp_name"], $BILDPFAD . $_FILES["bild"]["name"])) {
         if (file_exists($BILDPFAD . $_FILES["bild"]["name"])) {
          sscanf($_POST["datum"], "%10s %2s:%2s:%2s", $d, $h, $m, $s);
          $bildname = "${d}_$h-$m-$s." . $dateiendung;
          if (rename($BILDPFAD . $_FILES["bild"]["name"], $BILDPFAD . $bildname)) {
           if ($breite > $MAX_BILD_BREITE || $hoehe > $MAX_BILD_HOEHE) {
            bildAnpassen($BILDPFAD . $bildname, $breite, $hoehe, $dateiendung);
           }
           echo "<p id='BD'><span class='ok'>&#10003;</span> Das Bild &bdquo;<i>" . $bildname . "</i>&rdquo; wurde hoch geladen.</p>" .
            "<script>window.setTimeout(\"blende('BD')\", " . $statuszeit . ");</script>";
          }
         }
         else {
          echo "<p><span class='ko'>&#10008;</span> Das Bild konnte nicht hoch geladen werden!</p>";
         }
        }
       }
      }
      else {
       echo "<p><span class='ko'>&#10008;</span> Dieses Bildformat ist nicht erlaubt!</p>";
      }
     }
     if ($_FILES["bild"]["name"] != "" && $bildname == "") {
      echo "<p><span class='ko'>&#10008;</span> Das Bild ist leider zu groß, maximal: " .
       number_format(($BILD_MAXGROESSE / 1024 / 1024), 1, ",", ".") . " MB!</p>";
     }

     $anzeige = $_SESSION["admin"] == true ? $_POST["anzeige"] : $NACHRICHTEN_CHECK;
     $_POST["kategorie"] = delSonderzeichen($_POST["kategorie"]);
     $_POST["titel"] = delSonderzeichen($_POST["titel"]);
     $_POST["nachricht"] = strip_tags($_POST["nachricht"], $HTML_TAGS);
     $_POST["url"] = delSonderzeichen($_POST["url"]);
     $pin = isset($_POST["pin"]) ? 1 : 0;
     $kommando = $verbindung->prepare("UPDATE `nachrichten`
                                                              SET 
                                                              `kategorie`= :kategorie,
                                                                 `anzeige` = :anzeige,
                                                                 `titel` = :titel,
                                                                 `autor` = :autor,
                                                                 `nachricht` = :nachricht,
                                                                 `bild` = :bild,
                                                                 `url` = :url,
                                                                 `pin` = :pin
                                                              WHERE
                                                                 `id` = :id");
     if ($kommando->execute([':kategorie'=>$_POST["kategorie"],
                                             ':anzeige'=>$anzeige,
                                             ':titel'=>$_POST["titel"],
                                             ':autor'=>$_POST["autor"],
                                             ':nachricht'=>$_POST["nachricht"],
                                             ':bild'=>$bildname,
                                             ':url'=>$_POST["url"],
                                             ':pin'=>$pin,
                                             ':id'=>$_POST["id"]])) {
      echo "<p id='OK'><span class='ok'>&#10003;</span> Die Nachricht &bdquo;<i>" . $_POST["titel"] . "</i>&rdquo; wurde geändert.</p>" .
       "<script>window.setTimeout(\"blende('OK')\", " . $statuszeit . ");</script>";

      // Bild löschen
      if (isset($_POST["bild_del"])) {
       if (file_exists($BILDPFAD . $_POST["bildname"])) {
        if (unlink($BILDPFAD . $_POST["bildname"])) {
         echo "<p id='BD'><span class='ok'>&#10003;</span> Das Bild &bdquo;<i>" . $_POST["bildname"] . "</i>&rdquo; wurde gelöscht.</p>" .
          "<script>window.setTimeout(\"blende('BD')\", " . $statuszeit . ");</script>";
        }
        else {
         echo "<p><span class='ko'>&#10008;</span> Das Bild konnte nicht gelöscht werden!</p>";
        }
       }
       else {
        echo "<p><span class='ko'>&#10008;</span> Das Bild ist nicht vorhanden!</p>";
       }
      }
     }
     else {
      echo "<p><span class='ko'>&#10008;</span> Fehler beim bearbeiten der Nachricht!</p>";
      //print_r($kommando->errorInfo());
     }
    }
    else {
     echo "<p><span class='ko'>&#10008;</span> Sie können diese Nachricht nicht bearbeiten!</p>";
    }
   }
   else {
    echo "<p><span class='ko'>&#10008;</span> Es wurde keine Nachricht zum bearbeiten ausgewählt!</p>";
   }
  }

  // Nachricht löschen
  if ($_POST["aktion"] == "loeschen") {
   if (isset($_POST["id"])) {
    if ($_POST["autor"] == $_SESSION["autor"] ||
        $_SESSION["admin"] == true) {
     $kommando = $verbindung->prepare("DELETE FROM `nachrichten`
                                                            WHERE `id` = :id");
     if ($kommando->execute([':id'=>$_POST["id"]])) {
      echo "<p id='OK'><span class='ok'>&#10003;</span> Die Nachricht &bdquo;<i>" . $_POST["titel"] . "</i>&rdquo; wurde gelöscht.</p>" .
       "<script>window.setTimeout(\"blende('OK')\", " . $statuszeit . ");</script>";

      // Bild löschen
      if ($_POST["bildname"] != "") {
       if (file_exists($BILDPFAD . $_POST["bildname"])) {
        unlink($BILDPFAD . $_POST["bildname"]);
       }
      }
     }
     else {
      echo "<p><span class='ko'>&#10008;</span> Fehler beim löschen der Nachricht!</p>";
      //print_r($kommando->errorInfo());
     }
    }
    else {
     echo "<p><span class='ko'>&#10008;</span> Sie können diese Nachricht nicht löschen!</p>";
    }
   }
   else {
    echo "<p><span class='ko'>&#10008;</span> Es wurde keine Nachricht zum löschen ausgewählt!</p>";
   }
  }
 }
 if (!is_writable($BILDPFAD)) echo "<p><span class='ko'>&#10008;</span> Das Verzeichnis: '" . $BILDPFAD . "' besitzt keine Schreibrechte!</p>";
}


// Anzeige und Anzahl der Nachrichten
$ergebnis = $verbindung->query("SELECT `kategorie` FROM `nachrichten`");
$kategorien = $ergebnis->fetchAll(PDO::FETCH_COLUMN, 0);
$anzahlNachrichten = count($kategorien);
$start = isset($_POST["start"]) ? $_POST["start"] : 1;
$start = $start > $anzahlNachrichten || $start < 1 ? 1 : $start;
$anzahl = isset($_POST["anzahl"]) ? $_POST["anzahl"] : 5;
$anzahl = $anzahl > $anzahlNachrichten || $anzahl < 1 ? $anzahlNachrichten : $anzahl;

// Filter - Suchbegriff
$suche = isset($_POST["suche"]) ? $_POST["suche"] : "";
$matchCase = isset($_POST["matchCase"]) ? "BINARY " : "";
$matchCaseCheck = isset($_POST["matchCase"]) ? ' checked="checked"' : '';

$wasSuchen = ($suche != "") ? 
 " WHERE (" . $matchCase . "`titel` LIKE '%" . $suche . "%' OR 
    " . $matchCase . "`kategorie` LIKE '%" . $suche . "%' OR 
    " . $matchCase . "`autor` LIKE '%" . $suche . "%' OR 
    " . $matchCase . "`nachricht` LIKE '%" . $suche . "%' OR 
    " . $matchCase . "`url` LIKE '%" . $suche . "%' OR 
    `datum` LIKE '%" . $suche . "%')" :
  // Filter - Kategorie
 (isset($_POST["kate"]) && $_POST["kate"] != "" ?
   " WHERE `kategorie` = '" . $_POST["kate"] . "'" :
   "");

// Auswahlliste - Sortierung nach Spalte
$spalte = isset($_POST["spalte"]) ? $_POST["spalte"] : "datum";
$auswahllisteSpalte = "<select name='spalte' onChange='document.Form.submit()'>";
foreach (["anzeige", "autor", "bild", "datum", "kategorie", "nachricht", "pin", "titel", "url"] as $element) {
 $auswahllisteSpalte .= "  <option value='" . $element . "'" .
  ((isset($_POST["spalte"]) ? $_POST["spalte"] : "datum") == $element ? " selected='selected'" : "") .
  ">" . ucfirst($element) . "</option>";
}
$auswahllisteSpalte .= " </select>";

// Auswahlliste und Datenliste Kategorie
$datenlisteKategorie = "<datalist id='list_kategorie'>";
$auswahllisteKategorie = "<select name='kate' onChange='document.Form.submit()'><option></option>";
$kategorien = array_unique($kategorien);
sort($kategorien);
foreach ($kategorien as $kategorie) {
 $datenlisteKategorie .= "  <option>" . $kategorie . "</option>";
 $auswahllisteKategorie .= "  <option" .
  ((isset($_POST["kate"]) ? $_POST["kate"] : "") == $kategorie ? " selected='selected'" : "") . ">" . $kategorie . "</option>";
}
$datenlisteKategorie .= "</datalist>";
$auswahllisteKategorie .= "</select>";

// Spalten aufsteigend/absteigend wechseln
$spalteWechseln = isset($_POST["spalte_wechseln"]) ? "ASC" : "DESC";
$spalteWechselnText = isset($_POST["spalte_wechseln"]) ? "&#9650;" : "&#9660;";
$spalteWechselnCheck = isset($_POST["spalte_wechseln"]) ? ' checked="checked"' : '';

// Nachrichten auslesen
$kommando = $verbindung->query("SELECT `id`, `kategorie`, `anzeige`, `titel`, `autor`, `nachricht`, `bild`, `url`, `pin`, `datum`
                                                    FROM `nachrichten`" . $wasSuchen . "
                                                    ORDER BY `" . $spalte . "` " . $spalteWechseln . "
                                                    LIMIT " . ($start - 1) . ", " . $anzahl);
$nachrichten = $kommando->fetchAll(PDO::FETCH_OBJ);

if ($anzahlNachrichten > 0) {
// Formular ausgeben
 echo "<p>
<label title='Starte von Nachricht'>Start: <input type='number' name='start' value='" . $start . "' min='1' max='" .
 $anzahlNachrichten . "' size='3' autocomplete='off'></label>&nbsp;
<label title='Anzahl der Nachrichten'>Anzahl: <input type='number' name='anzahl' value='" . $anzahl .
  "' min='1' max='" . $anzahlNachrichten . "' size='3' autocomplete='off'></label> / <span id='counter' title='Anzahl aller Nachrichten'>" . $anzahlNachrichten . "</span>&nbsp;
<label title='Sortierung nach Spalte'>Sortierung: " . $auswahllisteSpalte . "</label>&nbsp;
<label title='Sortierung aufsteigend oder absteigend'><input type='checkbox' name='spalte_wechseln' " . $spalteWechselnCheck .
 " onClick='document.Form.submit()'> <span class='symbol'>" . $spalteWechselnText . "</span></label>&nbsp;
 <label title='Kategorie filtern'>Kategorie: " . $auswahllisteKategorie . "</label>&nbsp;
<label title='Suche nach: Titel, Kategorie, URL, Beschreibung &#13;oder Datum (JJJJ-MM-TT)'><input type='search' name='suche' value='" . $suche . "' size='20' placeholder='Suche ...'></label>
 <label title='Genauer Suchbegriff'><input type='checkbox' name='matchCase' " . $matchCaseCheck . " onClick='document.Form.submit()'> <span class='symbol'>&#9677;</span></label>&nbsp;
<input type='submit' value='Anzeigen' formnovalidate='formnovalidate'>
</p>";

// Tabelle ausgeben
echo "<table id='tabelle'>
 <tr id='tabellenkopf'>
  <th width='2%'> # </th>
  <th> Kategorie </th>
  <th> Titel </th>
  <th> Autor </th>
  <th> <label title='Textlänge der Nachrichten'>Nachricht: <input type='text' name='kurze_nachricht' value='" . $kurzeNachricht .
   "' size='3'> <span style='font-weight: normal;'>Zeichen</span></label> </th>
  <th> Bild / URL </th>
  <th width='1%' title='Angepinnt'> &#9872; </th>
  <th width='7%'> Datum </th>
 </tr>
";

 // Nachrichten ausgeben
 $zaehler = $start;
 foreach ($nachrichten as $z => $nachricht) {
  // Datumsformat umwandeln
  sscanf($nachricht->datum, "%4s-%2s-%2s %5s", $jahr, $monat, $tag, $uhrzeit);
  // Nachricht angepinnt
  $pin = $nachricht->pin == "1" ? '&#9872;' : '&emsp;';
  $pon = $nachricht->pin == "1" ? true : false;
  // Bildgröße und Dateigröße ermitteln
  $bildlink = "";
  if ($nachricht->bild <> '' && file_exists($BILDPFAD . $nachricht->bild)) {
   list($breite, $hoehe) = getImageSize($BILDPFAD . $nachricht->bild);
   $size = filesize($BILDPFAD . $nachricht->bild);
   // Link zum Bild
   $bildlink = "<a href='" . $BILDPFAD . $nachricht->bild . "' target='_blank' title='" . $nachricht->bild .
    "\n" . $breite . " x " . $hoehe . " Pixel - " . (round($size / 1024, 1)) . " KB'>&#9619;&#9619;</a><br>";
  }
  // Zähler und Kategorie ausgeben
  echo "<tr id=\"r" . ($z+1) . "\">
 <td> " . $zaehler . " </td>
 <td> <label for='n" . $nachricht->id . "'>" . $nachricht->kategorie . "</label> </td>
 <td class='titel'>" .
  // Radiobutton ausgeben
  "<input type='radio' name='id' id='n" . $nachricht->id . "' value='" . $nachricht->id . "'" .
   // Ausgewählte Nachricht in die Formularfelder einfügen per JavaScript
   // Anführungszeichen beachten!
   " onClick='einfuegen(\"" .
                                       $nachricht->anzeige . '", "' .
                                       addSlashes($nachricht->titel) . '", "' .
                                       addSlashes($nachricht->autor) . '", "' .
                                       (str_replace(["\r\n", "\'"], ["~",], addSlashes($nachricht->nachricht))) . '", "' .
                                       $nachricht->bild . '", "' .
                                       $nachricht->url . '", "' .
                                       $nachricht->kategorie . '", "' .
                                       $pon . '", "' .
                                       $nachricht->datum . "\"); auswahl(); markRow(" . ($z+1) . ");'><label for='n" . $nachricht->id . "'>" .
  // Titel - Zeilenumbruch nach 35 Zeichen
  wordwrap($nachricht->titel, 35, "<br>") .
  // Nachricht Frei/Gesperrt - Symbol ausgeben
  " <span style='font-weight: bold; color:" . ($nachricht->anzeige == 0 ?
  "#EE0000'>&empty;" : "#3DCE00'>&#10003;") . "</span>&thinsp;</label>
 </td>" .
 // Autor ausgeben
 "<td> <label class='mark' for='n" . $nachricht->id . "'>" . $nachricht->autor . "</label> </td>
 <td class='nachricht'> <label class='mark' for='n" . $nachricht->id . "'>" .
  // Nachricht ohne HTML-Elemente ausgeben - Zeilenumbruch nach 65 Zeichen
  wordwrap(mb_substr(strip_tags($nachricht->nachricht), 0, $kurzeNachricht), 65, "<br>") .
 // Bei einer zu langen Nachricht Punkte (...) hinzufügen
 (strlen($nachricht->nachricht) > $kurzeNachricht ? " &hellip;" : "") . "</label></td>" .
 // Bildlink ausgeben
 "<th class='bild_url'> <label class='mark' for='n" . $nachricht->id . "'>" . $bildlink .
  // Externer Link zur URL ausgeben
  ($nachricht->url !='' ? '<a href="' . $nachricht->url . '" target="_blank">' .
  // "http://" und "www." im Namen entfernen
  str_replace(["http://", "https://", "www."], "", $nachricht->url) . '</a>' : '') . "</label> </th>" .
  "<th><label class='mark' for='n" . $nachricht->id . "'>" . $pin . "</label></th>" . 
  // Datum und Uhrzeit ausgeben
 "<td> <label class='mark' for='n" . $nachricht->id . "'><code title='" . $uhrzeit . " Uhr'>" . $tag . "." . $monat . "." . $jahr .
 "</code></label> </td>
</tr>
";
  $zaehler++;
 }
 echo "</table>";
}
else {
 echo "<p><span class='ko'>&#10008;</span> Es sind keine Nachrichten in der Datenbank vorhanden!</p>";
}
if (count($nachrichten) < 1) {
 echo "<p><span class='ko'>&#10008;</span> Es konnten keine Nachrichten zu dem Suchbegriff gefunden werden!</p>";
}
?>
</fieldset>

<table>
<tr>
<td rowspan="2" style="vertical-align: top; width: 500px;">

<!-- Nachricht eintragen oder bearbeiten -->
<fieldset>
 <legend>Nachricht eintragen oder bearbeiten</legend>

 <!-- Kategorie -->
 <p>
 <label>Kategorie: <input type="text" name="kategorie"
  size="20" maxlength="50" list="list_kategorie" required="required" autocomplete="off" style="width: 160px;"></label> &nbsp;
 <?=$datenlisteKategorie;?>

  <!-- Titel -->
 <label>Titel: <input type="text" name="titel"
  size="45" maxlength="60" required="required" style="width: 270px;"></label>
 </p>

 <!-- Nachricht -->
 <label for="nt">Nachricht: </label> &emsp;
 <!-- Wörter / Zeichen -->
 <code id="zeichen" title="Wörter / Zeichen"></code><br>
 <textarea name="nachricht" id="nt" rows="14" cols="80" maxlength="<?=$MAX_NACHRICHT;?>"
  onKeyDown="zeichen()" required="required"></textarea><br>

 <!-- Funktionen -->
 <a href="javascript:blende('Funktionen')"><small>Funktionen</small></a>
 <blockquote id="Funktionen" style="display: none;">
  <?=$buttons;?>
  <input type="button" onclick="zeigeVorschau()" value="Vorschau" title="Nachricht in der Vorschau anzeigen">&emsp;
  <label title="Schriftgröße im Eingabefeld einstellen">Schriftgröße: <input type="text" name="fontsize" size="2" value="<?=$fontsize;?>"
   maxlength="2" onKeyUp="setTextarea()"></label>px &emsp;
  <label for="sf" title="Schriftfarbe im Eingabefeld einstellen">Schriftfarbe:</label> <input type="color" name="fontcolor"id="sf" size="7" value="<?=$fontcolor;?>"
   maxlength="6" onChange="setTextarea()" style="text-transform: uppercase; letter-spacing: 1px;">
  <script>
  // Schriftgröße und Schriftfarbe im Textarea setzen.
  setTextarea();
  </script>
 </blockquote>
</fieldset>
</td>

<td style="vertical-align: top;">
<fieldset style="margin-left: 15px;">
 <legend>Optionen</legend>
 <!-- Autor -->
 <p>
 <label title="Autor der Nachricht">Autor: <input type="text" name="autor" value="<?=$_SESSION["autor"];?>" size="35" readonly="readonly"></label>
 </p>

 <!-- Sperre -->
 <p<?=$adminAnzeige;?>>
 <label title="Anzeige der Nachricht sperren oder freischalten">Anzeige: 
 <select name="anzeige">
  <option value="0" style="color:#EE0000">&empty; Sperren</option>
  <option value="1" selected="selected" style="color:#4DB53F">&#10003; Freischalten</option>
 </select>
 </label>&emsp;

 <!-- Angepinnt -->
 <input type="checkbox" name="pin" id="lbl_pin"> <label title="Diese Nachricht oben anpinnen!" for="lbl_pin">&#9872; Angepinnt</label>
</p>

 <!-- Bild -->
 <p>
 <input type="hidden" name="MAX_FILE_SIZE" value="<?=$BILD_MAXGROESSE; ?>">

 <label for="lb_bild" title="Bild zum hochladen auswählen">Bild:</label>

 <input type="text" name="bildname" id="lb_bild" size="25" readonly="readonly"> &nbsp; 
 <input type="checkbox" name="bild_del" id="lbl_bild_del">
 <label for="lbl_bild_del" id="bild_del" title="Bild aus der Datenbank und vom Verzeichnis löschen">Bild löschen</label><br>
 <input type="file" size="30" name="bild" id="Datei" accept="image/*"></label><br>
 <small style="font-size: 0.60rem;">Dateiformat: <?=implode(", ", array_unique(array_keys($BILD_MIMETYPEN)));?> - 
 Dateigröße max.: <?=number_format(($BILD_MAXGROESSE / 1024 / 1024), 2, ",", ".");?> MB [<?=$MAX_BILD_BREITE . 'x' . $MAX_BILD_HOEHE;?>]</small><br>
 </p>

 <!--URL -->
 <p>
 <label>URL: <input type="url" name="url" size="35" maxlength="100" placeholder="http://"></label>
 <input type="button" onclick="zeigeUrl(document.Form.url.value);" title="URL anzeigen" value="&#10138;">
 <input type="hidden" name="datum" readonly="readonly">
 </p>

</fieldset>

</td>
</tr>

<tr>
<td style="vertical-align: bottom;">
<!-- Absenden -->
<fieldset style="background-color:#EFEFEF; text-align:center; margin-left: 15px;">
 <legend>Absenden</legend>
 <!-- Auswahl -->
 <div>
 <select name="aktion" required="required">
  <option selected="selected"></option>
  <option value="eintragen" style="color:#0000EE">&para; Nachricht eintragen</option>
 </select> 
 <!-- Ausführen -->
 <input type="submit" name="action" value="&#10149;  Ausführen"><br><br>
  <!-- Eingaben löschen -->
  <input type="button" value="&xutri; Eingaben löschen" onDblClick="markRow('x'); auswahlLoeschen();document.Form.reset();"
   title="Um die Eingaben zu löschen, Bitte doppelt klicken!">
 </div>
</fieldset>

</td>
</tr>
</table>

</form>

</body>
</html>

<?php
// Funktionen

// Bild nach dem hochladen anpassen
function bildAnpassen($bildpfad, $breite, $hoehe, $dateiendung) {
 global $MAX_BILD_BREITE, $MAX_BILD_HOEHE, $WASSERZEICHEN,
  $WASSERZEICHEN_BILD, $WASSERZEICHEN_TRAN;

 // Bild erstellen
 switch ($dateiendung) {
  case "gif": $bild = imageCreateFromGIF($bildpfad); break;
  case "jpg": $bild = imageCreateFromJPEG($bildpfad); break;
  case "png": $bild = imageCreateFromPNG($bildpfad); break;
  default: $bild = imageCreateFromJPEG($bildpfad);
 }

 // Größe berechnen
 if ($MAX_BILD_BREITE && ($breite < $hoehe)) {
 $MAX_BILD_BREITE = ceil(($MAX_BILD_HOEHE / $hoehe) * $breite);
 }
 else {
  $MAX_BILD_HOEHE = ceil(($MAX_BILD_BREITE / $breite) * $hoehe);
 }

 // Bild kopieren
 $bildKopie = imageCreateTruecolor($MAX_BILD_BREITE, $MAX_BILD_HOEHE);
 imageCopyResampled($bildKopie, $bild, 0, 0, 0, 0, $MAX_BILD_BREITE, $MAX_BILD_HOEHE, $breite, $hoehe);

 // Wasserzeichen (Bild, PNG-Format) hinzufügen
 if ($WASSERZEICHEN == "ja") {
  $wasserzeichen = imageCreateFromPng($WASSERZEICHEN_BILD);
  $wzB = imageSX($wasserzeichen);
  $wzH = imageSY($wasserzeichen);
  $x = $MAX_BILD_BREITE - $wzB - 10;
  $y = $MAX_BILD_HOEHE - $wzH - 10;
  imageCopyMerge($bildKopie, $wasserzeichen, $x, $y, 0, 0, $wzB, $wzH, $WASSERZEICHEN_TRAN);
 }

 // Bild speichern
 switch ($dateiendung) {
  case "gif": imageGIF($bildKopie, $bildpfad); break;
  case "jpg": imageJPEG($bildKopie, $bildpfad, 100); break;
  case "png": imagePNG($bildKopie, $bildpfad); break;
  default: imageJPEG($bildKopie, $bildpfad);
 }
 imageDestroy($bildKopie);
}

// Zeichen entfernen
function delSonderzeichen($string) {
 return strip_tags(trim(str_replace(["'", '"', '\\', '\n', '\r', '\t', ], "", $string)));
}

// Buttons hinzufügen
function buttons($HTML_TAGS) {
 $array = explode(",", $HTML_TAGS);
 $var = "";
 foreach ($array as $zaehler => $element) {
  if ($element != "") {
   $button = str_replace(["<", ">"], "", trim($element));
   $var .= '<input type="button" value="<' . $button . '>" onClick="button(\'' . $button . '\')" title="<' . $button . '>-Element einfügen"> ' .
    ($zaehler == 7 ? '<br>' : '');
  }
 }
 return $var != "" ? $var . "<br><br>" : "";
}
?>
<?php
/*
 * notizblock.php (utf-8)
 * - https://werner-zenk.de
 */
session_start();
if (!isset($_SESSION["login"])) {
 header("Location: ../index.php");
 exit; 
}

// Pfad zur Datenbank
//$datenbank = "notizen.sqt";
$datenbank = $_SESSION["benutzer"].".sqt";
// Datenbank-Datei erstellen
if (!file_exists($datenbank)) {
 $db = new PDO('sqlite:' . $datenbank);
 $db->exec("CREATE TABLE notizen( id INTEGER PRIMARY KEY, notiz TEXT, datum DATETIME)");
}
else {
 // Verbindung
 if (!isset($db)) {
  $db = new PDO('sqlite:' . $datenbank);
 }
}

// Zeitzone setzen
// http://de3.php.net/manual/de/timezones.europe.php
date_default_timezone_set("Europe/Berlin");

// Start (Notizen ausgeben)
if (isset($_GET["start"])) {
 // Notizen auslesen
 $select = $db->query("SELECT `id`, `notiz`, `datum` FROM `notizen` ORDER BY `id`  ASC");
 $notizen = $select->fetchAll(PDO::FETCH_ASSOC);
 // Notizen ausgeben
 foreach ($notizen as $z => $notiz) {
  $datum = sscanf($notiz["datum"], "%4s-%2s-%2s %5s", $jahr, $monat, $tag, $uhrzeit);
  $datum = $tag . '.' . $monat . '.' . $jahr . '&nbsp; ' . $uhrzeit . ' Uhr';
  // Zeilenumbrüche ersetzen (Kontextwechsel zu JavaScript)
  $text = str_replace(["\r\n", "\'"], ["~",], addSlashes($notiz["notiz"]));
  $note = preg_replace_callback('#(( |^)(((ftp|http|https|)://)|www.)\S+)#mi', 'makeLink', $notiz["notiz"]);
  $note = nl2br($note);
  echo '<div id="r' . $notiz["id"] . '" class="notiz">
   <label title="Notiz auswählen" class="mark"><input type="radio" name="id" value="' . $notiz["id"] . '" ' .
  ' onClick="einfuegen(\'' . $text . '\',\'' . $datum . '\'); aendern(); markRow(\'' . ($z+1) . '\');"' . '> ' .
  $note . '</label></div>';
 }
 exit; // Keine weiteren Daten an den Webserver senden
}

// Formular wurde gesendet
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Fallunterscheidung je nach gewählter Option
  switch ($_POST["option"]) :

   // Eintragen
   case 'insert':
     // Notiz ist nicht leer
     if (!empty($_POST["notiz"])) {
       // Query vorbereiten ...
       $insert = $db->prepare("INSERT INTO `notizen` (`notiz`, `datum`) VALUES (:notiz, :datum)");
      // ... und ausführen
      if ($insert->execute([":notiz" => quotationMarks($_POST["notiz"]),
                                     ":datum" => date("Y-m-d H:i:s")])) {
       echo '<span class="status_ok">&#10004;</span> Die Notiz wurde eingetragen.';
      }
      else {
       echo '<span class="status_ko">&#10008;</span> Fehler beim eintragen!';
      }
     }
     else {
      echo '<span class="status_ko">&#10008;</span> Die Notiz fehlt!';
     }
   break;

   // Ändern
   case 'edit':
   // Notiz wurde ausgewählt
   if (isset($_POST["id"])) {
     // Notiz ist nicht leer
     if (!empty($_POST["notiz"])) {
       // Query vorbereiten ...
       $update = $db->prepare("UPDATE `notizen` SET `notiz` = :notiz, `datum` = :datum WHERE `id`= :id");
      // ... und ausführen
      if ($update->execute([":notiz" => quotationMarks($_POST["notiz"]),
                                        ":datum" => date("Y-m-d H:i:s"),
                                        ":id" => $_POST["id"]])) {
       echo '<span class="status_ok">&#10004;</span> Die Notiz wurde aktualisiert.';
      }
      else {
       echo '<span class="status_ko">&#10008;</span> Fehler beim ändern!';
      }
     }
     else {
      echo '<span class="status_ko">&#10008;</span> Die Notiz fehlt!';
     }
   }
   else {
    echo '<span class="status_ko">&#10008;</span> Keine Notiz zum ändern gewählt!';
   }
   break;

   // Löschen
   case 'delete':
   // Notiz wurde ausgewählt
   if (isset($_POST["id"])) {
    // Query vorbereiten ...
    $delete = $db->prepare("DELETE FROM `notizen` WHERE `id`= :id");
    // ... und ausführen
    if ($delete->execute([":id"=>$_POST["id"]])) {
     echo '<span class="status_ok">&#10004;</span> Notiz wurde gelöscht.';
    }
    else {
     echo '<span class="status_ko">&#10008;</span> Fehler beim löschen!';
    }
   }
   else {
    echo '<span class="status_ko">&#10008;</span> Keine Notiz zum löschen gewählt!';
   }
   break;

  endswitch;

 exit; // Keine weiteren Daten an den Webserver senden
}

// Funktion: Link erstellen
function makeLink($hit) {
 $url = trim($hit[1]);
 if ((substr($url, 0, 7) != 'http://') && (substr($url, 0, 8) != 'https://') && (substr($url, 0, 6) != 'ftp://'))
  $url = "http://" . $url;
 return ' <a class="notizlink" href="' . $url . '" target="_blank">' . $url . '</a>';
}

function quotationMarks($str) {
 return str_replace(["'", '"'], "", $str);
}
?>
<!DOCTYPE html>
<html lang="de">
 <head>
  <meta charset="UTF-8">
  <title>Notizen</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <script>
  // Das XMLHttpRequest-Objekt setzen
  var xhr = new XMLHttpRequest();

  // Diese Funktion beim laden der Seite aufrufen
  window.addEventListener("load", function () {
  // Dem HTML-Button (id="submit") den Event: "click" zuweisen
  // dieser ruft dann (beim klicken) die Funktion: senden() auf.
  document.getElementById("submit").addEventListener("click", senden);
  // Dem HTML-Button (id="abbrechen") den Event: "dblclick" zuweisen
  // dieser ruft dann (beim doppelklicken) diese Funktion auf.
  document.getElementById("abbrechen").addEventListener("dblclick", function () {
   document.getElementsByName("Form")[0].reset();
   document.getElementById("status").innerHTML = "&emsp;";
   markRow("x");
  });
   // Funktion start() aufrufen
   start();
  });

  // Notizen ausgeben
  function start() {
   // Die Anfrage initialisieren (Methode GET, URL + Querystring)
   xhr.open("GET", document.URL + "?start");
   // Die Anfrage senden
   xhr.send(null);
   // Auf eine Antwort (vom Webserver) warten
   xhr.onreadystatechange = function() {
    // Status überprüfen
    if (xhr.readyState === XMLHttpRequest.DONE &&
        xhr.status == 200) {
     // Antwort ausgeben
     document.getElementById("notizen").innerHTML = xhr.responseText;
    }
   }
  }

  // Formulardaten senden
  function senden() {
    // Die Daten aus dem Formular holen
    var daten = new FormData(document.getElementsByName("Form")[0]);
    // Die Anfrage initialisieren (Methode POST, Url)
    xhr.open("POST", "notizblock.php");
    // Die Daten senden
    xhr.send(daten);
    // Auf eine Antwort (vom Webserver) warten
    xhr.onreadystatechange = function() {
     // Status überprüfen
     if (xhr.readyState === XMLHttpRequest.DONE &&
         xhr.status == 200) {
      // Antwort ist positiv
      if (xhr.responseText.startsWith('<span class="status_ok">')) {
        // Formular zurücksetzen
       document.getElementsByName("Form")[0].reset();
       // Antwort ausgeben
       document.getElementById("status").innerHTML = xhr.responseText;
       // Verzögerungszeit um die Antwort zu löschen
       window.setTimeout(function () {
        document.getElementById("status").innerHTML = "&emsp;";
       }, 3000); // Millisekunden
       // Funktion start() aufrufen
       start();
      }
      // Antwort ist negativ
      else {
       // Antwort ausgeben
       document.getElementById("status").innerHTML = xhr.responseText;
       // Verzögerungszeit um die Antwort zu löschen
       window.setTimeout(function () {
        document.getElementById("status").innerHTML = "&emsp;";
       }, 5000);
      }
     }
    }
  }

  // Optionsfeld ändern
  function aendern() {
   document.getElementsByName("Form")[0].option[1].checked = true;
  }

  // Formularfelder mit den übergeben Werten auswählen/befüllen
  function einfuegen(Notiz, Update) {
   document.getElementsByName("Form")[0].notiz.value = Notiz.replace(new RegExp('~', 'gi'), "\r\n");
   document.getElementById("status").innerHTML = "Letzte Änderung: " + Update;
  }

  // Zeile markieren
  function markRow(ID) {
   for (var i = 1; i <= document.getElementsByName("id").length; i++) {
    document.getElementById("r" + i).classList.remove("markRow");
   }
   if (ID != "x") {
    document.getElementById("r" + ID).classList.add("markRow");
   }
  }
  </script>

  <style>
  body {
   cursor: Default;
  }

  body, textarea {
   font-family: Verdana, Arial, Sans-Serif;
   font-size: 1rem;
  }

  a:link, a:visited {
   color: #4169E1;
  }

  fieldset {
   border-top: Dashed 4px #AAAAAA;
   border-right: Solid 4px #CCCCCC;
   border-bottom: Solid 4px #CCCCCC;
   border-left: Solid 4px #CCCCCC;
   border-radius: 12px;
   padding: 0px;
   background: #F4F9FD;
  }

  fieldset legend {
   font-size: 1.4rem;
   color: #2F8CD9;
   margin-left: 40px;
   display: Inline-Block;
  }

  /* Notizen */
  div#notizen {
   width: 100%;
   min-width: 100%;
   max-width: 100%;
   height: 200px;
   min-height: 100px;
   max-height: 550px;
   overflow: Auto;
   resize: Vertical;
   border-bottom: Solid 1px #CCCCCC;
   background-image: Url("data:image/png;base64,R0lGODdhAgARAIgAAP///8rr/SwAAAAAAgARAAACBoSPqYsRBQA7");
   margin-bottom: 5px;
  }

  /* Notiz */
  div.notiz {
   padding-left: 5px;
   margin: 0 0 7px 0;
  }

  label.mark {
   display: Inline-Block;
  }

  /* Textarea */
  textarea {
   font-family: Verdana, Arial, Sans-Serif;
   color: #237bc2;
   width: 325px;
   min-width: 325px;
   max-width: 100%;
   height: 130px;
   min-height: 130px;
   max-height: 100%;
  }

  /* Statuszeile */
  span#status {
   display: Block;
   font-size: 0.95rem;
   color: #777777;
   height: 25px;
  }

  /* Status */
  span.status_ok {
   color: #2F9D00;
  }

  span.status_ko {
   color: #FF0000;
  }

  /* Schrift */
  textarea, div#status {
   font-family: Verdana, Arial, Sans-Serif;
   font-size: 0.95rem;
  }

  /* Label */
  input[type=radio]:checked + label {
   color: #2F8CD9;
  }

  input[type=radio]:checked + label#loeschen {
   color: Red;
  }

  .markRow {
   box-shadow: Inset 0px 0px 10px 100px #E5F1FB;
  }

  .edit {
   margin-left: 3%;
  }

  p.submit {
   margin-left: 55px;
  }
  </style>

 </head>
<body>

<form name="Form">

<fieldset>
 <legend>Notizblock</legend>

<div id="notizen">
 <noscript>JavaScript erforderlich!</noscript>
</div>

<div class="edit">
 <span id="status">&emsp;</span>
 <label>
 <textarea name="notiz"  rows="5" cols="40" spellcheck="false"></textarea></label>
</div>

<p class="edit">
 <input type="radio" name="option" id="u1" value="insert" checked="checked"> <label for="u1" title="Notiz eintragen">Eintragen</label>&nbsp;
 <input type="radio" name="option" id="u2" value="edit"> <label for="u2" title="Notiz ändern">Ändern</label>&nbsp;
 <input type="radio" name="option" id="u3" value="delete"> <label for="u3" id="loeschen" title="Notiz löschen">Löschen</label>
</p>

<p class="submit">
 <input type="button" value="Abbrechen" id="abbrechen" title="Bitte doppelt klicken!">&emsp; &emsp; 
 <input type="button" value="Ausführen" id="submit">
</p>
</fieldset>

</form>

</body>
</html>
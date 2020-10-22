<?php
session_start();
if (!isset($_SESSION["login"])) {
 header("Location: ../index.php");
 exit; 
}

?>
<!DOCTYPE html> 
<html lang="de"> 
 <head> 
  <meta charset="UTF-8"> 
  <title>Editor</title> 

  <style> 
  body { 
   background-color: #F5F5F5; 
   margin: 1rem 0 10rem 1.5rem; 
   cursor: Default; 
  } 

  body, input, textarea { 
   font-family: Verdana, Arial, Sans-Serif; 
   font-size: 0.95rem; 
  } 

  a:link, a:visited { 
   color: #4169E1; 
   text-decoration: None; 
  } 

  h2 { 
   font-weight: Normal; 
  } 

  p.ok { 
   color: #00C100; 
  } 

  p.ko { 
   color: #EE0000; 
  } 

  table#tabelle { 
   width: 460px; 
  } 

  table#tabelle th, table#tabelle td { 
   padding: 0.20rem; 
   font-weight: Normal; 
  } 

  table#tabelle tr:nth-child(even) { 
   background-color: #DCDCDC; 
   white-space: Nowrap; 
  } 

  table#tabelle tr:nth-child(odd) { 
   background-color: #FFFFFF; 
   white-space: Nowrap; 
  } 

  input[type=radio]:checked + label { 
   color: Royalblue; 
  } 

  input[type=radio]:checked + label#delete { 
   color: #FF0000; 
  } 

  textarea { 
   width: 450px; 
   height: 300px; 
   min-width: 450px; 
   min-height: 300px; 
  } 

  input[type="text"], 
   input[type="password"], 
   textarea { 
    transition: box-shadow 0.30s ease-in-out; 
    outline: none; 
    padding: 3px; 
    border: 1px solid #DDDDDD; 
    caret-color: #FF0000; 
  } 

  input[type="text"]:focus, 
   input[type="password"]:focus, 
   textarea:focus { 
    box-shadow: 0 0 3px rgb(149, 171, 238); 
    border: 1px solid rgb(149, 171, 238); 
  } 
  </style> 

 </head> 
<body> 

<form action="<?=basename($_SERVER['SCRIPT_NAME'])?>" method="get"> 
<h2> Editor&emsp; 
 <input type="text" name="suchbegriff" size="35" value="<?=isset($_GET['suchbegriff']) ? $_GET['suchbegriff'] : '';?>" placeholder="Suche nach Dateiname oder Inhalt" required="required"> 
 <input type="submit" value="&#10149;" title="Suche starten"> 
 </h2> 
</form> 

<?php 
/* 
 * editor.php (utf-8) PHP 5.4+ 
 * Werner-Zenk.de - 18.02.2018 
 */ 

// Passwort 
//$passwort = "0000"; 

// Verzeichnis 
$verzeichnis = "verzeichnis/"; // Verzeichnis mit / am Ende! 

// Die Dateiendung der Dateien die mit  
// diesem Formular bearbeitet werden kann. 
$dateiendung = "txt"; // Ohne Punkt! 

// Pagination - Anzahl der Dateien pro Seite 
$eintraege = 10; // 10 

// Zeitzone setzen (siehe: http://php.net/manual/de/timezones.europe.php) 
date_default_timezone_set("Europe/Berlin"); 

// Link Zurück 
$zurueck = '<br>&laquo; <a href="javascript:history.back();">Zurück</a>'; 

// PHP-Fehlermeldungen anzeigen (0/E_ALL) 
error_reporting(E_ALL); // E_ALL 

// Aktuelle Seite ermitteln 
$seite = ((isset($_GET["seite"])) ? abs(intval($_GET["seite"])) : 0); 

// Suche 
$ergebnisse = []; 
if (isset($_GET["suchbegriff"])) { 
 $dateien = array_slice(scanDir($verzeichnis), 2); 
 foreach ($dateien as $datei) { 
  $dinfo = pathinfo($datei); 
  if ($dinfo['extension'] == $dateiendung) { 
   $text = file_get_contents($verzeichnis . $datei); 
   if (mb_stristr($datei, $_GET["suchbegriff"]) or 
       mb_stristr($text, $_GET["suchbegriff"])) { 
    array_push($ergebnisse, '<a href="' .  $verzeichnis . basename($datei)  . '" target="_blank" title="Datei direkt aufrufen">&#128462;</a> <a href="?datei=' . 
     basename($datei) . '" title="Datei editieren">' . basename($datei) . '</a>'); 
   } 
  } 
 } 
 if (count($ergebnisse) > 0) { 
  echo '<p class="ok">Suchergebnisse: ' . count($ergebnisse) . '</p>' . 
   '<ol>'; 
  foreach ($ergebnisse as $ergebnis) { 
   echo '<li>' . $ergebnis . '</li>'; 
  } 
  echo '</ol>'; 
 } 
 else { 
  echo '<p class="ko">Keine Suchergebnisse!</p>'; 
 } 
} 

// Wurden Daten über POST gesendet 
if ($_SERVER["REQUEST_METHOD"] == "POST") { 

 // Vorschau 
 if ($_POST["option"] == "vorschau") { 
  echo '<fieldset><legend>&#9903; Vorschau</legend>' . nl2br($_POST["inhalt"]) . '</fieldset>'; 
 } 

 // Passwort überprüfen 
 if (isset($_SESSION["login"])) { 

  // Datei speichern 
  if ($_POST["option"] == "speichern") { 
   if (!empty($_POST["dateiname"]) && 
       !empty($_POST["inhalt"])) { 
    $dateiname = $_POST["dateiname"]; 

    // Existiert eine Datei mit gleichem Namen 
    if (!file_exists($verzeichnis . $dateiname . '.' . $dateiendung)) { 
     // Dateiname korrigieren 
     $dateiname = trim(mb_strtolower($dateiname, 'UTF-8')); // In Kleinbuchstaben umwandeln (unter Berücksichtigung von UTF-8) 
     $dateiname = strtr($dateiname, [" "=>"_", "ä"=>"ae", "ö"=>"oe", "ü"=>"ue", "ß"=>"ss"]); // Leerzeichen und Umlaute ersetzen 
     $dateiname = preg_replace("/[^a-z0-9_-]/", "", $dateiname); // Alles Zeichen außer Buchstaben, Zahlen Unterstrich und Bindestrich entfernen 
     $dateiname = ($dateiname == '') ? 'unbenannt' : $dateiname; // Wenn der Dateiname keinen Namen hat, auf 'unbenannt' setzen 
    } 
    else if ($_POST["neu"] == 'ja') { 
     exit('<p class="ko">Die Datei "' . $dateiname . '.' . $dateiendung . '" ist bereits vorhanden!' . $zurueck . '</p>'); 
    } 
  
    // Speichern 
    if (file_put_contents($verzeichnis . $dateiname . '.' . $dateiendung, $_POST["inhalt"])) { 
     echo '<p class="ok">Die Datei "' . $dateiname . '.' . $dateiendung . '" wurde erfolgreich gespeichert.</p>'; 
    } 
    else { 
     echo '<p class="ko">Beim speichern der Datei "' . $dateiname . '.' . $dateiendung . '" ist ein Fehler aufgetreten (Schreibrechte überprüfen)!' . $zurueck . '</p>'; 
    } 
   } 
   else { 
    echo '<p class="ko">Es wurden nicht alle Formularfelder korrekt ausgefüllt!' . $zurueck . '</p>'; 
   } 
  } 

  // Datei Löschen 
  if ($_POST["option"] == "loeschen") { 
   if (!empty($_POST["dateiname"])) { 
    if (file_exists($verzeichnis . $_POST["dateiname"] . '.' . $dateiendung)) { 

     // Löschen 
     if (unlink($verzeichnis . $_POST["dateiname"] . '.' . $dateiendung)) { 
      echo '<p class="ok">Die Datei "' . $_POST["dateiname"] . '.' . $dateiendung . '" wurde erfolgreich gelöscht.</p>'; 
     } 
     else { 
      echo '<p class="ko">Die Datei "' . $_POST["dateiname"] . '.' . $dateiendung . '" konnte nicht gelöscht werden (Schreibrechte überprüfen)!' . $zurueck . '</p>'; 
     } 
    } 
   } 
   else { 
    echo '<p class="ko">Der Dateiname fehlt!' . $zurueck . '</p>'; 
   } 
  } 

  // Datei hochladen 
  if ($_POST["option"] == "hochladen") { 

   // Wurde eine Datei ausgewählt 
   if ($_FILES["datei"]["name"] != "" && 
       $_FILES["datei"]["error"] === UPLOAD_ERR_OK) { 

    // Den Dateinamen ermitteln 
    $dinfo = pathinfo($_FILES["datei"]["name"]); 
    $dateiname = $dinfo['filename']; 

    // Dateiendung vergleichen 
    if ($dinfo['extension'] == $dateiendung) { 

     // Dateiname korrigieren 
     $dateiname = trim(mb_strtolower($dateiname, 'UTF-8')); 
     $dateiname = strtr($dateiname, [" "=>"_", "ä"=>"ae", "ö"=>"oe", "ü"=>"ue", "ß"=>"ss"]); 
     $dateiname = preg_replace("/[^a-z0-9_-]/", "", $dateiname); 
     $dateiname = ($dateiname == '') ? 'unbenannt' : $dateiname; 

     // Existiert eine Datei mit gleichem Namen 
     if (!file_exists($verzeichnis . "/" . $dateiname . "." . $dateiendung)) { 

      // Datei verschieben 
      if (move_uploaded_file($_FILES["datei"]["tmp_name"], $verzeichnis . "/" . $dateiname . "." . $dateiendung)) { 
       echo '<p class="ok">Die Datei "' . $dateiname . '.' . $dateiendung . '" wurde erfolgreich hochgeladen.</p>'; 
      } 
      else { 
       echo '<p class="ko">Fehler beim verschieben der Datei!' . $zurueck . '</p>'; 
      } 
     } 
     else { 
      echo '<p class="ko">Die Datei "' . $dateiname . '.' . $dateiendung . '" ist bereits vorhanden!' . $zurueck . '</p>'; 
     } 
    } 
    else { 
     echo '<p class="ko">Dieses Dateiformat wird nicht unterstützt (nur: <i>.' . $dateiendung . '</i>-Formate)!' . $zurueck . '</p>'; 
    } 
   } 
   else { 
    echo '<p class="ko">Beim hochladen ist ein Fehler aufgetreten!' . $zurueck . '</p>'; 
   } 
  } 

 } 
 else { 
  echo '<p class="ko">Das Passwort ist fehlerhaft!' . $zurueck . '</p>'; 
 } 
} 

// Datei zum bearbeiten auslesen 
if (isset($_GET["datei"])) { 
  $dateiname = basename($_GET["datei"]); 
 if (file_exists($verzeichnis . $dateiname)) { 

  // Leserechte überprüfen 
  if (is_readable($verzeichnis . $dateiname)) { 
   $neu = "nein"; 

   // Den Dateinamen ermitteln 
   $dinfo = pathinfo($_GET["datei"]); 
   $dateiname = $dinfo['filename']; 

   // Die Dateiendung überprüfen 
   if ($dinfo['extension'] == $dateiendung) { 

    // Den Inhalt auslesen 
    $inhalt = file_get_contents($verzeichnis . $_GET["datei"]); 
   } 
  } 
  else { 
   echo '<p class="ko">Die Datei "' . $_GET["datei"] . '" besitzt keine Leserechte!' . $zurueck . '</p>'; 
  } 
 } 
} 
// Neue Datei 
else { 
 $neu = "ja"; 
 $dateiname = ""; 
 $inhalt = ""; 
} 
?> 

<form action="<?=basename($_SERVER['SCRIPT_NAME']) . '?seite=' . $seite?>" method="post" accept-charset="UTF-8" enctype="multipart/form-data"> 

<p> 
 <label>&#128462; Dateiname: 
 <input type="text" size="34" name="dateiname" value="<?=$dateiname?>" pattern="^[a-z0-9-_]{1,100}$" 
 title="Der Dateiname darf nur aus: Kleinbuchstaben, Zahlen, Bindestrich oder Unterstrich bestehen (keine Umlaute, Leerzeichen oder Sonderzeichen)!"></label>  
.<?=$dateiendung?> 
</p> 

<p> 
<label>&#9998; Inhalt:<br> 
 <textarea name="inhalt" rows="12" cols="50"><?=$inhalt?></textarea> 
</label> 
</p> 

<p> 
 <input type="radio" name="option" value="speichern" id="speichern" checked="checked"> <label for="speichern" title="Datei speichern">&#128427; Speichern</label>&emsp;  
 <input type="radio" name="option" value="loeschen" id="loeschen" required="required"> <label for="loeschen" id="delete" title="Datei löschen">&#10006; Löschen</label>&emsp;  
 <input type="radio" name="option" value="vorschau" id="vorschau" title="Vorschau anzeigen"> <label for="vorschau">&#9903; Vorschau</label> 
</p> 

<p> 
 <input type="radio" name="option" value="hochladen" id="hochladen" required="required"> <label for="hochladen" title="Datei hochladen">&#128471; Hochladen: 
 <input type="file" name="datei"></label> 
</p> 

<p> 
 
 <input type="hidden" name="neu" value="<?=$neu?>"> &emsp; 
 <input type="submit" value="&#10004; Ausführen" title="Ausgewählte Option ausführen"> 
</p> 

</form> 

<?php 
// Verzeichnis auslesen 
if (is_dir($verzeichnis)) { 
 $dateien = glob($verzeichnis . "*." . $dateiendung); 

 // Dateien mit einem Link verknüpfen 
 $ausgabe = []; 
 foreach ($dateien as $datei) { 
  $ausgabe[] =  '<tr><td><a href="' .  $verzeichnis . basename($datei)  . '" target="_blank" title="Datei direkt aufrufen">&#128462;</a> <a href="?datei=' . 
   basename($datei) . '&amp;seite=' . $seite . '" title="Datei editieren">' . basename($datei) . '</a></td><td><small>' . 
  // Dateigröße ermitteln 
  number_format((filesize($datei) / 1024), 2, ",", ".") . ' KB</small></td><td><small>' . 
  // Letzte Änderung ermitteln 
  date("d.m.Y - H:i", fileMtime($datei)) . ' Uhr</small></td></tr>'; 
 } 

 // Seitennavigation 
 $nr = 1; 
 echo '<table id="tabelle"><tr><td colspan="3">&#128449; "<code>' . $verzeichnis . '</code>"&emsp;' 
  . count($dateien) . (count($dateien) == 1 ? ' Datei' : ' Dateien') . '&emsp;Seite: '; 
 for ($zaehler = 0; $zaehler < count($ausgabe); $zaehler = $zaehler + $eintraege) { 
  echo (($zaehler == $seite) ? ' <strong>' . $nr . '</strong>' : 
  ' <a href="?seite=' . $zaehler . '" title="Seite ' . $nr . ' anzeigen">' . $nr . '</a>'); 
  $nr++; 
 } 

 // Dateien ausgeben 
 echo '</td></tr><tr><th title="Dateiname" width="48%">&#128462;</th>' . 
  '<th title="Dateigröße" width="15%">&#13189;</th><th title="Letzte Änderung" >&#9719;</th></tr>'; 
 for ($zaehler = $seite; $zaehler < ($seite + $eintraege); $zaehler++) { 
  if (isset($ausgabe[$zaehler])) { 
   echo $ausgabe[$zaehler]; 
  } 
 } 
 echo '</table>'; 
} 
else { 
 echo '<p class="ko">Das Verzeichnis "' . $verzeichnis . '" ist nicht vorhanden!</p>'; 
} 
?> 

</body> 
</html>
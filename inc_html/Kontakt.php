<!DOCTYPE html>
<html lang="de">
 <head>
  <meta charset="UTF-8">
  <title></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" media="screen" href="../css/desktop.css">
  <link rel="stylesheet" type="text/css" media="screen" href="../css/style.css">
  

 </head>
<body>

<?php
/*
   Aktion: Formulardaten in eine MySQL-Datenbank eintragen.
   Formular - PHP 5.4+, Zeichenkodierung: UTF-8
   Siehe: https://werner-zenk.de/tipps/schriftzeichen_richtig_darstellen.php

   Erstellt mit dem Formular Generator (27.02.2018) - 
   https://werner-zenk.de/tools/formulargenerator.php

   Bitte testen Sie das Formular ausführlich und
   beachten Sie die Hinweise im Quelltext!
*/

// PHP Fehlermeldungen (1 um das Formular zu testen) anzeigen.
error_reporting(0); // (0/1)

$kundennummer = isset($_POST["kundennummer"]) ? strip_tags(trim($_POST["kundennummer"])) : ""; // Kundennummer
$name = isset($_POST["name"]) ? strip_tags(trim($_POST["name"])) : ""; // Name
$vorname = isset($_POST["vorname"]) ? strip_tags(trim($_POST["vorname"])) : ""; // Vorname
$firma = isset($_POST["firma"]) ? strip_tags(trim($_POST["firma"])) : ""; // Firma
$email = isset($_POST["email"]) ? strip_tags(trim($_POST["email"])) : ""; // E-Mail
$telefonnummer = isset($_POST["telefonnummer"]) ? strip_tags(trim($_POST["telefonnummer"])) : ""; // Telefonnummer
$strasse = isset($_POST["strasse"]) ? strip_tags(trim($_POST["strasse"])) : ""; // Straße
$hausnummer = isset($_POST["hausnummer"]) ? strip_tags(trim($_POST["hausnummer"])) : ""; // Hausnummer
$plz = isset($_POST["plz"]) ? strip_tags(trim($_POST["plz"])) : ""; // PLZ
$ort = isset($_POST["ort"]) ? strip_tags(trim($_POST["ort"])) : ""; // Ort
$nachricht = isset($_POST["nachricht"]) ? strip_tags(trim($_POST["nachricht"])) : ""; // Nachricht

// Benutzereingaben überprüfen
// Die Meldungen müssen hier eventuell angepasst werden.
$Fehler = ["name"=>"", "vorname"=>"", "email"=>"", 
 "telefonnummer"=>"", "strasse"=>"", "hausnummer"=>"", "plz"=>"", "ort"=>"", 
 "nachricht"=>"", "sicherheit"=>""];
if (isset($_POST["submit"])) {
	if($_POST["kundennummer"] != ''){
		$Fehler["kundennummer"] = strlen($_POST["kundennummer"]) > 12 ? " Es sind maximal 12 Zeichen erlaubt!" : "";
		$Fehler["kundennummer"] .= $_POST["kundennummer"] != strip_tags($_POST["kundennummer"]) ? " HTML-Tags sind nicht erlaubt!" : "";
	}
 $Fehler["name"] = strlen($_POST["name"]) < 1 ? " Bitte füllen Sie dieses Feld aus!" : "";
 $Fehler["name"] .= strlen($_POST["name"]) > 25 ? " Es sind maximal 25 Zeichen erlaubt!" : "";
 $Fehler["name"] .= $_POST["name"] != strip_tags($_POST["name"]) ? " HTML-Tags sind nicht erlaubt!" : "";
 $Fehler["vorname"] = strlen($_POST["vorname"]) < 1 ? " Bitte füllen Sie dieses Feld aus!" : "";
 $Fehler["vorname"] .= strlen($_POST["vorname"]) > 25 ? " Es sind maximal 25 Zeichen erlaubt!" : "";
 $Fehler["vorname"] .= $_POST["vorname"] != strip_tags($_POST["vorname"]) ? " HTML-Tags sind nicht erlaubt!" : "";
 $Fehler["email"] = strlen($_POST["email"]) < 1 ? " Bitte füllen Sie dieses Feld aus!" : "";
 $Fehler["email"] .= $_POST["email"] != strip_tags($_POST["email"]) ? " HTML-Tags sind nicht erlaubt!" : "";
 $Fehler["telefonnummer"] = strlen($_POST["telefonnummer"]) < 1 ? " Bitte füllen Sie dieses Feld aus!" : "";
 $Fehler["telefonnummer"] .= !preg_match("/^[ 0-9\/-]{6,}+$/", $_POST["telefonnummer"]) ? " Die Telefonnummer ist fehlerhaft!" : "";
// $Fehler["strasse"] = strlen($_POST["strasse"]) < 1 ? " Bitte füllen Sie dieses Feld aus!" : "";
	if($_POST["strasse"] != ''){
		$Fehler["strasse"] = strlen($_POST["strasse"]) > 35 ? " Es sind maximal 35 Zeichen erlaubt!" : "";
		$Fehler["strasse"] .= $_POST["strasse"] != strip_tags($_POST["strasse"]) ? " HTML-Tags sind nicht erlaubt!" : "";
	}
// $Fehler["hausnummer"] = strlen($_POST["hausnummer"]) < 1 ? " Bitte füllen Sie dieses Feld aus!" : "";
	if($_POST["hausnummer"] != ''){
		$Fehler["hausnummer"] = strlen($_POST["hausnummer"]) > 8 ? " Es sind maximal 8 Zeichen erlaubt!" : "";
		$Fehler["hausnummer"] .= !ctype_xdigit($_POST["hausnummer"]) ? " Geben Sie nur Hexadezimalziffern ein!" : "";
	}	
// $Fehler["plz"] = strlen($_POST["plz"]) < 1 ? " Bitte füllen Sie dieses Feld aus!" : "";
	if($_POST["plz"] != ''){
		$Fehler["plz"] = strlen($_POST["plz"]) > 12 ? " Es sind maximal 12 Zeichen erlaubt!" : "";
		$Fehler["plz"] .= !preg_match("/^[0-9]{4,5}$/", $_POST["plz"]) ? " Die Postleitzahl ist fehlerhaft!" : "";
	}
// $Fehler["ort"] = strlen($_POST["ort"]) < 1 ? " Bitte füllen Sie dieses Feld aus!" : "";
	if($_POST["ort"] != ''){
		$Fehler["ort"] = strlen($_POST["ort"]) > 50 ? " Es sind maximal 50 Zeichen erlaubt!" : "";
		$Fehler["ort"] .= $_POST["ort"] != strip_tags($_POST["ort"]) ? " HTML-Tags sind nicht erlaubt!" : "";
	}

 $Fehler["nachricht"] = strlen($_POST["nachricht"]) < 10 ? " Bitte füllen Sie dieses Feld aus (min. 10 Zeichen)!" : "";
 $Fehler["nachricht"] .= $_POST["nachricht"] != strip_tags($_POST["nachricht"]) ? " HTML-Tags sind nicht erlaubt!" : "";
 
 $Fehler["sicherheit"] = (md5($_POST["zip"]) != $_POST["zip2"]) ? "Die Rechenaufgabe ist leider falsch!" : "";
}

// Sicherheitsabfrage - Rechenaufgabe
$Z0 = [mt_rand(1, 9), mt_rand(1, 9)];
$Z1 = max($Z0); $Z2 = min($Z0);
$Spam = $Z1 . " &#43; &#" . (48 + $Z2) . ";";
$Schutz = md5($Z1 + $Z2);

// Formular erstellen
$Formular = "
<div>
	Bitte geben Sie Ihre Daten ein damit wir uns bei Ihnen melden können. 
</div>
<form action='" . $_SERVER["SCRIPT_NAME"] . "' method='post' class='kontaktformular'>



<p>
 <label> Kundennummer:
<br>
  <input type='text' name='kundennummer' value='" . $kundennummer . "' size='35' maxlength='12'>
 </label>
 <br><span class='hilfetext'> Sollten Sie schon eine Kundennummer bei uns haben, tragen Sie sie bitte ein. </span>
</p>

<p>
 <label> Name:
<span class='pflichtfeld'>&#10034; " . $Fehler["name"] . "</span><br>
  <input type='text' name='name' value='" . $name . "' size='35' maxlength='25' required='required'>
 </label>
</p>

<p>
 <label> Vorname:
<span class='pflichtfeld'>&#10034; " . $Fehler["vorname"] . "</span><br>
  <input type='text' name='vorname' value='" . $vorname . "' size='35' maxlength='25' required='required'>
 </label>
</p>

<p>
 <label> Firma:
<br>
  <input type='text' name='firma' value='" . $firma . "' size='35' maxlength='50'>
 </label>
</p>

<p>
 <label> E-Mail:
<span class='pflichtfeld'>&#10034; " . $Fehler["email"] . "</span><br>
  <input type='email' name='email' value='" . $email . "' size='35' required='required'>
 </label>
</p>

<p>
 <label> Telefonnummer:
<span class='pflichtfeld'>&#10034; " . $Fehler["telefonnummer"] . "</span><br>
  <input type='tel' name='telefonnummer' value='" . $telefonnummer . "' size='35' required='required'>
 </label>
</p>

<p>
 <label> Straße:
<br>
  <input type='text' name='strasse' value='" . $strasse . "' size='35' maxlength='35'>
 </label>
</p>

<p>
 <label> Hausnummer:
<br>
  <input type='text' name='hausnummer' value='" . $hausnummer . "' size='35' maxlength='8'>
 </label>
</p>

<p>
 <label> PLZ:
<br>
  <input type='text' name='plz' value='" . $plz . "' size='35' maxlength='12'>
 </label>
</p>

<p>
 <label> Ort:
<br>
  <input type='text' name='ort' value='" . $ort . "' size='35' maxlength='50'>
 </label>
</p>

<p>
 <label> Nachricht:
 <span class='pflichtfeld'>&#10034; " . $Fehler["nachricht"] . "</span><br>
 <textarea name='nachricht' cols='40' rows='8' required='required'>" . $nachricht . "</textarea>
 </label>
</p>

<p>
 <label> Sicherheitsabfrage: 
 <span class='pflichtfeld'>&#10034; " . $Fehler["sicherheit"] . "</span><br>
 <em>" . $Spam . "</em> = 
 <input type='text' name='zip' size='4' pattern='[0-9]{1,2}' required='required' autocomplete='off'>
 </label>
 <input type='hidden' name='zip2' value='" . $Schutz . "'>
 <br><span class='hilfetext'> Bitte lösen Sie die Rechenaufgabe. </span><br>
</p>

<p>
 <br>
 <input type='submit' name='submit' value='Formular absenden'>
</p>
<div class='pflichtfeldhinweis'><input style='-ms-transform: scale(1.3);-moz-transform: scale(1.3);-webkit-transform: scale(1.3);-o-transform: scale(1.3); ;width:15px;align:left;' type='checkbox' name='datenschutz' value='akzeptiert'> <a href='inc_html/Datenschutz.html' target='_blank' style='color:#0066FF;'>Bitte lesen und akzeptieren Sie die Datenschutzerklärung.</a><br></div>
<p>
 Hinweis: Felder mit <span class='pflichtfeld'>*</span> müssen ausgefüllt werden.
</p>

</form>
";

// Formular abgesendet
if (isset($_POST["submit"])) {

 // Sind keine Benutzer-Eingabefehler vorhanden
 if (implode("", $Fehler) == "") {


	include "../config.php";
	include "../".ADMIN_PFAD."einstellungen.php";  
	// Verbindung zur Datenbank aufbauen
	try {
	 $db = new PDO("mysql:host=" . $DB_HOST . ";dbname=" . $DB_NAME, $DB_USER, $DB_PASSWORD,
	 [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"]);
	}
	catch (PDOException $e) {
	 exit("<h4>Verbindung fehlgeschlagen!</h4>" . $e->getMessage());
	}
	  

  // Daten eintragen
  // Die Datenbanktabelle `auftragsanfragen` muss vor dem ersten Eintrag erstellt werden!
  $Insert = $db->prepare("INSERT INTO `auftragsanfragen`
   SET
    `kundennummer` = :kundennummer, 
    `name` = :name, 
    `vorname` = :vorname, 
    `firma` = :firma, 
    `email` = :email, 
    `telefonnummer` = :telefonnummer, 
    `strasse` = :strasse, 
    `hausnummer` = :hausnummer, 
    `plz` = :plz, 
    `ort` = :ort, 
    `nachricht` = :nachricht
  ");
  $Insert->bindValue(":kundennummer", $kundennummer);
  $Insert->bindValue(":name", $name);
  $Insert->bindValue(":vorname", $vorname);
  $Insert->bindValue(":firma", $firma);
  $Insert->bindValue(":email", $email);
  $Insert->bindValue(":telefonnummer", $telefonnummer);
  $Insert->bindValue(":strasse", $strasse);
  $Insert->bindValue(":hausnummer", $hausnummer);
  $Insert->bindValue(":plz", $plz);
  $Insert->bindValue(":ort", $ort);
  $Insert->bindValue(":nachricht", $nachricht);
  if ($Insert->execute()) {
      
   echo '
	<div class="hilfscontainer"><main>
	<nav><ul>
	<li class="li_nav"><a href="../index.php">Klicken Sie hier um auf die Hompage zurück zugehen. </a></li>
	</ul></nav>
	<article>
	<div class="dialog_green">Ihre Daten wurden uns übermittelt. Wir werden uns bei Ihnen melden.</div>
	</article>
	</main></div>
   
   ';
   exit;
   
  }
  else {
   echo "<script>alert('Die Daten konnten nicht gesendet werden. Versuchen Sie es später bitte nochmal.')</script>";
   echo '<nav><ul><li class="li_nav1"><a href="../index.php">Klicken Sie hier um auf die Hompage zurück zugehen. </a></li></ul></nav>';
   exit;   
   //print_r($Insert->errorInfo());
  }
 }
 else {

  // Formular und Benutzer-Eingabefehler ausgeben
  echo $Formular;
 }
}
else {

 // Formular ausgeben
 echo $Formular;
}
?>



</body>
</html>
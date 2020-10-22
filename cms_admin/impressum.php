<?php
session_start();
if (!isset($_SESSION["login"])) {
 header("Location: ../register/anmeldung.php");
 exit;
}

?>

<!DOCTYPE html>
<html lang="de">
 <head>
  <meta charset="UTF-8">
  <title></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <style>
  body {
   font-family: Verdana, Sans-Serif;
   font-size: 1rem;
  }

  caption h3 {
   font-weight: Normal;
  }
  </style>

 </head>
<body>

<?php
/*
   Aktion: Formulardaten in eine SQLite-Datenbank-Datei eintragen.
   Formular - PHP 5.4+, Zeichenkodierung: UTF-8
   Siehe: https://werner-zenk.de/tipps/schriftzeichen_richtig_darstellen.php

   Erstellt mit dem Formular Generator (18.06.2018) - 
   https://werner-zenk.de/tools/formulargenerator.php

   Bitte testen Sie das Formular ausführlich und
   beachten Sie die Hinweise im Quelltext!
*/

// PHP Fehlermeldungen (1 um das Formular zu testen) anzeigen.
error_reporting(1); // (0/1)
// Datenbank-Datei
$Datei = "../cache/db/impressum.sqt";

if (file_exists($Datei)){
	$DB = new PDO("sqlite:" . $Datei);
	$sql = "SELECT * FROM Impressum WHERE id = 1";
	$DB_daten = $DB->query($sql)->fetch();
		
	$firmenname = $DB_daten['firmenname']; // Firmenname
	$strasse = $DB_daten['strasse']; // Straße
	$hausnummer = $DB_daten['hausnummer']; // Hausnummer
	$plz = $DB_daten['plz']; // PLZ
	$stadt = $DB_daten['stadt']; // Stadt
	$land = $DB_daten['land']; // Land
	$telefonnummer = $DB_daten['telefonnummer']; // Telefonnummer
	$faxnummer = $DB_daten['faxnummer']; // Fax-Nummer
	$emailadresse =  $DB_daten['emailadresse']; // E-Mail-Adresse
	$geschaeftsfuehrer = $DB_daten['geschaeftsfuehrer']; // Geschäftsführer
	$ustidnr = $DB_daten['ustidnr']; // Ust-IdNr
	$handelsregister = $DB_daten['handelsregister']; // Handelsregister
	$DB = null;
}
else{
	
}




// Formular erstellen
$Formular = "
<form action='" . $_SERVER["SCRIPT_NAME"] . "#anker' autocomplete='off' method='post'>
<a id='anker'></a>

<p>
 <label> Firmenname:
<br>
  <input type='text' name='firmenname' value='" . $firmenname . "' size='35'>
 </label>
</p>

<p>
 <label> Straße:
<br>
  <input type='text' name='strasse' value='" . $strasse . "' size='35'>
 </label>
</p>

<p>
 <label> Hausnummer:
<br>
  <input type='text' name='hausnummer' value='" . $hausnummer . "' size='35'>
 </label>
</p>

<p>
 <label> PLZ:
<br>
  <input type='text' name='plz' value='" . $plz . "' size='35'>
 </label>
</p>

<p>
 <label> Stadt:
<br>
  <input type='text' name='stadt' value='" . $stadt . "' size='35'>
 </label>
</p>

<p>
 <label> Land:
<br>
  <input type='text' name='land' value='" . $land . "' size='35'>
 </label>
</p>

<p>
 <label> Telefonnummer:
<br>
  <input type='text' name='telefonnummer' value='" . $telefonnummer . "' size='35'>
 </label>
</p>

<p>
 <label> Fax-Nummer:
<br>
  <input type='text' name='faxnummer' value='" . $faxnummer . "' size='35'>
 </label>
</p>

<p>
 <label> E-Mail-Adresse:
<br>
  <input type='text' name='emailadresse' value='" . $emailadresse . "' size='35'>
 </label>
</p>

<p>
 <label> Geschäftsführer:
<br>
  <input type='text' name='geschaeftsfuehrer' value='" . $geschaeftsfuehrer . "' size='35'>
 </label>
</p>

<p>
 <label> Ust-IdNr:
<br>
  <input type='text' name='ustidnr' value='" . $ustidnr . "' size='35'>
 </label>
</p>

<p>
 <label> Handelsregister:
<br>
  <input type='text' name='handelsregister' value='" . $handelsregister . "' size='35'>
 </label>
</p>

<p>
 <br>
 <input type='submit' name='submit' value='Formular absenden'>
</p>
</form>
";

// Formular abgesendet
if (isset($_POST["submit"])) {

  // Daten in eine SQLite-Datenbank-Datei eintragen mit PDO (Vorschlag) - Bitte anpassen!
	$firmenname = isset($_POST["firmenname"]) ? strip_tags(trim($_POST["firmenname"])) : ""; // Firmenname
	$strasse = isset($_POST["strasse"]) ? strip_tags(trim($_POST["strasse"])) : ""; // Straße
	$hausnummer = isset($_POST["hausnummer"]) ? strip_tags(trim($_POST["hausnummer"])) : ""; // Hausnummer
	$plz = isset($_POST["plz"]) ? strip_tags(trim($_POST["plz"])) : ""; // PLZ
	$stadt = isset($_POST["stadt"]) ? strip_tags(trim($_POST["stadt"])) : ""; // Stadt
	$land = isset($_POST["land"]) ? strip_tags(trim($_POST["land"])) : ""; // Land
	$telefonnummer = isset($_POST["telefonnummer"]) ? strip_tags(trim($_POST["telefonnummer"])) : ""; // Telefonnummer
	$faxnummer = isset($_POST["faxnummer"]) ? strip_tags(trim($_POST["faxnummer"])) : ""; // Fax-Nummer
	$emailadresse = isset($_POST["emailadresse"]) ? strip_tags(trim($_POST["emailadresse"])) : ""; // E-Mail-Adresse
	$geschaeftsfuehrer = isset($_POST["geschaeftsfuehrer"]) ? strip_tags(trim($_POST["geschaeftsfuehrer"])) : ""; // Geschäftsführer
	$ustidnr = isset($_POST["ustidnr"]) ? strip_tags(trim($_POST["ustidnr"])) : ""; // Ust-IdNr
	$handelsregister = isset($_POST["handelsregister"]) ? strip_tags(trim($_POST["handelsregister"])) : ""; // Handelsregister	


  // Datenbank-Datei erstellen
  if (!file_exists($Datei)) {
   $DB = new PDO("sqlite:" . $Datei);
   $DB->exec("CREATE TABLE Impressum( 
    `id` INTEGER PRIMARY KEY,
    `firmenname` CHAR(255), 
    `strasse` CHAR(255), 
    `hausnummer` CHAR(255), 
    `plz` CHAR(255), 
    `stadt` CHAR(255), 
    `land` CHAR(255), 
    `telefonnummer` CHAR(255), 
    `faxnummer` CHAR(255), 
    `emailadresse` CHAR(255), 
    `geschaeftsfuehrer` CHAR(255), 
    `ustidnr` CHAR(255), 
    `handelsregister` CHAR(255)
   )");
  }
  else {
   // Verbinden
   $DB = new PDO("sqlite:" . $Datei);
  }



  // Daten eintragen
  $Insert = $DB->prepare("UPDATE `Impressum` SET 
		   `firmenname` = :firmenname,
		   `strasse` = :strasse, 
		   `hausnummer` = :hausnummer,
		   `plz` = :plz, 
		   `stadt` = :stadt, 
		   `land` = :land, 
		   `telefonnummer` = :telefonnummer, 
		   `faxnummer` = :faxnummer, 
		   `emailadresse` = :emailadresse, 
		   `geschaeftsfuehrer` = :geschaeftsfuehrer, 
		   `ustidnr` = :ustidnr, 
		   `handelsregister` = :handelsregister 
            WHERE `id` = :id;");


/*
  // Daten eintragen
  $Insert = $DB->prepare("INSERT INTO `Impressum` (
   `firmenname`, 
   `strasse`, 
   `hausnummer`, 
   `plz`, 
   `stadt`, 
   `land`, 
   `telefonnummer`, 
   `faxnummer`, 
   `emailadresse`, 
   `geschaeftsfuehrer`, 
   `ustidnr`, 
   `handelsregister`
  ) VALUES (
   :firmenname, 
   :strasse, 
   :hausnummer, 
   :plz, 
   :stadt, 
   :land, 
   :telefonnummer, 
   :faxnummer, 
   :emailadresse, 
   :geschaeftsfuehrer, 
   :ustidnr, 
   :handelsregister
  )");
  */
  $Insert->bindValue(":firmenname", $firmenname);
  $Insert->bindValue(":strasse", $strasse);
  $Insert->bindValue(":hausnummer", $hausnummer);
  $Insert->bindValue(":plz", $plz);
  $Insert->bindValue(":stadt", $stadt);
  $Insert->bindValue(":land", $land);
  $Insert->bindValue(":telefonnummer", $telefonnummer);
  $Insert->bindValue(":faxnummer", $faxnummer);
  $Insert->bindValue(":emailadresse", $emailadresse);
  $Insert->bindValue(":geschaeftsfuehrer", $geschaeftsfuehrer);
  $Insert->bindValue(":ustidnr", $ustidnr);
  $Insert->bindValue(":handelsregister", $handelsregister);
  $Insert->bindValue(":id", 1);
  if ($Insert->execute()) {
   echo "<p id='anker'>Die Daten wurden eingetragen.</p>";
  }
  else {
   echo "<p id='anker'>Fehler beim eintragen der Daten!</p>";
   //print_r($Insert->errorInfo());
  }
}
else {
	
	

 // Formular ausgeben
 echo $Formular;
}
?>


</body>
</html> 
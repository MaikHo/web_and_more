<?php
session_start();
if (!isset($_SESSION["login"])) {
 header("Location: ../index.php");
 exit;
}
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

// Formular abgesendet
if (isset($_POST)) {


$kundennummer = isset($_POST["kundennummer"]) ? trim($_POST["kundennummer"]) : ""; // Kundennummer
$firma = isset($_POST["firma"]) ? trim($_POST["firma"]) : ""; // Firma
$name = isset($_POST["name"]) ? trim($_POST["name"]) : ""; // Name
$vorname = isset($_POST["vorname"]) ? trim($_POST["vorname"]) : ""; // Vorname
$email = isset($_POST["email"]) ? trim($_POST["email"]) : ""; // E-Mail
$telefon = isset($_POST["telefon"]) ? trim($_POST["telefon"]) : ""; // Telefon
$strasse = isset($_POST["strasse"]) ? trim($_POST["strasse"]) : ""; // Straße
$hausnummer = isset($_POST["hausnummer"]) ? trim($_POST["hausnummer"]) : ""; // Hausnummer
$plz = isset($_POST["plz"]) ? trim($_POST["plz"]) : ""; // PLZ
$stadt = isset($_POST["stadt"]) ? trim($_POST["stadt"]) : ""; // Stadt





  

  
  // Daten eintragen
  // Die Datenbanktabelle `Tabellenname` muss vor dem ersten Eintrag erstellt werden!
  $Insert = $db->prepare("INSERT INTO `Kunden`
   SET
    `kundennummer` = :kundennummer, 
    `firma` = :firma, 
    `name` = :name, 
    `vorname` = :vorname, 
    `email` = :email, 
    `telefon` = :telefon, 
    `strasse` = :strasse, 
    `hausnummer` = :hausnummer, 
    `plz` = :plz, 
    `stadt` = :stadt
  ");
  $Insert->bindValue(":kundennummer", $kundennummer);
  $Insert->bindValue(":firma", $firma);
  $Insert->bindValue(":name", $name);
  $Insert->bindValue(":vorname", $vorname);
  $Insert->bindValue(":email", $email);
  $Insert->bindValue(":telefon", $telefon);
  $Insert->bindValue(":strasse", $strasse);
  $Insert->bindValue(":hausnummer", $hausnummer);
  $Insert->bindValue(":plz", $plz);
  $Insert->bindValue(":stadt", $stadt);
  if ($Insert->execute()) {
   echo "<p>Die Daten wurden eingetragen.</p>";
  }
  else {
   echo "<p>Fehler beim eintragen der Daten!</p>";
   //print_r($Insert->errorInfo());
  }
}
?>
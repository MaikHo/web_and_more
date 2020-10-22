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



  

  
  // Daten eintragen
  // Die Datenbanktabelle `Tabellenname` muss vor dem ersten Eintrag erstellt werden!
  $Insert = $db->prepare("DELETE FROM `auftragsanfragen` WHERE id = :id");
   
  $Insert->bindValue(":id", $_POST['data_id']);
  
  if ($Insert->execute()) {
   echo "<p>Die Daten wurden gelöscht.</p>";
  }
  else {
   echo "<p>Fehler beim löschen der Daten!</p>";
   //print_r($Insert->errorInfo());
  }
}
?>
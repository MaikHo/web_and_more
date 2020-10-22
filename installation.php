<?php
// damit es keine Probleme mit einer Session  gibt, davor einbinden
include "inc_function/autoload_class.php";

File::include_php_file("inc_function/error_reporting.php");	


File::include_php_file("config.php");


?>
<!DOCTYPE html>
<html lang="de">
 <head>
  <meta charset="utf-8">
  <title>Installation</title>
  <meta name="robots" content="noindex">
  <link rel="stylesheet" type="text/css" media="screen" href="css/style.css">
  <link rel="stylesheet" type="text/css" media="screen" href="css/desktop.css">
  
  <style>
  	.spalte{
		width: 49%;
		float: left;;
	}
  	label{
		display: inline-block;
		width: 12em;
	}
  	h1{
		text-align: center;
	}
	#hausnummer{
		width: 4em;
	}
	
	
	  span.pflichtfeld {
	   font-size: 0.90rem;
	   color: Red;
	  }

	  span.hilfetext {
	   font-family: Arial, Tahoma, Sans-Serif;
	   font-size: 0.80rem;
	   font-style: Oblique;
	  }
	
	@media only screen and (max-width: 768px) {
	    /* For mobile phones: */
	    .spalte {
	        width: 100%;
	    }
	}
	
	
  </style>
  
</head>
<body>
<div class="hilfscontainer">


<?php





//include "admin/einstellungen.php";


if (isset($_POST['admin_name']) && ($_POST['admin_passwort']))
  {
	// Administrator Name
	// Der Admin. Name wird in der DB-Tabelle gespeichert.
	$ADMIN_NAME = isset($_POST["admin_name"]) ? strip_tags(trim($_POST["admin_name"])) : ""; 

	// Administrator Passwort (max. 70 Zeichen)
	// Das Admin. Passwort wird verschlüsselt in der DB-Tabelle gespeichert.
	$ADMIN_PASSWORT = isset($_POST["admin_passwort"]) ? strip_tags(trim($_POST["admin_passwort"])) : ""; 

	// Administrator E-Mail-Adresse
	// Eine registrierte E-Mail-Adresse beim Webspace-Hoster!
	$ADMIN_EMAIL = isset($_POST["admin_email"]) ? strip_tags(trim($_POST["admin_email"])) : ""; 

	// Homepage-Name (wird in der E-Mail angezeigt!)
	$HOMEPAGE_NAME = isset($_POST["homepage_name"]) ? strip_tags(trim($_POST["homepage_name"])) : ""; 

	

	// Den neuen Benutzer vom Administrator freischalten lassen (ja/nein)
	$ADMINCHECK = isset($_POST["freischalten"]) ? strip_tags(trim($_POST["freischalten"])) : ""; 

	// Tabellenname
	$TBL_NAME = "benutzerverwaltung"; // benutzerverwaltung
	

	$hostadresse = isset($_POST["hostadresse"]) ? strip_tags(trim($_POST["hostadresse"])) : ""; // Hostadresse
	$datenbankname = isset($_POST["datenbankname"]) ? strip_tags(trim($_POST["datenbankname"])) : ""; // Datenbankname
	$benutzername = isset($_POST["benutzername"]) ? strip_tags(trim($_POST["benutzername"])) : ""; // Benutzername
	$passwort = isset($_POST["passwort"]) ? strip_tags(trim($_POST["passwort"])) : ""; // Passwort




	//var_dump($_POST);
	
	


	// Verbindung zur Datenbank aufbauen
	try {
	 $db = new PDO("mysql:host=" . $hostadresse . ";dbname=" . $datenbankname, $benutzername, $passwort,
	 [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"]);
	}
	catch (PDOException $e) {
	 exit('<h4>Datenbank Verbindung fehlgeschlagen!</h4>' . $e->getMessage());
	}
	
	
	// Daten in einer PHP-Datei (geschützt) speichern (Vorschlag) - Bitte anpassen!

	// Dateiname - Die Datei benötigt Schreibrechte.
	$Datei = ADMIN_PFAD."einstellungen.php";

	// Daten
	$Text = '  <?php
	 // Verbindungsdaten zur Datenbank
	$DB_HOST = "'.$hostadresse.'"; // Host-Adresse
	$DB_NAME = "'.$datenbankname.'"; // Datenbankname
	$DB_USER = "'.$benutzername.'"; // Benutzername
	$DB_PASSWORD = "'.$passwort.'"; // Passwort
	
	$HOMEPAGE_NAME = "'.$HOMEPAGE_NAME.'";
	
	// Der Benutzer hat X-Tage Zeit um seine Registrierung (über E-Mail) freizuschalten
	$FREISCHALTTAGE = 2; // 2
	
	// Anmeldeversuche
	$ANMELDEVERSUCHE = 3; // 3

	// Mindestlänge der Passwörter
	$PASSWORT_MIN = 8; // 8  	  
	
	
	// Absoluter Pfad zum Verzeichnis
	$PFAD = "http://" . $_SERVER["HTTP_HOST"] . rtrim(dirname($_SERVER["SCRIPT_NAME"]));

	// PHP-Version
	if (version_compare(PHP_VERSION, "5.6.0") < 0)
	 die("<p>Aktuelle PHP-Version: " . PHP_VERSION . "<br>Voraussetzung mind.: 5.6.0</p>");

	// PHP-Meldungen (zum testen) anzeigen (0 / E_ALL)
	error_reporting(0); // E_ALL = anzeigen

	// Zeitzone
	// http://de3.php.net/manual/de/timezones.europe.php
	date_default_timezone_set("Europe/Berlin"); // Europe/Berlin


	
	
	
	
	
	
	?>';

		// Daten speichern
		$Fh = fopen($Datei, "a+");
		if (fwrite($Fh, $Text)) {
		echo '<p class="ok">&#10004; Die Datei Einstellungen wurde erstellt.</p>';
		}
		else {
		echo '<p class="ko">&#10008;Fehler beim erstellen der Datei Einstellungen!</p>'; 
		}
		fclose($Fh);

		// Daten speichern im module ordner
		$Fh1 = fopen("module/einstellungen.php", "a+");
		if (fwrite($Fh1, $Text)) {
		echo '<p class="ok">&#10004; Die Datei Einstellungen wurde erstellt.</p>';
		}
		else {
		echo '<p class="ko">&#10008;Fehler beim erstellen der Datei Einstellungen!</p>'; 
		}
		fclose($Fh1);


  	  // Tabelle erstellen  	  	
	 if ($db->query("CREATE TABLE IF NOT EXISTS `benutzerverwaltung` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `sperre` smallint(2) NOT NULL,
	  `benutzername` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
	  `email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
	  `email_ok` smallint(2) NOT NULL,
	  `passwort` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
	  `register` int(10) NOT NULL,
	  `letzterbesuch` datetime NOT NULL,
	  `rolle` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
	  PRIMARY KEY (`id`),
	  UNIQUE KEY `benutzername` (`benutzername`)
	  ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"	)) {
	  echo '<p class="ok">&#10004; Die Tabelle &bdquo; benutzerverwaltung &rdquo; wurde erstellt.</p>';

	  // Admin. registrieren
	  if ($db->query("INSERT INTO `benutzerverwaltung`
	   SET
	     `id`                  = '1',
	     `sperre`            = '1',
	     `benutzername` = '" . $ADMIN_NAME . "',
	     `email`             = '" . $ADMIN_EMAIL . "',
	     `email_ok`        = '1',
	     `passwort`        = '" . (password_hash($ADMIN_PASSWORT, PASSWORD_BCRYPT)) . "',
	     `register`          = '" . time() . "',
	     `letzterbesuch`  = NOW(),
	     `rolle`             = 'Administrator'")) {

	   echo '<p class="ok">&#10004; 
	          Der Administrator &bdquo;' . $ADMIN_NAME . '&rdquo; und das Passwort wurden hinzugefügt.</p>
	           <p><a href="register/anmeldung.php">Weiter zur Anmeldung</a></p>';
	  }
	  else {
	   echo '<p class="ko">&#10008; Der Administrator konnte nicht hinzugefügt werden!</p>';
	  }
	 }
	 else {
	  echo '<p class="ko">&#10008; Fehler beim erstellen der Tabelle &bdquo; benutzerverwaltung &rdquo;!</p>';
	 }
	 
	 if ($db->query("CREATE TABLE IF NOT EXISTS `db_kalender` (
                         `start` DATETIME NOT NULL,
                         `ende` DATETIME NOT NULL,
                         `name` VARCHAR(30) COLLATE utf8_unicode_ci NOT NULL,
                         `event` VARCHAR(50) COLLATE utf8mb4_unicode_ci NOT NULL,
                         `beschreibung` TEXT COLLATE utf8mb4_unicode_ci NOT NULL,
                         `prioritaet` TINYINT(1) NOT NULL DEFAULT '0',
                         `wiederholung` TINYINT(1) NOT NULL DEFAULT '0',
                         `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY
                        ) ENGINE = InnoDB DEFAULT CHARSET=utf8")){
            echo '<p class="ok">&#10004; Die Tabelle &bdquo; db_kalender &rdquo; wurde erstellt.</p>';						
	}else {
	  echo '<p class="ko">&#10008; Fehler beim erstellen der Tabelle &bdquo; db_kalender &rdquo;!</p>';
	 }
	 
	if ($db->query("CREATE TABLE IF NOT EXISTS `kunden` (  
	  `kundennummer` INT NOT NULL AUTO_INCREMENT PRIMARY KEY, 
	  `firma` VARCHAR(100) COLLATE utf8_unicode_ci, 
	  `name` VARCHAR(100) COLLATE utf8_unicode_ci NOT NULL, 
	  `vorname` VARCHAR(100) COLLATE utf8_unicode_ci NOT NULL, 
	  `email` VARCHAR(100) COLLATE utf8_unicode_ci NOT NULL, 
	  `telefon` VARCHAR(100) COLLATE utf8_unicode_ci NOT NULL, 
	  `strasse` VARCHAR(100) COLLATE utf8_unicode_ci NOT NULL, 
	  `hausnummer` VARCHAR(100) COLLATE utf8_unicode_ci NOT NULL, 
	  `plz` VARCHAR(100) COLLATE utf8_unicode_ci NOT NULL, 
	  `stadt` VARCHAR(100) COLLATE utf8_unicode_ci NOT NULL,
	  `infos` MEDIUMTEXT COLLATE utf8_unicode_ci 
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;")) {
		  echo '<p class="ok">&#10004; Die Tabelle Kunden wurde erstellt.</p>';

		  
		 }
		 else {
		  echo '<p class="ko">&#10008; Fehler beim erstellen der Tabelle Kunden!</p>';
		 }		 

	if ($db->query("CREATE TABLE IF NOT EXISTS `auftragsanfragen` (  
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `kundennummer` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
	  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
	  `vorname` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
	  `firma` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
	  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
	  `telefonnummer` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
	  `strasse` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
	  `hausnummer` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
	  `plz` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
	  `ort` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
	  `nachricht` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;")) {
		  echo '<p class="ok">&#10004; Die Tabelle auftragsanfragen wurde erstellt.</p>';

		  
		 }
		 else {
		  echo '<p class="ko">&#10008; Fehler beim erstellen der Tabelle auftragsanfragen!</p>';
		 }		



		if ($db->query("CREATE TABLE IF NOT EXISTS `nachrichten` (
		                                     `id` int(11) NOT NULL AUTO_INCREMENT,
		                                     `kategorie` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
		                                     `anzeige` tinyint(1) NOT NULL,
		                                     `titel` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
		                                     `autor` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
		                                     `nachricht` text COLLATE utf8_unicode_ci NOT NULL,
		                                     `bild` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
		                                     `url` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
		                                     `pin` tinyint(1) NOT NULL,
		                                     `datum` datetime NOT NULL,
		                                     PRIMARY KEY (`id`)
		                                   ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci")) {
		 echo '<h4>Die Datenbank-Tabelle nachrichten wurde erstellt.</h4>
		 <p>&raquo; <a href="nachrichten_editor.php">Weiter zur Anmeldung</a></p>';
		}
		else {
		 echo '<h4>Fehler beim erstellen der Datenbank-Tabelle nachrichten.</h4>';
		 print_r($verbindung->errorInfo());
		}






  }
 // Bereich für die Eingabe der Daten  
else
  {
	echo '
		<form action="#" method="post">
		<h1>Installation des Content Management Systems</h1>
		<p>
			Hier beginnt die Installation der Software. Bitte geben Sie die Zugangsdaten Ihrer Datenbank die Ihnen von Ihrem Hoster (Webseiten Provider) ein und erstellen sich einen Administrator. 
		</p>
		<p>
			Bei Fragen oder Problemen kontaktieren Sie bitte das Team von Softnetz.
		</p>
		';
	echo "
		<p>&nbsp;</p>
		<div class='spalte'>
		<strong>Datenbank Einstellung</strong>
		<p>
		<label> Hostadresse:<br>
		<input type='text' name='hostadresse' size='35' required>			
		</label>
		 <br><span class='hilfetext'> Bitte geben Sie die Hostadresse der Datenbank an!<br> Meist ist es localhost .</span>
		</p>

		<p>
		 <label> Datenbankname:<br>
		  <input type='text' name='datenbankname'  size='35' required>
		 </label>
		 <br><span class='hilfetext'> Bitte geben Sie den Namen der Datenbank an! </span>
		</p>

		<p>
		 <label> Benutzername:		
		  <input type='text' name='benutzername'  size='35' required>
		 </label>
		 <br><span class='hilfetext'> Bitte geben Sie den Benutzer der Datenbank ein! </span>
		</p>

		<p>
		 <label> Passwort:		
		  <input type='password' name='passwort' size='35' required>
		 </label>
		 <br><span class='hilfetext'> Bitte geben Sie das Passwort des Datenbankbenutzers ein! </span>
		</p>	
		</div>
		";
		
		
	echo '		
		
		
		<div class="spalte">
		<strong>Hinzufügen eines Administrators</strong>
		<p>
		<label>Administrator Name<br>
		<input type="text" name="admin_name" size="35" required/></label>
		<br><span class="hilfetext"> Bitte geben Sie den Namen des Administrator ein! </span>
		</p>
		
		<p>
		<label>Passwort<br>
		<input type="text" name="admin_passwort" size="35" required/></label>
		<br><span class="hilfetext"> Bitte geben Sie ein Passwort für den Administrator ein! </span>
		</p>
		<p>
		<label>Administrator E-Mail<br>
		<input type="email" name="admin_email" size="35" required/></label>
		<br><span class="hilfetext"> Bitte geben Sie die E-Mail des Administrator ein! </span>
		</p>
		<p>
		<label>Neue Benutzerkonten vom Administrator freischalten lassen?<br>
		 Ja <input type="radio" name="freischalten" value="ja" required/> Nein <input type="radio" name="freischalten" value="nein" /></label>
		 <br><span class="hilfetext"> Bitte auswählen! </span>
		 </p>
		 <p><label>Hompage Name<br><input type="text" name="homepage_name" size="35" required/></label><br><span class="hilfetext"> Bitte geben Sie die Domain ein! </span></p>
		</div>
		<p>&nbsp;</p>
		<p>Bitte kontrollieren Sie nochmal ihre Eingaben bevor Sie auf Absenden klicken.</p>

		<input type="Submit" value="Absenden" />
		</form>	
	
	';
  }


?>
</div>

</body>
</html>
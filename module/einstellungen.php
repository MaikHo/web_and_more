  <?php
	 // Verbindungsdaten zur Datenbank
	$DB_HOST = "mysql04.manitu.net"; // Host-Adresse
	$DB_NAME = "db21019"; // Datenbankname
	$DB_USER = "u21019"; // Benutzername
	$DB_PASSWORD = "nsEtERZzC7ab"; // Passwort
	
	$HOMEPAGE_NAME = "softnetz.de";
	
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


	
	
	
	
	
	
	?>
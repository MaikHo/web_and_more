<?php
spl_autoload_register(function ($class){
	if( file_exists("../../classes/$class.class.php")){
		include_once("../../classes/$class.class.php");			
	}
	else{
		$fehlermeldung = 'Die Klasse '.$class.' Existiert nicht. Bitte dem Support melden';
		echo $fehlermeldung;
		Log::write_log($fehlermeldung);
	}
});
/*
 *  Webseitenschutz
 *  Diesen PHP-Code für alle Seiten benutzen
 *  die geschützt werden sollen.
 */
File::include_php_file("../../inc_function/error_reporting.php");	

session_start();
if (!isset($_SESSION["login"])) {
 header("Location: ../../register/anmeldung.php");
 exit;
}
?>
<!DOCTYPE html>
<html lang="de">
 <head>
  <title>Event-Kalender - Demo</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="stylesheet" href="kalender/kalenderstyle.css">
  <script type="text/javascript" src="kalender/kalenderscript.js"></script>

 </head>
<body>

<div id="kalender"></div>

<!-- 
Event-Kalender - 
Die Scripte verwenden die Zeichenkodierung "UTF-8" um Umlaute etc. richtig darzustellen!
Siehe: https://werner-zenk/tipps/schriftzeichen_richtig_darstellen.php
 -->

</body>
</html>


<?php
/*
 * Webseitenschutz - anmeldung.php (utf-8)
 * - https://werner-zenk.de
 */
session_cache_limiter(20);
session_start();
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

// Anmeldung
if ($_SERVER["REQUEST_METHOD"] == "POST" &&
    $_POST["benutzername"] != "") {

 // Anmeldeversuche
 if (!isset($_SESSION["versuche"])) {
  $_SESSION["versuche"] = 1;
 }
 else {
  $_SESSION["versuche"]++;
 }

 // Leerzeichen entfernen
 $_POST["benutzername"] = trim($_POST["benutzername"]);
 $_POST["passwort"] = trim($_POST["passwort"]);

 // Benutzername und Passwort auslesen
 $select = $db->prepare("SELECT `benutzername`, `passwort`, `sperre`, `email`, `rolle`
                                       FROM `benutzerverwaltung`
                                       WHERE `benutzername` = :benutzername");
 $select->execute([':benutzername' => $_POST["benutzername"]]);
 $reg = $select->fetch();
 if ($select->rowCount() > 0) {

  // Name, Passwort und Anmeldeversuche überprüfen
  if ($reg["benutzername"] == $_POST["benutzername"] &&
      password_verify($_POST["passwort"], $reg["passwort"]) &&
      $reg["sperre"] == "1" &&
      $_SESSION["versuche"] <= $ANMELDEVERSUCHE) {

   // Session setzen
   unset($_SESSION["versuche"]);
   session_regenerate_id();
   $_SESSION["login"] = true;
   $_SESSION["benutzer"] = $reg["benutzername"];
   
   $_SESSION["Admin"] = $reg["rolle"] == "Administrator" ? true : false;
   $_SESSION["Moderator"] = $reg["rolle"] == "Moderator" ? true : false;
   if($_SESSION["Admin"] == true){
   		$_SESSION["Moderator"] = true;
   }
   
   if($_SESSION["Moderator"] == true){
   		$_SESSION["autor"] = $reg["benutzername"];
   }   

   // Weiterleitung zur geschützten Seite
   header("Location: ../benutzer/hauptseite.php");
   exit;
  }
  else {
   $fehler = true;
  }
 }
}

// Abmeldung
if (isset($_SESSION["login"]) && isset($_GET["abmeldung"])) {

 // Letzter Besuch eintragen
 $db->query("UPDATE `benutzerverwaltung`
                      SET `letzterbesuch` = NOW()
                      WHERE `benutzername` = '" . $_SESSION["benutzer"] . "'");

 // Session und Cookies löschen
 unset($_SESSION["benutzer"]);
 $_SESSION = [];
 if (ini_get("session.use_cookies")) {
  $params = session_get_cookie_params();
  setcookie(session_name(), '', time() - 42000, $params["path"],
   $params["domain"], $params["secure"], $params["httponly"]);
 }
 session_destroy();

 // Weiterleitung zur Anmeldung
 header("Location: anmeldung.php?abmeldung_ok");
 exit;
}
?>
<!DOCTYPE html>
<html lang="de">
 <head>
  <meta charset="utf-8">
  <title>Anmeldung</title>
  <meta name="robots" content="noindex">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <link rel="stylesheet" type="text/css" media="screen" href="../css/desktop.css">
  <link rel="stylesheet" type="text/css" media="screen" href="../css/style.css">

  
 </head>
<body>
<div class="hilfscontainer">
<nav>
<ul>
	<li class="li_nav"><a href="../index.php">Startseite</a></li>
</ul>  
</nav>

<article>

<h1>Anmeldung</h1>

<form action="anmeldung.php" method="post" accept-charset="UTF-8">
<p>
 <label>Benutzername: <span class="pflichtfeld">&#10034;</span> <br>
 <input type="text" name="benutzername" size="40" maxlength="35" autofocus="autofocus"></label><br>
 <span class="hilfetext">Geben Sie hier Ihren Benutzernamen ein.</span>
</p>

<p>
 <label>Passwort: <span class="pflichtfeld">&#10034;</span> <br>
 <input type="password" name="passwort" size="25"></label> 
 <a href="passwort_vergessen.php"><small>Passwort vergessen</small></a><br>
 <span class="hilfetext">Geben Sie hier Ihr Passwort ein.</span>
</p>

 <p>
  <input type="submit" value="Anmelden">
 </p>
</form>

<?php
if (isset($_GET["abmeldung_ok"])) {
 echo '<p class="ok">&#10004; Sie wurden erfolgreich abgemeldet.</p>';
}
if (isset($fehler) && $_SESSION["versuche"] < $ANMELDEVERSUCHE) {
 echo '<p class="ko">&#10008; Der Benutzername oder das Passwort ist falsch!<br>
         Anmeldeversuch ' . $_SESSION["versuche"] . ' von ' . $ANMELDEVERSUCHE . '.</p>';
}
if (isset($_SESSION["versuche"])) {
 if ($_SESSION["versuche"] >= $ANMELDEVERSUCHE) {
  echo '<p class="ko">&#10008; Es stehen Ihnen keine weiteren Anmeldeversuche zur Verfügung!</p>';
 }
}
?>

</article>

</div><!-- Hilfscontainer-->

</body>
</html>
<?php
/*
 * Webseitenschutz - passwort_aendern.php (utf-8)
 * - https://werner-zenk.de
 */

session_start();
if (!isset($_SESSION["login"])) {
 header("Location: anmeldung.php");
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
$ausgabe = '<p>Hier können Sie Ihr Passwort ändern, das neue Passwort ist bei der nächsten Anmeldung aktiviert.</p>';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

 // Eingaben sind nicht leer
 if (!empty($_POST["benutzername"]) &&
     !empty($_POST["passwort"]) &&
     !empty($_POST["passwortNeu"])) {

  // Länge des neuen Passwort überprüfen
  if (strlen($_POST["passwortNeu"]) >= $PASSWORT_MIN) {

   // Benutzer aus der DB-Tabelle auslesen
   $select = $db->prepare("SELECT `benutzername`, `passwort`, `id`
                                   FROM `benutzerverwaltung` WHERE `benutzername` = :benutzername");
   $select->execute([":benutzername"=>$_POST["benutzername"]]);
   $status = $select->fetch();

   // Benutzer gefunden / Passwort überprüfen
   if ($select->rowCount() > 0 &&
       password_verify($_POST["passwort"], $status["passwort"])) {

    // Passwort aktualisieren
    $update = $db->prepare("UPDATE `benutzerverwaltung`
                                           SET 
                                           `passwort` = :passwort
                                          WHERE
                                            `id` = :id");
     if ($update->execute([":id" => $status["id"],
                                       ":passwort" => password_hash($_POST["passwortNeu"],PASSWORD_BCRYPT)])) {
      $ausgabe = '<p class="ok">&#10004; Das Passwort wurde aktualisiert.<br>Merken Sie sich das Passwort oder schreiben Sie dieses auf.</p>';
     }
   }
   else {
    $ausgabe = '<p class="ko">&#10008; Der <i>Benutzername</i> und das <i>Passwort</i> stimmen nicht überein!</p>';
   }
  }
  else {
   $ausgabe = '<p class="ko">&#10008; Das neue Passwort ist zu kurz!</p>';
  }
 }
 else {
  $ausgabe = '<p class="ko">&#10008; Bitte alle Formularfelder ausfüllen!</p>';
 }
}

?>
<!DOCTYPE html>
<html lang="de">
 <head>
  <meta charset="utf-8">
  <title>Passwort ändern</title>
  <link rel="stylesheet" type="text/css" media="screen" href="../css/desktop.css">
  <link rel="stylesheet" type="text/css" media="screen" href="../css/style.css">

 </head>
<body>
<div class="hilfscontainer">

<nav>
<ul>
 <li class="li_nav"><a href="../index.php">Startseite</a></li>
 <li class="li_nav"><a href="../benutzer/hauptseite.php">Hauptseite</a></li>
 <li class="li_nav"><a href="../register/anmeldung.php?abmeldung">Abmelden</a></li>	
</ul>
</nav>

<article>

<h1>Passwort ändern</h1>

<?=$ausgabe;?>

<form method="post" accept-charset="UTF-8">
<p>
 <label>Benutzername: <span class="pflichtfeld">&#10034;</span><br>
 <input type="text" name="benutzername" size="40" maxlength="35" required="required" autofocus="autofocus" autocomplete="username"></label><br>
 <span class="hilfetext">Geben Sie hier Ihren Benutzernamen ein.</span>
</p>

<p>
 <label>Altes Passwort: <span class="pflichtfeld">&#10034;</span><br>
 <input type="password" name="passwort" size="25" required="required" autocomplete="current-password"></label><br>
 <span class="hilfetext">Geben Sie hier Ihr altes Passwort ein.</span>
</p>

<p>
 <label>Neues Passwort: <span class="pflichtfeld">&#10034;</span><br>
 <input type="password" name="passwortNeu" size="25" required="required"></label><br>
 <span class="hilfetext">Geben Sie hier Ihr neues Passwort ein (min.: <?=$PASSWORT_MIN;?> Zeichen).</span>
</p>

<p><input type="submit" value="Passwort ändern"></p>
</form>

</article>

</div>

</body>
</html>
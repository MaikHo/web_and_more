<?php
/*
 * Webseitenschutz - passwort_vergessen.php (utf-8)
 * - https://werner-zenk.de
 */
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

$ausgabe = '<p>Hier können Sie sich ein neues Passwort generieren lassen. Das Passwort wird an Ihre <u>registrierte</u> E-Mail-Adresse gesendet.</p>';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

 // Eingaben sind nicht leer
 if (!empty($_POST["benutzername"]) &&
     !empty($_POST["email"])) {

  // Benutzer aus der DB-Tabelle auslesen
  $select = $db->prepare("SELECT `benutzername`, `email`, `id`
                                  FROM `benutzerverwaltung`
                                  WHERE `benutzername` = :benutzername
                                        AND `email` = :email");
  $select->execute([":benutzername" => $_POST["benutzername"],
                              ":email" => $_POST["email"]]);
  $status = $select->fetch();

  // Benutzer gefunden
  if ($select->rowCount() > 0) {

   // Mnemonisches Passwort erzeugen
   $passwort = "";
   $conso = array("b", "c", "d", "f", "g", "h", "j", "k", "l", "m", "n", "p", "r", "s", "t", "v", "w", "x", "y", "z", "B", "C", "D", "F", "G", "H", "J", "K", "L", "M", "N", "P", "R", "S", "T", "V", "W", "X", "Y", "Z");
   $vocal = array("a", "e", "i", "o", "u");
   $l = mt_rand(3, 4);
   for ($i = 1; $i <= $l; $i++) {
    $passwort .= $conso[mt_rand(0, 38)] . $vocal[mt_rand(0, 4)];
   }
   $passwort . mt_rand(1, 99);

   // Passwort in der DB-Tabelle aktualisieren
   $update = $db->prepare("UPDATE `benutzerverwaltung`
                                          SET 
                                          `passwort` = :passwort
                                         WHERE
                                           `id` = :id");
   if ($update->execute([":id" => $status["id"],
                                    ":passwort" => password_hash($passwort,PASSWORD_BCRYPT)])) {

    // E-Mail an den Benutzer senden
    $mailtext = file_get_contents("passwort_vergessen.txt");
    $mailtext = strtr($mailtext, ["{:HOMEPAGE:}" => $HOMEPAGE_NAME, 
                                              "{:DATUM:}" => date("d.m.Y \u\m H:i") . " Uhr",
                                              "{:PASSWORT:}" => $passwort,
                                              "{:ANMELDUNG:}" => $PFAD . "/anmeldung.php", 
                                              "{:ADMIN:}" => $ADMIN_NAME]);
    // PHPMailer
    require_once "../PHPMailer-master/PHPMailerAutoload.php";
    $Mailer = new PHPMailer();
    $Mailer->CharSet = "UTF-8";
    $Mailer->setFrom($ADMIN_EMAIL, $HOMEPAGE_NAME);
    $Mailer->addAddress($status["email"], $status["benutzername"]);
    $Mailer->Subject = "Passwort-Anforderung - " . $HOMEPAGE_NAME;
    $Mailer->Body = $mailtext;
    if ($Mailer->Send()) {
     $ausgabe = '<p class="ok">&#10004; Ihr neues Passwort wurde mit weiteren Anweisungen an Ihre registrierte E-Mail-Adresse versandt.</p>';
    }
   }
  }
  else {
   $ausgabe = '<p class="ko">&#10008; Der <i>Benutzername</i> und die <i>E-Mail-Adresse</i> stimmen nicht überein!</p>';
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
  <title>Passwort vergessen</title>
  <meta name="robots" content="noindex">
  <link rel="stylesheet" type="text/css" media="screen" href="../css/desktop.css">
  <link rel="stylesheet" type="text/css" media="screen" href="../css/style.css">

 </head>
<body>
<div class="hilfscontainer">


<nav>
<ul>
 <li class="li_nav"><a href="../index.php">Startseite</a></li>
 <li class="li_nav"><a href="anmeldung.php">Anmeldung</a></li>
 
</ul>
</nav>

<article>

<h1>Passwort vergessen</h1>

<?=$ausgabe;?>

<form method="post" accept-charset="UTF-8">
<p>
 <label>Benutzername: <span class="pflichtfeld">&#10034;</span><br>
 <input type="text" name="benutzername" size="40" maxlength="35" autocomplete="username" required="required" autofocus="autofocus"></label><br>
 <span class="hilfetext">Geben Sie hier Ihren Benutzernamen ein.</span>
</p>

<p>
 <label>E-Mail: <span class="pflichtfeld">&#10034;</span><br>
 <input type="mail" name="email" size="35" required="required"></label><br>
 <span class="hilfetext">Geben Sie hier Ihre registrierte E-Mail-Adresse ein.</span>
</p>

<p>
 <input type="submit" value="Absenden">
</p>
</form>

</article>


</div>
</body>
</html>
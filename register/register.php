<?php
/*
 *  Webseitenschutz
 *  Diesen PHP-Code für alle Seiten benutzen
 *  die geschützt werden sollen.
 */
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

?>
<!DOCTYPE html>
<html lang="de">
 <head>
  <meta charset="utf-8">
  <title>Registrierung</title>
  <meta name="robots" content="noindex">
  <link rel="stylesheet" type="text/css" media="screen" href="../css/desktop.css">
  <link rel="stylesheet" type="text/css" media="screen" href="../css/style.css">

</head>
<body>
<div class="hilfscontainer">


<nav>
<ul>
	<li class="li_nav"><a href="../index.php">Home</a></li>
	<li class="li_nav"><a href="../benutzer/hauptseite.php">Arbeitsbereich</a></li>
</ul> 
</nav>

<article>

<h1>Registrierung</h1>

<?php
/*
 * Webseitenschutz - register.php (utf-8)
 * - https://werner-zenk.de
 */




// Link-Bestätigung der Registrierung (über E-Mail)
if (isset($_GET["uid"])) {
 if (ctype_digit($_GET["uid"])) {
  $uid = strip_tags(trim($_GET["uid"]));

  // Überprüfen ob das Eintragsdatum in der DB vorhanden ist
  $select = $db->prepare("SELECT `register` FROM `benutzerverwaltung` WHERE `register` = :uid AND `sperre` = '0'");
  $select->execute([':uid'=>$uid]);
  $reg = $select->fetch();
  if ($select->rowCount() > 0) { // Datensatz vorhanden

   // Ablauffrist > Freischalttage
   if ((floor((time() - $reg["register"]) / 86400)) > $FREISCHALTTAGE) {

    // Registrierung abbrechen
    $db->query("DELETE FROM `benutzerverwaltung` WHERE `register` = '" . $reg["register"] . "'");
    echo '<h2 class="ko">Fehler bei der Registrierung!</h2>
     <p>&#10008; Die Zeit um Ihre Registrierung freizuschalten ist leider abgelaufen (' . $FREISCHALTTAGE .
     ' Tage).</p><p><a href="register.php">Bitte registrieren Sie Sich erneut</a></p>';
   }
   else {

    // Registrierung abschließen
    $db->query("UPDATE `benutzerverwaltung` SET `email_ok` = '1', `sperre` = '1'  WHERE `register` = '" . $reg["register"] . "'");

    echo '<h2 class="ok">Registrierung erfolgreich</h2>
     <p>&#10004; Ihre Registrierung wurde erfolgreich abgeschlossen. 
     Sie können Sich nun, mit Ihrem Benutzernamen und Passwort anmelden. 
     <a href="anmeldung.php">Zur Anmeldung</a></p>';
   }
  }
  else {
   echo '<p class="ko">&#10008; Sie müssen sich <a href="register.php">registrieren</a></p>';
  }
 }
}
else {

 // Registrierungsformular
 $benutzername = isset($_POST["benutzername"]) ? $_POST["benutzername"] : "";
 $passwortA = isset($_POST["passwortA"]) ? $_POST["passwortA"] : "";
 $passwortB = isset($_POST["passwortB"]) ? $_POST["passwortB"] : "";
 $email = isset($_POST["email"]) ? $_POST["email"] : "";
 //$nutzungsbedingung = isset($_POST["nutzungsbedingung"]) ? $_POST["nutzungsbedingung"] : "";
 $nutzungsbedingungCk = isset($_POST["nutzungsbedingung"]) ? " checked='checked'" : "";

// Sicherheitsabfrage
$Z0 = [mt_rand(1, 9), mt_rand(1, 9)];
$Z1 = max($Z0); $Z2 = min($Z0);
$Spam = $Z1 . " &#43; &#" . (48 + $Z2) . ";";
$Schutz = md5($Z1 + $Z2);

 // Benutzereingabe überprüfen
 $fehler = array("benutzername"=>"","passwort"=>"","email"=>"",/*"nutzungsbedingung"=>"",*/"spam"=>"");
 if ($_SERVER['REQUEST_METHOD'] == "POST") {
  $select = $db->prepare("SELECT `benutzername`
                                       FROM `benutzerverwaltung`
                                       WHERE `benutzername` = :benutzername");
  $select->execute([':benutzername' => $benutzername]);
  $select->fetch();
  $fehler["benutzername"] = strlen($benutzername) < 4 ? "<br>Der Benutzername ist zu kurz!" : "";
  $fehler["benutzername"] = strlen($benutzername) > 35 ? "<br>Der Benutzername ist zu lang!" : $fehler["benutzername"];
  $fehler["benutzername"] = $benutzername != preg_replace("/[^a-zäöüß 0-9]/i", "", trim($benutzername)) ? "<br>Der Benutzername darf nur Buchstaben und Ziffern enthalten!" : $fehler["benutzername"];
  $fehler["benutzername"] = $select->rowCount() > 0 ? "<br>Der Benutzername ist bereits vorhanden!" : $fehler["benutzername"];
  $fehler["passwort"] = strlen($_POST["passwortA"]) < $PASSWORT_MIN || strlen($_POST["passwortB"]) < $PASSWORT_MIN  ? "<br>Das Passwort ist zu kurz!" : "";
  $fehler["passwort"] = strlen($_POST["passwortA"]) > 70 || strlen($_POST["passwortB"]) > 70  ? "<br>Das Passwort ist zu lang!" : $fehler["passwort"];
  $fehler["passwort"] = $_POST["passwortA"] != $_POST["passwortB"]  ? "<br>Die Passwörter sind unterschiedlich!" : $fehler["passwort"];
  $fehler["email"] = !filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)  ? "<br>Die E-Mail-Adresse ist fehlerhaft!" : "";
  //$fehler["nutzungsbedingung"] = !isset($_POST["nutzungsbedingung"]) ? "<br>Die Nutzungsbedingungen wurden nicht bestätigt!" : "";
  $fehler["spam"] = md5($_POST["zip"]) != $_POST["zip2"]  ? "<br>Die Sicherheitsabfrage ist leider falsch!" : "";
 }

// Formular erstellen
$formular = '
<form action="register.php" method="post">
 <p>
  <label>Benutzername:
  <span class="ko">&#10034; ' . $fehler['benutzername'] . '</span> <br>
  <input type="text" name="benutzername" value="' . $benutzername . '" size="40" maxlength="35" required="required" autofocus="autofocus">
  </label>
  <br><span class="hilfetext"> Geben Sie hier Ihren gewünschten Benutzernamen ein (min.: 4 Zeichen). </span>
 </p>

 <p>
  <label>Passwort:
  <span class="ko">&#10034; ' . $fehler['passwort'] . '</span> <br>
  <input type="password" name="passwortA" value="' . $passwortA . '" size="25" required="required">
  </label>
  <br><span class="hilfetext"> Geben Sie hier ein Passwort ein (min.: ' . $PASSWORT_MIN . ' Zeichen). <br>
   Achten Sie bei dem Passwort auf die Groß-/Kleinschreibung!</span>
 </p>

 <p>
  <label>Passwort wiederholen:
  <span class="ko">&#10034; ' . $fehler['passwort'] . '</span> <br>
  <input type="password" name="passwortB" value="' . $passwortB . '" size="25" required="required">
  </label>
  <br><span class="hilfetext"> Wiederholen Sie das Passwort. </span>
 </p>

 <p>
  <label>E-Mail:
  <span class="ko">&#10034; ' . $fehler['email'] . '</span> <br>
  <input type="email" name="email" value="' . $email . '" size="40" required="required">
  </label><br>
<span class="hilfetext"> Geben Sie hier Ihre (gültige) E-Mail-Adresse ein. 
' . ($ADMINCHECK == 'ja' ? '' : '<br>
  Sie erhalten über E-Mail einen Link den Sie <u>innerhalb</u> von ' . $FREISCHALTTAGE . ' Tagen <br> 
  anklicken müssen, dann ist die Registrierung abgeschlossen.') . '</span>
 </p>
' . $fehler['spam'] .	// steht hier nur weil die Nutzungsbedingungen ausgeklammert sind
/* <p>
  <label>
  <input type="checkbox" name="nutzungsbedingung" value="Ja" ' . $nutzungsbedingungCk . ' required="required">
  Ich habe die <a href="nutzungsbedingungen.htm" target="_blank">Nutzungsbedingungen</a> gelesen <br>
  und bin damit einverstanden
  </label>
  <span class="ko">&#10034; ' . $fehler['nutzungsbedingung'] . '</span>
 </p>*/
'
 <p>
 <label>Sicherheitsabfrage:
 <span class="ko">&#10034; ' . $fehler['spam'] . '</span> <br>
 <em>' . $Spam . '</em> &#61; 
 <input type="text" name="zip" size="5" autocomplete="off" required="required">
 <input type="hidden" name="zip2" value="' . $Schutz . '">
 </label>
 <br><span class="hilfetext"> Lösen Sie die Rechenaufgabe. </span>
 </p>

 <p>
  <br>
  <label>
  <input type="submit" value="Registrieren">
  </label>
 </p>

 <p>
  <span class="ko">&#10034;</span> <small>Bitte füllen Sie alle Pflichtfelder aus!</small> 
 </p>
</form>';


 if ($_SERVER['REQUEST_METHOD'] == "POST") {
  // Keine Eingabefehler vorhanden
  if (implode("", $fehler) == "") {

   // Benutzer in die DB eintragen
   $zeitstempel = time();
   $passwort = password_hash($_POST["passwortA"], PASSWORD_BCRYPT);
   $insert = $db->prepare("INSERT INTO `benutzerverwaltung`
   SET
     `sperre`            = '0',
     `benutzername` = :benutzername,
     `email`             = :email,
     `email_ok`        = '0',
     `passwort`        = :passwort,
     `register`          = '" . $zeitstempel . "',
     `letzterbesuch`  = NOW()");
   if ($insert->execute([':benutzername'=>$benutzername,
                                   ':email'=>$email,
                                   ':passwort'=>$passwort])) {

    if ($ADMINCHECK == "nein") {

     // E-Mail an den Benutzer senden
     $mailtext = file_get_contents("benutzer_email.txt");
     $mailtext = strtr($mailtext, ["{:HOMEPAGE:}" => $HOMEPAGE_NAME, 
                                               "{:DATUM:}" => date("d.m.Y \u\m H:i", $zeitstempel) . " Uhr",
                                               "{:REGISTER:}" => $PFAD . "/register.php?uid=" . $zeitstempel, 
                                               "{:FREISCHALTTAGE:}" => $FREISCHALTTAGE,
                                               "{:ADMIN:}" => $ADMIN_NAME]);
     // PHPMailer
     require_once "../PHPMailer-master/PHPMailerAutoload.php";
     $Mailer = new PHPMailer();
     $Mailer->CharSet = "UTF-8";
     $Mailer->setFrom($ADMIN_EMAIL, $HOMEPAGE_NAME);
     $Mailer->addAddress($email, $benutzername);
     $Mailer->Subject = "Registrierung bei: " . $HOMEPAGE_NAME;
     $Mailer->Body = $mailtext;
     if ($Mailer->Send()) {

      // Nachricht ausgeben
      echo '<h2 class="ok">Vielen Dank für die Registrierung!</h2>
       <p>Merken Sie sich Ihren Benutzernamen und das Passwort oder schreiben Sie diese auf.</p>
       <p>Sie erhalten über E-Mail einen Link den Sie <u>innerhalb</u> von ' . $FREISCHALTTAGE . ' Tagen 
       anklicken müssen, dann ist die Registrierung abgeschlossen.</p>';
     }
    }
    else {

     // Nachricht ausgeben
     echo '<h2 class="ok">Vielen Dank für die Registrierung!</h2>
      <p>Merken Sie sich Ihren Benutzernamen und das Passwort oder schreiben Sie diese auf.</p>
      <p>Sie werden nach einer Überprüfung durch den <strong>Administrator</strong> freigeschaltet.</p>';
    }

     // E-Mail an den Admin senden
     $mailtext = file_get_contents("admin_email.txt");
     $mailtext = strtr($mailtext, ["{:HOMEPAGE:}" => $HOMEPAGE_NAME,
                                        "{:DATUM:}" => date("d.m.Y \u\m H:i", $zeitstempel) . " Uhr",
                                        "{:BENUTZER:}" => $benutzername,
                                        "{:FREI:}" => ($ADMINCHECK == "nein" ? "Benutzer (Link in der E-Mail)." : "Administrator."),
                                        "{:ADMIN:}" => $ADMIN_NAME]);
     // PHPMailer
     require_once "../PHPMailer-master/PHPMailerAutoload.php";
     $Mailer = new PHPMailer();
     $Mailer->CharSet = "UTF-8";
     $Mailer->setFrom($ADMIN_EMAIL, $HOMEPAGE_NAME);
     $Mailer->addAddress($ADMIN_EMAIL, $HOMEPAGE_NAME);
     $Mailer->Subject = "Neue Registrierung - " . $HOMEPAGE_NAME;
     $Mailer->Body = $mailtext;
     $Mailer->Send();
    }
   }
  else {
   // Eingabefehler und Formular anzeigen
   echo $formular;
  }
 }
 else {
  // Formular anzeigen
  echo $formular;
 }
}
?>

</article>


</div>
</body>
</html>
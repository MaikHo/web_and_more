<?php
/*
 *  Webseitenschutz (nur für den Admin zugänglich!)
 *  Diesen PHP-Code für alle Seiten benutzen
 *  die geschützt werden sollen.
 */
session_start();
if (!isset($_SESSION["login"]) ||
    $_SESSION["Admin"] == false) {
 header("Location: ../register/anmeldung.php");
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
<html>
 <head>
  <meta charset="utf-8">
   <meta name="robots" content="noindex">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title> Benutzerverwaltung </title>
  <link rel="stylesheet" type="text/css" media="screen" href="../css/desktop.css">
  <link rel="stylesheet" type="text/css" media="screen" href="../css/style.css">


  <style>
  table {
   border-spacing: 2px;
   width: 100%;
  }

  tr th {
   background-color: #D0D0D0;
  }

  th, td {
   border: solid 1px #BBBBBB;
   font-size: 0.90rem;
   font-weight: Normal;
   white-space: Nowrap;
  }

  tr:nth-child(even) {
   background-color: #FFFFFF;
  }

  tr:nth-child(odd) {
   background-color: #EBEBEB;
  }

  tr:hover {
   background-color: #EEF7FD;
  }

  td:hover {
   background-color: #E5F1FB;
  }

  td.spalte {
   font-size: 0.80rem;
   text-align: Center;
  }

  tr.markRow {
   box-shadow: Inset 0px 0px 10px 10px #E5F1FB;
  }

  input[type="number"] {
   width: 50px;
  }

  label.mark {
   display: Inline-Block;
   width: 100%;
  }

  span.aktiv {
   color: #00BB2F;
   font-weight: Bold;
  }

  span.inaktiv {
   color: #FF0000;
   font-weight: Bold;
  }
  </style>

  <script>
  // Ausgewählter Datensatz in das Formularfeld einfügen
  function auswahl(Benutzerkonto) {
   document.Form.benutzerkonto.value = Benutzerkonto;
  }

  // Tabellenzeile markieren
  function markRow(ID) {
   for (var i = 1; i <= document.getElementsByName("id").length; i++) {
    document.getElementById("r" + i).classList.remove("markRow");
   }
   if (ID != "x") {
    document.getElementById("r" + ID).classList.add("markRow");
   }
  }

  // Statusanzeige
  function anzeige(ID) {
   document.getElementById(ID).style.display=
    (document.getElementById(ID).style.display=="none") ? "block" : "none";
  }

  // Sicherheitsabfrage vor dem löschen
  function sicherheit() {
   if (document.getElementById("aktion").selectedIndex == 3) {
    if (confirm("Benutzerkonto löschen?")) {
     return true;
    }
    else {
     return false;
    }
   }
  }
  </script>

 </head>
<body>
<div class="hilfscontainer">


<nav>
<ul>
 <li class="li_nav"><a href="../index.php">Home</a></li>
 <li class="li_nav"><a href="../benutzer/hauptseite.php">Arbeitsbereich</a></li>
 <li class="li_nav"><a href="../register/anmeldung.php?abmeldung">Abmelden</a></li>
</ul>
</nav>

<article>

<h1>Benutzerverwaltung</h1>

<?php
/*
 * Webseitenschutz - benutzerverwaltung.php (utf-8)
 * - https://werner-zenk.de
 */

include_once "einstellungen.php";
$statuszeit = 5000; // Status-OK-Anzeige in Millisekunden (JavaScript)

if ($_SERVER['REQUEST_METHOD'] == "POST") {
 if (isset($_POST["aktion"])) {

  // Benutzerkonto aktualisieren
  if ($_POST["aktion"] == "0" ||
      $_POST["aktion"] == "1") {
   if (isset($_POST["id"])) {
    if ($_POST["id"] > "1") {
     $kommando = $db->prepare("UPDATE `benutzerverwaltung`
                                                   SET  `sperre` = :aktion
                                                   WHERE `id` = :id");
     if ($kommando->execute([':id' => $_POST["id"],
                                             ':aktion' => $_POST["aktion"]])) {
      echo '<p class="ok" id="meldung">&#10004; Das Benutzerkonto wurde aktualisiert.</p>
              <script>window.setTimeout("anzeige(\'meldung\')", ' . $statuszeit . ')</script>';
     }
    }
    else {
     echo '<p class="ko" id="meldung">&#10008; Das Admin.-Benutzerkonto kann nicht geändert werden!</p>
              <script>window.setTimeout("anzeige(\'meldung\')", ' . $statuszeit . ')</script>';
    }
   }
   else {
    echo '<p class="ko" id="meldung">&#10008; Es wurde kein Benutzerkonto zum ändern ausgewählt!</p>
             <script>window.setTimeout("anzeige(\'meldung\')", ' . $statuszeit . ')</script>';
   }
  }

  // Benutzerkonto löschen
  if ($_POST["aktion"] == "2") {
   if (isset($_POST["id"])) {
    if ($_POST["id"] > "1") {
     $kommando = $db->prepare("DELETE FROM `benutzerverwaltung`
                                                   WHERE id = :id");
     if ($kommando->execute([':id' => $_POST["id"]])) {
      echo '<p class="ok" id="meldung">&#10004;  Das Benutzerkonto wurde gelöscht.</p>
              <script>window.setTimeout("anzeige(\'meldung\')", ' . $statuszeit . ')</script>';
     }
    }
    else {
     echo '<p class="ko" id="meldung">&#10008; Das Admin.-Benutzerkonto kann nicht gelöscht werden!</p>
             <script>window.setTimeout("anzeige(\'meldung\')", ' . $statuszeit . ')</script>';
    }
   }
   else {
    echo '<p class="ko" id="meldung">&#10008; Es wurde kein Benutzerkonto zum löschen ausgewählt!</p>
            <script>window.setTimeout("anzeige(\'meldung\')", ' . $statuszeit . ')</script>';
   }
  }

  if ($_POST["aktion"] == "3" || $_POST["aktion"] == "4" || $_POST["aktion"] == "5") {
   if (isset($_POST["id"])) {
    if ($_POST["id"] > "1") {
     switch($_POST["aktion"]){
	 	case "3": $aktion = 'Benutzer';
	 		break;
	 	case "4": $aktion = 'Moderator';
	 		break;
	 	case "5": $aktion = 'Administrator';
	 		break;
	 	default: exit;
	 }     
     $kommando = $db->prepare("UPDATE `benutzerverwaltung`
                                                   SET  `rolle` = :aktion
                                                   WHERE `id` = :id");
     if ($kommando->execute([':id' => $_POST["id"],
                                             ':aktion' => $aktion])) {
      echo '<p class="ok" id="meldung">&#10004; Das Benutzerkonto wurde aktualisiert.</p>
              <script>window.setTimeout("anzeige(\'meldung\')", ' . $statuszeit . ')</script>';
     }







    }
    else {
     echo '<p class="ko" id="meldung">&#10008; Das Admin.-Benutzerkonto kann nicht geändert werden!</p>
              <script>window.setTimeout("anzeige(\'meldung\')", ' . $statuszeit . ')</script>';
    }
   }
   else {
    echo '<p class="ko" id="meldung">&#10008; Es wurde kein Benutzerkonto zum ändern ausgewählt!</p>
             <script>window.setTimeout("anzeige(\'meldung\')", ' . $statuszeit . ')</script>';
   }
  }


 }
}

// Anzeige und Begrenzung
$anzahl = $db->query("SELECT `id` FROM `benutzerverwaltung`");
$anzahl = count($anzahl->fetchAll());
$start = isset($_POST["start"]) ? (int)$_POST["start"] : 1;
$start = $start > $anzahl || $start < 1 ? 1 : $start;
$limit = isset($_POST["limit"]) ? (int)$_POST["limit"] : 10;
$limit = $limit > $anzahl || $limit < 1 ? $anzahl : $limit;

// SQL-Statement für den Filter
$filter = isset($_POST["filter"]) ? $_POST["filter"] : "";
$where = ($filter != "") ? 
 "WHERE `benutzername` LIKE '%" . $filter . "%' OR `email` LIKE '%" . $filter . "%' " :
 "";

// Datensätze auslesen
$select = $db->query("SELECT `id`, `sperre`, `benutzername`, `email`, `email_ok`, `passwort`, `register`, `letzterbesuch`, `rolle`
                                   FROM `benutzerverwaltung` " . $where . "
                                   ORDER BY `benutzername` ASC
                                   LIMIT " . ($start - 1) . "," . $limit);
$datensaetze = $select->fetchAll(PDO::FETCH_ASSOC);

// Formular
echo '<form name="Form" action="benutzerverwaltung.php" method="post" autocomplete="off" onSubmit="return sicherheit();">
<p>
 <label>Start: <input type="number" name="start" value="' . $start . '" min="1" max="' . $anzahl . '"></label> &nbsp;
 <label>Limit: <input type="number" name="limit" value="' . $limit . '" min="1" max="' . $anzahl . '"> / ' . $anzahl . '</label> &nbsp;
 <label>Filter: <input type="text" name="filter" value="' . $filter . '" size="20"></label> &nbsp; 
 <input type="submit" name="anzeigen" value="Anzeigen" formnovalidate="formnovalidate"  onClick="document.Form.aktion.selectedIndex = 0">
</p>
';

// Datensätze vorhanden
if ($select->rowCount() > 0) {

 // Tabellenkopf
 echo '<table>
 <tr>
  <th>#</th>
  <th>Benutzername</th>
  <th width="30%">E-Mail</th>
  <th width="20%">Registrierung</th>
  <th width="20%">Letzter Besuch</th>
  <th width="20%">Rechte</th>
 </tr>
 ';

 // Datensätze ausgeben
 $zaehler = $start;
 foreach ($datensaetze as $z => $datensatz) {
  sscanf($datensatz["letzterbesuch"], "%4s-%2s-%2s %5s", $jahr, $monat, $tag, $uhrzeit);
  echo '
  <tr id="r' . ($z + 1) . '">
   <td class="spalte"><label class="mark" for="n' . $datensatz["id"] . '">' . $zaehler . '</label></td>
   <td>
    <input type="radio" name="id" id="n' . $datensatz["id"] . '" value="' . $datensatz["id"] . '" required="required" onclick="auswahl(\'' . $datensatz["benutzername"] . '\'); markRow(' . ($z + 1) . ')"> 
    <label class="mark" for="n' . $datensatz["id"] . '">
    <span class="' . ($datensatz["sperre"] == "0" ? 'inaktiv">&#9940;' : 'aktiv">&#10004;') . "</span> " . $datensatz["benutzername"] . '
    </label>
   </td>
   <td>
    <label class="mark" for="n' . $datensatz["id"] . '">
    <span class="' . ($datensatz["email_ok"] == "0" ? 'inaktiv">&#10008;' : 'aktiv">&#10004;') . '</span> ' . $datensatz["email"] . '
    </label>
   </td>
   <td class="spalte">
    <label class="mark" for="n' . $datensatz["id"] . '">
    ' . date("d.m.Y H:i", $datensatz["register"]) . '
     </label>
    </td>
   <td class="spalte">
    <label class="mark" for="n' . $datensatz["id"] . '">
    ' . $tag . '.' . $monat . '.' . $jahr . ' ' . $uhrzeit . '
    </label>
   </td>
   <td class="spalte">
    <label class="mark" for="n' . $datensatz["id"] . '">
    ' .$datensatz["rolle"]. '
    </label>
   </td>
  </tr>
  ';
  $zaehler++;
 }
 echo '</table>';
}
else {
 echo '<p class="ko">&#10008; Keine Datensätze gefunden!</p>';
}
?>

<p>
 Benutzerkonto: <input type="text" name="benutzerkonto" size="30" readonly="readonly" disabled="disabled">
 <select name="aktion" id="aktion" size="1" required="required">
  <option></option>
  <option value="0">&#9940; Sperren</option>
  <option value="1">&#10004; Freischalten</option>
  <option value="2">&#10008; Löschen</option>
  <option value="3">&sect; Benutzer</option>
  <option value="4">&sect; Moderator</option>
  <option value="5">&sect; Administrator</option>
 </select>&nbsp; 
 <input type="reset" value="Reset" title="Auswahl zurücksetzen" onClick="markRow('x');">&nbsp; 
 <input type="submit"  value="Ausführen">
</p>

</form>

</article>

</div>

</body>
</html>
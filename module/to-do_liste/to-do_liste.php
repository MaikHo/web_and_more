<?php
/*
/* To-Do Liste mit Passwortschutz (utf-8) PHP 5.4+
 * 12.07.2016 - https://werner-wenk.de
 *
 * Bitte nehmen Sie per Hand keine Änderungen an der Datei:
 * "todo.sqt" vor, da dadurch die Datei unbrauchbar werden kann!
 */

session_start();
if (!isset($_SESSION["login"])) {
 header("Location: ../index.php");
 exit; 
}

session_regenerate_id();
  $_SESSION["TO-DO"] = true;



$password = "0000";
//$list = "todo.sqt";
$list = $_SESSION["benutzer"].".sqt";


$script = basename($_SERVER["SCRIPT_NAME"]);
//session_start();

if (isset($_POST["user"])):
 if ($_POST["user"] === $password):
  session_regenerate_id();
  $_SESSION["TO-DO"] = true;
  header("Location: " . $script);
 endif;
endif;

if (isset($_GET["logout"])):
 $_SESSION = array();
 session_destroy();
 header("Location: " . $script);
endif;

header("Content-Type: text/html; charset=UTF-8");
header("Cache-Control: no-cache, must-revalidate"); // I.Explorer/Edge

// Initial
if (!file_exists($list)):
 $db = new PDO('sqlite:' . $list);
 $db->exec("CREATE TABLE todo(`id` INTEGER PRIMARY KEY, `text` TEXT, `ok` CHAR(1))");
else:
 $db = new PDO('sqlite:' . $list);
endif;

// Insert
if (isset($_POST["text"], $_SESSION["TO-DO"])):
 if (strlen(trim($_POST["text"])) > 0):
  $db->prepare("INSERT INTO `todo` (`text`, `ok`) VALUES (:text, '0')")->execute([':text' => $_POST["text"]]);
 endif;
endif;

// Update
if (isset($_GET["update"], $_SESSION["TO-DO"])):
 $select = $db->prepare("SELECT `ok` FROM `todo` WHERE `id` = :id AND `ok` = '1'");
 $select->bindValue(':id', $_GET["update"]);
 $select->execute();
 $todo = $select->fetch();
 if ($todo["ok"] == "1"):
  $db->prepare("UPDATE `todo` SET `ok` = '0' WHERE `id` = :id")->execute([':id' => $_GET["update"]]);
 else:
  $db->prepare("UPDATE `todo` SET `ok` = '1' WHERE `id` = :id")->execute([':id' => $_GET["update"]]);
 endif;
endif;

// Delete
if (isset($_GET["delete"], $_SESSION["TO-DO"])):
 $db->prepare("DELETE FROM `todo` WHERE `id` = :id")->execute([':id' => $_GET["delete"]]);
endif;

// Delete All
if (isset($_GET["delete_all"], $_SESSION["TO-DO"])):
 $db->prepare("DELETE FROM `todo` WHERE `ok` = '1'")->execute();
endif;

// Sort
$a = ["a-z"=>" ORDER BY `text` COLLATE NOCASE ASC",
"z-a"=>" ORDER BY `text` COLLATE NOCASE DESC",
"0-9"=>" ORDER BY `id` ASC",
"9-0"=>" ORDER BY `id` DESC"];

if (isset($_GET["sort"])):
 $sql = $a[$_GET["sort"]];
 $srt = $_GET["sort"];
else:
 $srt = "0-9"; // Default
 $sql = $a[$srt];
endif;

// Select
if (isset($_GET["read"]) ||
    isset($_POST["text"]) ||
    isset($_GET["update"]) ||
    isset($_GET["sort"]) ||
    isset($_GET["delete_all"]) ||
    isset($_GET["delete"]) &&
    isset($_SESSION["TO-DO"])):
 $entries = $db->query("SELECT `id`, `text`, `ok` FROM `todo`" . $sql)->fetchAll(PDO::FETCH_ASSOC);
 if (count($entries) > 0):
   print '<div class="option"> [' . count($entries) . ']&emsp; <a href="javascript:sort(\'a-z\')" title="Aufsteigend sortieren">&#9650; A-Z</a> <a href="javascript:sort(\'z-a\')" title="Absteigend sortieren">&#9660; Z-A</a> &emsp;' .
    '<a href="javascript:sort(\'0-9\')" title="Aufsteigend sortieren">&#9650; 0-9</a> <a href="javascript:sort(\'9-0\')" title="Absteigend sortieren">&#9660; 9-0</a></div>';
  foreach ($entries as $entry):
   $ok = $entry["ok"] == '1' ? ' style="color: #167DC9; background-color: #0C3E63;"' : '';
   print '<div' . $ok . ' class="todo">' .
   '<span class="td"><span class="delete" onClick="del(\'' . $entry["id"] . '\',\'' . $srt . '\')" title="Aufgabe löschen">&#10006;</span></span>' .
   '<span class="text">' . $entry["text"] . '</span>' .
   '<span class="td"><span class="update" onClick="update(\'' . $entry["id"] . '\',\'' . $srt . '\')" title="Aufgabe als erledigt markieren">&#10004;</span></span>' .
   ' </div>';
  endforeach;
 else:
  print '<p>Keine Aufgaben vorhanden!</p>';
 endif;
 exit;
endif;
?>
<!DOCTYPE html>
<html lang="de">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TO-DO</title>

  <style>
  body {
   font-family: Verdana, Arial, Sans-Serif;
   font-size: 1rem;
   color: #FFF;
   margin: 1rem;
   cursor: Default;
   background-color: #092942;
  }

  form {
   color: #000;
   background-color: #FFF;
   padding: 8px;
   border-radius: 20px;
   white-space: Nowrap;
  }

  input[type="text"] {
   width: 90%;
   border: 0;
   font-size: 1.2rem;
  }

  input[type="password"] {
   width: 80%;
   border: 0;
   font-size: 1.2rem;
  }

  span.delete {
   font-size: 1.2rem;
   font-weight: Bold;
   color: #196CB0;
   background-color: #EEEEEE;
   padding: 5px 10px 5px 10px;
   border-radius: 20px;
   cursor: Pointer;
  }
  span.delete:hover {
   background-color: #DDDDDD;
  }

  span.text {
   display: Table-Cell;
   width: 99%;
   padding: 0 10px 0 10px;
  }

  span.update {
   font-size: 1.2rem;
   font-weight: Bold;
   color: #FFFFFF;
   background-color: #FCC453;
   padding: 5px 10px 5px 10px;
   border-radius: 20px;
   margin-right: 0.50rem;
   cursor: Pointer;
  }
  span.update:hover {
   background-color: #FBAC09;
  }

  span.td {
   display: Table-Cell;
   width: 1%;
   vertical-align: Middle;
  }

  span#insert, input[type="submit"] {
   font-size: 1.2rem;
   font-weight: Bold;
   color: #FFFFFF;
   background-color: #196CB0;
   padding: 5px 8px 5px 8px;
   border: 0px;
   border-radius: 20px;
   margin-right: 0.60rem;
   cursor: Pointer;
  }

  span#insert:hover, input[type="submit"]:hover {
   background-color: #1F87DC;
  }

  div.option {
   color: #FFF;
   padding: 10px;
   text-align: Center;
  }

  a:link, a:visited {
   color: #167DC9;
   text-decoration: None;
   padding: 5px;
   font-size: 1.1rem;
   font-weight: Bold;
  }

  a:link:hover {
   background-color: #EEEEEE;
  }

  div.todo:nth-child(even) {
   display: Table;
   color: #FFFFFF;
   background-color: #196CB0;
   padding: 10px 0 10px 7px;
   border-radius: 20px;
   margin-bottom: 0.3rem;
  }

  div.todo:nth-child(odd) {
   display: Table;
   color: #FFFFFF;
   background-color: #145B96;
   padding: 10px 0 10px 7px;
   border-radius: 20px;
   margin-bottom: 0.3rem;
  }
  </style>

<?php
if (!isset($_SESSION["login"])):
 die('</head><body>
<form action="' . $script . '" method="post">
 <label>
 <input type="password" name="user" autofocus="autofocus" placeholder="TO-DO - Passwort" required="required"></label> 
 <input type="submit" value="&#10149;">
</form>
</body></html>');
endif;
?>

  <script>
  "use strict";
  var xhr = new XMLHttpRequest();

  window.addEventListener('load', function() {
   document.getElementById("insert").addEventListener("click", insert);
   xhr.open("GET", "<?=$script;?>?read", true);
   xhr.send(null);
   xhr.onreadystatechange = response;
  });

  function insert() {
   xhr.open("POST", "<?=$script;?>", true);
   xhr.send(new FormData(document.getElementsByTagName("form")[0]));
   document.getElementById("text").value = "";
   xhr.onreadystatechange = response;
  }

  function update(ID, sort) {
   xhr.open("GET", "<?=$script;?>?update=" + ID + "&sort=" + sort, true);
   xhr.send(null);
   xhr.onreadystatechange = response;
  }

  function sort(TO) {
   xhr.open("GET", "<?=$script;?>?sort=" + TO, true);
   xhr.send(null);
   xhr.onreadystatechange = response;
  }

  function del(ID, sort) {
   if (confirm("Aufgabe löschen?")) {
    xhr.open("GET", "<?=$script;?>?delete=" + ID + "&sort=" + sort, true);
    xhr.send(null);
    xhr.onreadystatechange = response;
   }
  }

  function del_all() {
   if (confirm("Alle erledigten Aufgaben löschen?")) {
    xhr.open("GET", "<?=$script;?>?delete_all", true);
    xhr.send(null);
    xhr.onreadystatechange = response;
   }
  }

  function response() {
   if (xhr.readyState == 4 &&
       xhr.status == 200) {
     document.getElementsByTagName("section")[0].innerHTML = xhr.responseText;
    }
  }
  </script>

 </head>
<body>

<form action="javascript:insert()" id="form" autocomplete="off">
 <label>
  <input type="text" name="text" id="text" spellcheck="true" placeholder="TO-DO - Aufgabe eintragen ...">
 </label>
 <span id="insert" title="Aufgabe eintragen">&#10149;</span>
</form>

<section><noscript><p>JavaScript erforderlich!</p></noscript></section>

<div class="option">
<a href="javascript:del_all()" title="Alle erledigten Aufgaben löschen">&#9851;</a>
</div>

</body>
</html>
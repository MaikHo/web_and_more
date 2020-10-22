<?php
// damit es keine Probleme mit einer Session  gibt, davor einbinden
include "inc_function/autoload_class.php";
File::include_php_file("inc_function/error_reporting.php");
//session_cache_limiter(20);
// díe session cookie lebt 3 Stunden
session_set_cookie_params(10800);
session_start();
// Besucherzähler
Counter::add_counter();
File::include_php_file("config.php");
?>
<!DOCTYPE html>
<html lang="de">
 <head>
<?php
// holen der meta Daten für die Page
File::include_php_file(ADMIN_PFAD."einstellungen_web/meta.php");
?>
</head>
<body>
<?php 
include("inc_html/mini_html/kontaktleiste.html");
File::include_php_file("inc_html/nav.php"); 
?>
<noscript>Diese Homepage funktioniert leider nur mit Javascript. Ohne Javascript können keine Inhalte geladen und Funktionen genutzt werden.</noscript>
<div class="hilfscontainer">
<div id="hamburger"><span></span><span></span><span></span></div>
<div id="cookie_dialog"><div>Wir setzen Cookies zur Verbesserung unserer Website ein. Die ist zu lesen in unserer Datenschutzerklärung</div><button id="close_dialog">Schließen!</button></div>
<?php 
echo '<header>';
File::include_html_file("inc_html/header.html");
echo '</header>';
?>
<main>
<div id="loader"></div>
<?php
echo '<article id="load_html" class="editable_elements animate-bottom">';
File::include_html_file("inc_html/Home.html");
echo '</article>';
echo '</main>';
File::include_php_file("inc_html/footer.php");
?>
<script src="js/index.js"></script>
</div>
</body>
</html>
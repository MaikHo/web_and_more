<?php

spl_autoload_register(function ($class){
	if( file_exists("../classes/$class.class.php")){
		include_once("../classes/$class.class.php");			
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
File::include_php_file("../inc_function/error_reporting.php");	

session_start();
if (!isset($_SESSION["login"])) {
 header("Location: ../register/anmeldung.php");
 exit;
}
?>
<!DOCTYPE html>
<html lang="de">
 <head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Hauptseite</title>  
  <link rel="stylesheet" type="text/css" media="screen" href="../css/loader.css">
  
  <script src="../third_party/jquery/jquery-3.2.1.min.js"></script>

  
  
  
  
  

	

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
	<script src="https://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/js/bootstrap.min.js"></script>	
	

	
	<script src="js/jquery.hotkeys.js"></script>

	<script src="js/prettify.js"></script>
	
	<script src="js/bootstrap-wysiwyg.js"></script>
  
  
  
  <link rel="stylesheet" type="text/css" media="screen" href="../css/style.css">  




 <script src="../third_party/jquery-confirm-master/js/jquery-confirm.js"></script>

  
  
  
  
  
<style>





</style>

  
 </head>
<body>
<div id="loader"></div>
<div style="display:none;" id="hilfscontainer" class="hilfscontainer animate-bottom">
<div id="hamburger">
    <span></span>
    <span></span>
    <span></span>
</div>
<div id="ajax_meldung"></div>	

<?php

File::include_php_file("../inc_html/nav.php");


File::include_php_file("inc/section_01.php");

File::include_php_file("inc/section_02.php");

File::include_php_file("inc/section_03.php");

?>



        

        




	
</div><!-- hilfscontainer  -->
<script src="js/index.js"></script>
</body>
</html>
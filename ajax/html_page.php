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
if ( $_POST )
{
    
    $pagename = $_POST['page'];
    
    
    if (!preg_match("#^[a-zA-Z0-9 üöäÜÄÖ]+$#", $pagename)) {
	   Report::aktions_meldung('Bitte keine Sonderzeichen und kein ä,ö,ü wenn es geht benutzen! ', 'Erstellung der Seite '.$pagename, 'red');
	   exit; 
	}
    
    
    $datei = "../inc_html/".$pagename.".html";
    $rechte = '0755';

    
 
	// Daten
	$text = '  
<section id="'.$pagename.'">

 Lorem ipsum dolor sit amet Vitae vel at nisl augue sapien nibh semper Integer pretium Sed elit Aliquam id Nam Phasellus laoreet pede consequat Sed Vestibulum Nam id sed malesuada. 

 Quis tempus Lorem nibh wisi pellentesque Curabitur dui montes et senectus Ut urna eros sit dui tellus vitae leo Sed vel Curabitur Sed habitant parturient.

 Sollicitudin congue ligula tincidunt a gravida suscipit volutpat et Vestibulum congue sapien ligula sodales Ut Sed nec vel commodo elit massa feugiat sapien Maecenas semper. 

 Mauris nisl in velit in interdum urna egestas ante quis et congue pretium Suspendisse ac tincidunt et ornare sodales Duis felis tempor porttitor nascetur mus. 

 Pellentesque mi turpis tempus Curabitur vitae Aliquam Vestibulum elit justo nibh consequat vitae netus risus penatibus Morbi adipiscing metus Quisque ipsum tellus Praesent nunc elit. 
	
	
</section>
	
	
	
	';



	// Daten speichern
	$fh = fopen($datei, "w");
	if (fwrite($fh, $text)) {
		Report::aktions_meldung('Die Erstellung der Seite '.$pagename.' war erfolgreich! Rechte auf '.$rechte.' geändert.', 'Erstellung der Seite '.$pagename, 'green');
		/*		
		if (@chmod($datei, $rechte) == true) {
	        
	    } else {
	        Report::aktions_meldung('Die Erstellung der Seite '.$pagename.' war erfolgreich! Rechte auf '.$rechte.' konnten nicht geändert werden.', 'Erstellung der Seite '.$pagename, 'red');
	    }  */  
	}
	else {
		Report::aktions_meldung('Die Erstellung der Seite '.$pagename.' ist Fehlgeschlagen! ', 'Erstellung der Seite '.$pagename, 'red');
	}
	fclose($fh);   
    
    
    
	    
 
    // Datei wird nicht weiter ausgeführt
    exit;
}
?>



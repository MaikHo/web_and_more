<?php
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
if($_POST){
	$pagename = $_POST['page'];
	
	
	if($pagename != 'Home' && $pagename != "Datenschutz" && $pagename != "header" && $pagename != "Header"){
		//echo $pagename;
		$file = '../inc_html/'.$pagename.'.html';
		$newfile = '../cache/old_inc_html/'.date("Y_m_d_").$pagename.'.html';
		
		
		
		if(!file_exists($file)){
			Report::aktions_meldung('Die Seite '.$pagename.' ist nicht voehanden! Bitte überprüfen Sie die Schreibweise!', 'Löschen der Seite '.$pagename, 'red');
		}else{
			if (!copy($file, $newfile)) {
			    Report::aktions_meldung('Die Seite '.$pagename.' konnte nicht gelöscht werden!', 'Löschen der Seite '.$pagename, 'red');
			}else{
				unlink('../inc_html/'.$pagename.'.html');
				Report::aktions_meldung('Die Seite '.$pagename.' wurde gelöscht!', 'Löschen der Seite '.$pagename, 'green');
			}			
		}
				
	}else{
		Report::aktions_meldung('Die Seite '.$pagename.' darf nicht gelöscht werden', 'Löschen der Seite '.$pagename, 'red');
		
	}
	
	
	
	
}

?>
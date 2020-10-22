<?php


spl_autoload_register(function ($class){
	if( file_exists("./classes/$class.class.php")){
		include_once("./classes/$class.class.php");			
	}
	else{
		$fehlermeldung = 'Die Klasse '.$class.' Existiert nicht. Bitte dem Support melden';
		echo $fehlermeldung;
		Log::write_log($fehlermeldung);
	}
});
?>
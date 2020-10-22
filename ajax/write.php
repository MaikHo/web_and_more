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
    // und nun die Daten in eine Datei schreiben
    // Datei wird zum Schreiben geöffnet
    $pagename = $_POST['page'];
    
    echo $pagename;
    
    $datei = "../inc_html/".$pagename;
    $rechte = '0755';

	$data = $_POST['data'];
	
	$darf_nicht = 'script';
    $pos = strpos($data, $darf_nicht);
    
    

	if ($pos !== false) {
        Report::aktions_meldung('Das Wort script darf nicht im vorkommen!', 'Warnung', 'red');
        exit;
    } 
// Schreibrechte überprüfen und ändern 
//	if (decoct(fileperms($datei)) != 100777) { 
//	 chmod ($datei, 0777); 
//	} 


    


	// Sichergehen, dass die Datei existiert und beschreibbar ist.
	if (is_writable($datei)) {	    
	    if (!$handle = fopen($datei, "w")) {
	         Report::aktions_meldung('Kann die Datei '.$datei.' nicht öffnen zum beschreiben!', 'Warnung', 'red');
	         print "Kann die Datei $datei nicht öffnen";
	         exit;
	    }	    
	    if (!fwrite($handle, $data)) {
	        Report::aktions_meldung('Kann die Datei '.$datei.' nicht beschreiben!', 'Warnung', 'red');
	        exit;
	    }
	    Report::aktions_meldung('Die Datei '.$datei.' wurde neu beschrieben!', $pagename.' fertig gestellt', 'green');
	    fclose($handle);
	} else {
	    Report::aktions_meldung('Kann die Datei '.$datei.' nicht beschreiben!', 'Warnung', 'red');
	}

    

    // Datei wird nicht weiter ausgeführt
    exit;
}
?>
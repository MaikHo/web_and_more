<?php
/**
* Diese Klasse repäsentiert einen Counter
* 
* @author Maik Hoffmann
* @version 1.0
* @category page features 
*/
class Counter
{
	public static function add_counter()
	{
		$counter_datei = "cache/counter.txt";
		$counterstand = intval(file_get_contents($counter_datei));
 
		if(!isset($_SESSION['counter_ip']))
		   {
		   $counterstand++;
		   file_put_contents($counter_datei, $counterstand);
 
		   $_SESSION['counter_ip'] = true;
		   }
 
		//return $counterstand;
	}
}


// díe session cookie lebt 3 Stunden

// die zeilen am Anfang des Dokuments einbinden

// session_set_cookie_params(10800);
// session_start();


?>
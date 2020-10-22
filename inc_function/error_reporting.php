<?php
/**
* Fehlermeldungen werden nicht mehr angezeigt sondern in der log datei gespeichert
*/
error_reporting(E_ALL & ~E_NOTICE);
ini_set ('display_errors', 'Off');
ini_set ('log_errors', 'On');
if(file_exists('cache/php_log/php_error.txt')){
	ini_set ('error_log','cache/php_log/php_error.txt');
}else{
	ini_set ('error_log','../cache/php_log/php_error.txt');
}



?>
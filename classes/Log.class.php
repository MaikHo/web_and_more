<?php
class Log {
	/**
	* 
	* @todo Hie müssen noch einige Infos wie User , Moidul und so rein
	* @return
	*/
	public static function write_log($Fehlermeldung, $title = NULL){
		
		$format = "txt"; 
 
		$datum_zeit = date("d.m.Y H:i:s");
		$ip = $_SERVER["REMOTE_ADDR"];
		$site = $_SERVER['REQUEST_URI'];
		//$browser = $_SERVER["HTTP_USER_AGENT"];
		 
		$monate = array(1=>"Januar", 2=>"Februar", 3=>"Maerz", 4=>"April", 5=>"Mai", 6=>"Juni", 7=>"Juli", 8=>"August", 9=>"September", 10=>"Oktober", 11=>"November", 12=>"Dezember");
		$monat = date("n");
		$jahr = date("Y");
		 
		 
		if(is_dir('cache/logs')){
			$folder = 'cache/logs';
		}else{
			if(is_dir('../cache/logs')){
				$folder =  '../cache/logs';
			}
		}
				
		
		
		$dateiname= $folder."/".$jahr."_".$monate[$monat].".".$format;
		 
		$infos = "----------------------------------------- <br>\r\n";
		$infos .= "Datum: ".$datum_zeit. "<br>\r\nIP: ".$ip. "<br>\r\nUser: ".$_SESSION["benutzer"]. "<br>\r\nScript: ".$site. "<br>\r\nMeldung: ".$title."<br>\r\n";
		$infos .= $Fehlermeldung."<br>\r\n";
		 
		file_put_contents($dateiname, $infos, FILE_APPEND);
		
		
	}
}
?>




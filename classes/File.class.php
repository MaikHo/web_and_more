<?php
class File{
	
	/**
	* Prüfung ob die Datei existiert, dann wird Sie "include"
	* Existiert sie nicht dann kommt eine Fehlermeldung
	* @param string der einzubindenen Datei
	* 
	* @return
	*/
	public static function include_html_file($file){
		if(file_exists($file)){
			//include($file);
			$datei_inhalt = '';
		    ob_start();
		    readfile($file);
		    $datei_inhalt = ob_get_contents();
		    ob_end_clean();
		    echo $datei_inhalt;
		}
		else{
			$fehlermeldung = 'Die Datei: '.$file.' ist nicht im System vorhanden. Bitte dem Support melden!';
			//echo $fehlermeldung;	
			echo '<script>			
				    $.confirm({
				    theme: "supervan",
				    animation: "bottom",
    				closeAnimation: "top",
				    boxWidth: "30%",
				    useBootstrap: false,
				    icon: "fas fa-exclamation-circle",
				    title: "Systemfehler!",
				    content: "'.$fehlermeldung.'",
				    type: "red",				    
				    typeAnimated: true,
				    buttons: {
				        weiter: {
				            text: "Weiter",
				            btnClass: "btn-red",
				            action: function(){
				            }
				        }
				        
				    }
				}); 
			</script>';
			Log::write_log($fehlermeldung);		
		}
	}


	public static function include_php_file($file){
		if(file_exists($file)){
			include($file);			
		}
		else{
			$fehlermeldung = "Die Datei: ".$file." ist nicht im System vorhanden. Bitte dem Support melden!";
			echo $fehlermeldung;	
			Log::write_log($fehlermeldung);		
		}
	}


	/**
	* 
	* @param undefined $ordner
	* 
	* @return
	*/
	public static function list_nav_files($ordner){		
		$alledateien = scandir($ordner); 	
		foreach ($alledateien as $datei) {
			$dateiinfo = pathinfo($ordner."/".$datei); 			 
			if ($datei != "." && $datei != ".." && $datei != "header.html" && $datei != "Home.html" && $datei != "Datenschutz.html") { 				
				$datei_typen= array("html", "htm");				
				if(in_array($dateiinfo['extension'],$datei_typen))
				{
					echo '<li class="li_nav"><a id="'.$dateiinfo['filename'].'" href="#'.$dateiinfo['filename'].'">'.$dateiinfo['filename'].'</a></li>';					
				} 
			}
		}
	
	}

	
	/**
	* 
	* @param undefined $ordner
	* 
	* @return html
	*/
	function show_all_files($ordner) 
	{ 
	    echo '<div data-container="all_files">';
	    $handle = opendir($ordner); 
	    while ($file = readdir ($handle)) 
	    {         
	        if($file != "." && $file != "..") 
	        { 
	            $path_parts = pathinfo($file);
	            $dateiendung = $path_parts['extension'];
	            $basename = basename($file, ".".$dateiendung);
	            $dateipfad = $ordner."/".$file;
	            if($dateiendung != ""){
					echo '<li data-file="'.$dateiendung.'" data-path="'.$dateipfad.'"><h3>'.$basename.'</h3><br><a target="_blank" href="'.$dateipfad.'">Öffnen der '.$dateiendung.'</a>'; 
				}
	            else{
					echo '<li data-path="'.$dateipfad.'"><h1>'.$basename.'</h1><br>'; 
				}            
	            echo "</li>"; 
	            //
	            if(is_dir($ordner."/".$file)) 
	            { 
	                echo '<br/>'; 
	                show_all_files($ordner."/".$file); 
	            } 
	        } 
	    } 
	    closedir($handle); 
	    echo "<div>";
	} 
	function show_all_folder($ordner){
		$handle = opendir($ordner); 
	    while ($file = readdir ($handle)) 
	    {         
	        if($file != "." && $file != "..") 
	        { 
	            //
	            $path_parts = pathinfo($file);
	            $dateiendung = "";  
	            $basename = $path_parts['basename'];
	            $dateipfad = $ordner."/".$file;
	            if($file != "index.php"){
					echo '<h3>'.$basename.'<br><a target="_blank" href="'.$dateipfad.'">Öffnen des Ordners '.$basename.'</a></h3>'; 
				}            
	        } 
	    } 
	    closedir($handle); 
	}	
	
	
	
	
	/**
	* 
	* @param undefined $filename
	* 
	* @return
	*/
	public static function myfile_size(string $filename){
		return filesize($filename)." bytes";
	}
	/**
	* 
	* @param undefined $filename
	* 
	* @return
	*/
	public static function lastedit(string $filename){
		return date("r", filemtime($filename));
	}
	/**
	* 
	* @param undefined $folder
	* 
	* @aufruf MyFile::read_folder("page");
	* 
	* @return
	*/
	public static function read_folder(string $folder){
		$dirfolder = opendir($folder);
		while (($entry = readdir($dirfolder)) != ""){
			echo $entry."<br />";
		}
		
	}
	/**
	* 
	* @param undefined $filename
	* 
	* @return
	*/
	public static function read_file(string $filename){
		$fileopen = fopen($filename, "r");
		while (!feof($fileopen)){
			echo fgets($fileopen)." <br />";
		}
		fclose($fileopen);
	}
	
	
}




?>
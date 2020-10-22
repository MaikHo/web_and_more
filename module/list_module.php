<?php
if(!$_SESSION["login"]){
	session_start();
}
if (!isset($_SESSION["login"])) {
 header("Location: ../register/anmeldung.php");
 exit;
}



function show_all_folder($ordner){
		$handle = opendir($ordner); 
	    while ($file = readdir ($handle)) 
	    {   
	        $path_parts = pathinfo($file);
	        $dateiendung = pathinfo($file, PATHINFO_EXTENSION);
	        $basename = $path_parts['basename'];
	        // Erster Buchstabe wird großgeschrieben
	        $basename_groß = ucfirst($basename);
	        
	        if($file != "." && $file != ".." && $basename != '') 
	        { 
	            //echo '<h3>'.$basename.'<br><a href="'.$dateipfad.'">Öffnen des Ordners '.$basename.'</a></h3>'; 
	            
	            //$dateipfad = $ordner.$file;
	            if($dateiendung != "php" && $dateiendung != "html"){
					echo '<div class="kachel app" data-app_name="'.$basename.'" data-app_url="../module/'.$basename.'/'.$basename.'.php">'.$basename_groß.'</div>'; 
				}
	        }
	    } 
	    closedir($handle); 
        echo '<div class="kachel app" data-app_name="Office" data-app_url="../third_party/wodotexteditor-0.5.9/editor.php">Office</div>';
        
        echo '<div class="kachel app" data-app_name="Google Drive" data-app_url="https://drive.google.com/drive/my-drive">Google Drive</div>';
        echo '<div class="kachel app" data-app_name="Google Docs" data-app_url="https://docs.google.com/">Google Docs</div>';
        
        echo '<div class="kachel app" data-app_name="Microsoft OneDrive" data-app_url="https://onedrive.live.com/">Microsoft OneDrive</div>';
        echo '<div class="kachel app" data-app_name="Microsoft Office" data-app_url="https://www.office.com/">Microsoft Office</div>';
        
        if ($_SESSION["Admin"] == true) {// ToDo : cms_admin muss aus der config geholt werden
			echo '<hr>';
			echo '<div class="kachel app" data-app_name="Dateibrowser" data-app_url="../dateibrowser.php">Dateibrowser</div>';
			echo '<div class="kachel app" data-app_name="Adminer" data-app_url="../cms_admin/adminer.php">SQL / Datenbank</div>';
		} 				    	    
	}	
	
show_all_folder('../module');


?>
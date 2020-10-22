<?php
if(!$_SESSION["login"]){
	session_start();
}
if (!isset($_SESSION["login"])) {
 header("Location: ../../register/anmeldung.php");
 exit;
}
?>
<section id="03" class="tabcontent" style="display: none">
<article>
	<p>Einstellungen</p>
	<?php
	echo '<button class="kachel"><a href="../register/passwort_aendern.php">Passwort ändern</a></button>';
	echo '<button class="kachel"><a href="../register/anmeldung.php?abmeldung">Abmelden</a></button>';					
	?>
	<hr>
	<p>Homepage Informationen</p>
      <div id="besucher" class="kachel">Besucher?</div>
      <hr>
      <p>Log Einträge.</p>			      
		<?php            
				$ordner = '../cache/logs';
				$alledateien = scandir($ordner); 	
				foreach ($alledateien as $datei) {
					$dateiinfo = pathinfo($ordner."/".$datei); 			 
					if ($datei != "." && $datei != ".." ) { 				
						$datei_typen= array("txt", "htm");				
						if(in_array($dateiinfo['extension'],$datei_typen))
						{
							echo '<div id="'.$dateiinfo['filename'].'" class="kachel log">'.$dateiinfo['filename'].'</div>';					
						} 
					}
				}   
		?>	            
	<hr>
<?php	            	
	// Die folgende Ausgabe ist nur für den Moderator. sichtbar!
	if ($_SESSION["Moderator"] == true) {
	?>
	    <p>Webseiten Einstellungen</p>
	            
	<?php	
	// ToDo : cms_admin muss aus der config geholt werden
			echo '<div class="kachel app" data-app_name="Systemverwaltung" data-app_url="../cms_admin/systemverwaltung.php">Systemverwaltung</div>';
			echo '<div class="kachel app" data-app_name="Impressum" data-app_url="../cms_admin/impressum.php">Impressum</div>';
	
	
	
		
	}
	?>	
	<hr>
<?php	            	
	// Die folgende Ausgabe ist nur für den Admin. sichtbar!
	// ToDo : cms_admin muss aus der config geholt werden
	if ($_SESSION["Admin"] == true) {
	?>
	    <p>Benutzer Verwaltung</p>
	            <button class="kachel"><a href="../cms_admin/benutzerverwaltung.php">Benutzerverwaltung</a></button>
	            <button class="kachel"><a href="../register/register.php">Registrieren</a></button>
	    <?php            
				$ordner = '../cache/php_log';
				$alledateien = scandir($ordner); 	
				foreach ($alledateien as $datei) {
					$dateiinfo = pathinfo($ordner."/".$datei); 			 
					if ($datei != "." && $datei != ".." ) { 				
						$datei_typen= array("log", "txt");				
						if(in_array($dateiinfo['extension'],$datei_typen))
						{
							echo '<div id="'.$dateiinfo['filename'].'" class="kachel errorlog">'.$dateiinfo['filename'].'</div>';					
						} 
					}
				}   
		?>	
		
		
		        
	<?php		
	}
	?>		
	
	
	
	
</article>
</section>


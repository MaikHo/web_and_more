<?php
if(!$_SESSION["login"]){
	session_start();
}
if (!isset($_SESSION["login"])) {
 header("Location: ../../register/anmeldung.php");
 exit;
}
?>
<section id="02" class="tabcontent" style="display: none">
  <article>
    
    <?php
	File::include_php_file("../inc_html/toolbar.php");
	?>
	<div id="show_page" class="editable_elements"></div>
	
    <div id="edit_page" class="kachel"><i class="far fa-edit"></i>Bearbeiten</div>
	<div style="float:left;" id="save" class="kachel"><i class="far fa-save"></i>Speichern</div>
	<div style="float:left;"  id="edit_cancel" class="kachel"><i class="far fa-close"></i> Abbrechen</div>
    
    
    <div id="erstelle_seite" class="page_files kachel">Neue Seite anlegen</div>
    
    <hr>
    <p>Vorhandene Seiten im System</p>
	<?php            
			$ordner = '../inc_html';
			$alledateien = scandir($ordner); 	
			foreach ($alledateien as $datei) {
				$dateiinfo = pathinfo($ordner."/".$datei); 			 
				if ($datei != "." && $datei != ".." && $datei != "Header.html" ) { 				
					$datei_typen= array("html", "htm");				
					if(in_array($dateiinfo['extension'],$datei_typen))
					{
						echo '<div id="'.$dateiinfo['filename'].'" class="page_files kachel page_show">'.$dateiinfo['filename'].'</div>';					
					} 
				}
			}            
	            
	             
	?>       
	<hr>
	<h2>Wichtige Informationen</h2>
	<p>
		Hier können sie Ihre Inhalte Ihrer Webseite erstellen, bearbeiten und löschen. Die Seiten werden automatisch im System eingebunden. 
		Über die Navigationsleiste aufgerufen läd ihre Hompage diese Seite nach.<br>
		Die Seiten Datenschutz und header können nicht gelöscht werden aufgrund einer Sperre. Auch empfehlen wir Ihnen die Seite Datenschutz nur zu ändern wenn 
		Sie sich vorher über das Thema informiert haben. Natürlich sollten Sie aber ihre Daten dort eintragen.<br>
		Die Seite header ist der Kopfbereich ihrer Hompage. Beachten Sie bitte hier, das dieser Bereich nur eine bestimmte Höhe hat.
		Es ist auch empfehlenswert sich immer die Homepage nach Änderungen ganz anzuschauen.
		
	</p>
	<p>
		Bitte zögern Sie nicht uns zu benachrichtigen wenn es Darstellungprobleme gibt.
	</p>
	

	



	
	
	
	
	
	
	
	
	
	
	
	
  </article>

  

</section>
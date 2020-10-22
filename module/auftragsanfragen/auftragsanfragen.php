<?php
session_start();
if (!isset($_SESSION["login"])) {
 header("Location: ../../register/anmeldung.php");
 exit;
}



?>
<!DOCTYPE html>
<html lang="de">
 <head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="author" content="Maik Hoffmann">	
	<meta name="copyright" content="Maik Hoffmann" />
	<meta name="Language" content="german,deutsch,de,at,ch,lu">	
	<title> Startseite </title>  	
	<link rel="stylesheet" type="text/css" media="screen" href="../../css/module.css">	
	
	<link rel="stylesheet" type="text/css" media="screen" href="../../third_party/fontawesome-free-5.0.4/web-fonts-with-css/css/fontawesome-all.min.css">	
	<script src="../../third_party/jquery/jquery-3.2.1.min.js"></script>	
 </head>
<body>
<article>
<div id="ajax_meldung"></div>
<div id="content_auftragsanfragen">
		<?php
		include "../../config.php";
		include '../../'.ADMIN_PFAD.'einstellungen.php';
	// Verbindung zur Datenbank aufbauen
	try {
	 $db = new PDO("mysql:host=" . $DB_HOST . ";dbname=" . $DB_NAME, $DB_USER, $DB_PASSWORD,
	 [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"]);
	}
	catch (PDOException $e) {
	 exit("<h4>Verbindung fehlgeschlagen!</h4>" . $e->getMessage());
	}
	
		$sql = "SELECT * FROM `auftragsanfragen`";
		foreach ($db->query($sql) as $row) {
		   $speicherbutton = '';
		   if($row['kundennummer'] == ''){
		   		$kundennummer = 'nicht angegeben';
		   		$speicherbutton = '<button data-id="'.$row['id'].'" class="kunde_an_db kachel">Kunde speichern</button>';
		   }else{
		   		$kundennummer = $row['kundennummer'];
		   		
		   }
		   
		   echo '
			<div class="table ">	
		      
		      <div class="spalte">'.$row['name'].', '.$row['vorname'].'</div>      
		      <div class="spalte">Firma: '.$row['firma'].'</div>
		      <div class="spalte"><button data-id="'.$row['id'].'" class="show_kontakt kachel_small">Kontakt</button><button data-id="'.$row['id'].'" class="show_anfrage kachel_small">Anfrage</button></div>
		    
		    </div>  
		   <div id="'.$row['id'].'" class="neu_kontakt ">
		   		Kundennummer: '.$kundennummer.'<br>
		   		<br>
		   		'.$row['strasse'].'&nbsp;'.$row['hausnummer'].'<br>
		   		'.$row['plz'].'&nbsp;'.$row['ort'].'<br>
		   		<br>
		   		Telefon: '.$row['telefonnummer'].'<br>
		   		EMail: '.$row['email'].'<br>
		   </div>
		   <div id="'.$row['id'].'"  class="neu_auftrag ">
		   		'.$row['nachricht'].'
		   </div>	
		   <div id="'.$row['id'].'"  class="neu_kontakt ">
		   		'.$speicherbutton.'
		   		<button data-id="'.$row['id'].'" class="anfrage_delete kachel">Anfrage Löschen</button>
		   </div>	
		   ';
		}



		?>
	</div>	
	
	

</article>
<script> 
$(document).ready(function(){


$('.show_kontakt').click(function(){
	var id = $(this).data('id');
	$('#'+id+'.neu_kontakt').toggleClass('show_spalte');
});

$('.show_anfrage').click(function(){
	var id = $(this).data('id');
	$('#'+id+'.neu_auftrag').toggleClass('show_spalte');
});


$('.anfrage_delete').click(function(){					
					var data_id = $(this).data('id');
					
					event.preventDefault();
										
					$.ajax({
						url : '../../ajax/db_auftrag_delete.php',
						type : 'post',
						data : {
							data_id: data_id
							
							//'text2': 'hello World',
						}
					}).done(function(msg){
						//  Cross-Origin-Request kann nur erfolgreich durchgeführt werden wenn der Server die gleiche Domain hat 
						// oder der Server bei seiner Antwort den Zugriff durch entsprechende HTTP-Header erlaubt
						// Beispiel: Access-Control-Allow-Origin: http://foo.example
						$("#ajax_meldung").html(msg);
						window.location.href = window.location.href;
						location.reload();
					}).fail(function(){
						
					
					});		
});


$('.kunde_an_db').click(function(){					
					var data_id = $(this).data('id');
					
					event.preventDefault();
										
					$.ajax({
						url : '../../ajax/write_db.php',
						type : 'post',
						data : {
							//auftragsnummer: data_id
							
							//'text2': 'hello World',
						}
					}).done(function(msg){
						//  Cross-Origin-Request kann nur erfolgreich durchgeführt werden wenn der Server die gleiche Domain hat 
						// oder der Server bei seiner Antwort den Zugriff durch entsprechende HTTP-Header erlaubt
						// Beispiel: Access-Control-Allow-Origin: http://foo.example
						
							
						
						
					}).fail(function(){
						alert("Diese Funktion ist noch nicht fertiggestellt");
					
					});		
});



});    
</script>
</body>
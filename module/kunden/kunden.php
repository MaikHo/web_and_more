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

	<div id="show_kunden" class="kachel">Kunden &nbsp;anzeigen</div>
	<div id="show_formular" class="kachel">Kunden hinzufügen</div>



	<div id="kunden">
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
	
		$sql = "SELECT * FROM `kunden`";
		foreach ($db->query($sql) as $row) {
		   echo '
			<div class="table ">
		      <div class="spalte">'.$row['name'].', '.$row['vorname'].'</div>      
		      <div class="spalte">Firma: '.$row['firma'].'</div>
		      <div class="spalte"><button data-id="'.$row['kundennummer'].'" class="show_kontakt kachel_small">Kontakt</button><button data-id="'.$row['kundennummer'].'" class="show_anfrage kachel_small">Anfrage</button><button data-id="'.$row['kundennummer'].'" class="anfrage_delete kachel_small">Löschen</button></div>
		    
		    </div>  
		   <div id="'.$row['kundennummer'].'" class="neu_kontakt ">
		   		Kundennummer: '.$row['kundennummer'].'<br>
		   		<br>
		   		'.$row['strasse'].'&nbsp;'.$row['hausnummer'].'<br>
		   		'.$row['plz'].'&nbsp;'.$row['ort'].'<br>
		   		<br>
		   		Telefon: '.$row['telefonnummer'].'<br>
		   		E-Mail: '.$row['email'].'<br>
		   </div>
		   <div id="'.$row['kundennummer'].'"  class="neu_auftrag ">
		   		'.$row['nachricht'].'
		   </div>		
		   ';
		}



		?>
	</div>
	
<?php
// Formular erstellen
$Formular = "
<form action='' autocomplete='off' method='post'>

<p>
 <label> Kundennummer:
<br>
  <input type='text' name='kundennummer' id='kundennummer'  size='35'>
 </label>
</p>

<p>
 <label> Firma:
<br>
  <input type='text' name='firma' id='firma'' size='35'>
 </label>
</p>

<p>
 <label> Name:
<br>
  <input type='text' name='name' id='name' size='35'>
 </label>
</p>

<p>
 <label> Vorname:
<br>
  <input type='text' name='vorname' id='vorname' size='35'>
 </label>
</p>

<p>
 <label> E-Mail:
<br>
  <input type='text' name='email' id='email' size='35'>
 </label>
</p>

<p>
 <label> Telefon:
<br>
  <input type='text' name='telefon' id='telefon' value='' size='35'>
 </label>
</p>

<p>
 <label> Straße:
<br>
  <input type='text' name='strasse' id='strasse' size='35'>
 </label>
</p>

<p>
 <label> Hausnummer:
<br>
  <input type='text' name='hausnummer' id='hausnummer' size='35'>
 </label>
</p>

<p>
 <label> PLZ:
<br>
  <input type='text' name='plz' id='plz' size='35'>
 </label>
</p>

<p>
 <label> Stadt:
<br>
  <input type='text' name='stadt' id='stadt' size='35'>
 </label>
</p>
<p>
 <label> Infos:
<br>
  <textarea name='infos' id='editor' ></textarea>
 </label>
</p>
<p>
 <br>
 <button id='formular_abbrechen' class='kachel_small' >Abbrechen</button>
 <button id='kunde_an_db' class='kachel_small'>Senden</button>
</p>
</form>
";
?>
<div id="formular">
<?php

echo $Formular;

?>	
</div>

</article>
<script> 
$(document).ready(function(){

$('#kunden, #formular').hide();

$('#show_kunden').click(function(){
	$('#kunden').toggle();
});


$('#show_formular').click(function(){
	$('#formular').toggle();
});

$('.show_kontakt').click(function(){
	var id = $(this).data('id');
	$('#'+id+'.neu_kontakt').toggleClass('show_spalte');
});

$('.show_anfrage').click(function(){
	var id = $(this).data('id');
	$('#'+id+'.neu_auftrag').toggleClass('show_spalte');
});


$('#show_anfragen').click(function(){	
	$('.table').toggleClass('show_table');
});

$('#kunde_an_db').click(function(){
					event.preventDefault();
					$.ajax({
						url : '../../ajax/db_kunde_hinzu.php',
						type : 'post',
						data : {
							kundennummer: $('#kundennummer').val(),
							firma: $('#firma').val(),
							name: $('#name').val(),
							vorname: $('#vorname').val(),
							email: $('#email').val(),
							telefon: $('#telefon').val(),
							strasse: $('#strasse').val(),
							hausnummer: $('#hausnummer').val(),
							plz: $('#plz').val(),
							stadt: $('#stadt').val()
							
							//'text2': 'hello World',
						}
					}).done(function(msg){
						//  Cross-Origin-Request kann nur erfolgreich durchgeführt werden wenn der Server die gleiche Domain hat 
						// oder der Server bei seiner Antwort den Zugriff durch entsprechende HTTP-Header erlaubt
						// Beispiel: Access-Control-Allow-Origin: http://foo.example
						$("#ajax_meldung").html(msg);
						
						
					}).fail(function(){
						alert("Fehler");
					
					});		
});



});    
</script>
</body>
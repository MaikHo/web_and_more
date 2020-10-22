<?php
	$Datei = "../cache/db/impressum.sqt";
	$DB = new PDO("sqlite:" . $Datei);
	$sql = "SELECT * FROM Impressum WHERE id = 1";
	$DB_daten = $DB->query($sql)->fetch();
		
	$firmenname = $DB_daten['firmenname']; // Firmenname
	$strasse = $DB_daten['strasse']; // Straße
	$hausnummer = $DB_daten['hausnummer']; // Hausnummer
	$plz = $DB_daten['plz']; // PLZ
	$stadt = $DB_daten['stadt']; // Stadt
	$land = $DB_daten['land']; // Land
	$telefonnummer = $DB_daten['telefonnummer']; // Telefonnummer
	$faxnummer = $DB_daten['faxnummer']; // Fax-Nummer
	$emailadresse =  $DB_daten['emailadresse']; // E-Mail-Adresse
	$geschaeftsfuehrer = $DB_daten['geschaeftsfuehrer']; // Geschäftsführer
	$ustidnr = $DB_daten['ustidnr']; // Ust-IdNr
	$handelsregister = $DB_daten['handelsregister']; // Handelsregister
	$DB = null;

echo '
<h1>Impressum</h1>
</p>
<label>Firma</label>'.$firmenname.'<br>
</p>
<p>
<label>Anschrift</label>'.$strasse.'&nbsp;'.$hausnummer .'<br>
<label></label>'.$plz .'&nbsp;'.$stadt .'<br>
<label></label>'.$land.'<br>
</p>
<p>
<label>Tel:</label>'.$telefonnummer .'<br>
<label>Fax:</label>'.$faxnummer .'<br>
<label>E-Mail:</label>'.$emailadresse .'<br>
</p>
<p>
<label>Geschäftsführer:</label>'.$geschaeftsfuehrer .'<br>
<label>UST. ID Nr:</label>'.$ustidnr .'<br>
<label>Handelsregister:</label>'.$handelsregister .'<br>
</p>
';
?>



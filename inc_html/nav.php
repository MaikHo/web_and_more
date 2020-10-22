<nav>
 <ul>
<?php



if (!isset($_SESSION["login"])){
	// nicht angemeldet
	//echo '<li class="li_nav"><a href="index.php">Home</a></li>';
	echo '<li class="li_nav"><a id="Home" href="#Home">softnetz.de</a></li>';
	echo '<li class="li_nav"><a id="News" href="#News">News</a></li>';
	File::list_nav_files("inc_html");
	echo '<li class="li_nav"><a id="Kontakt" href="#Kontakt">Kontakt</a></li>';
	echo '<li class="li_nav"><a href="register/anmeldung.php"><i class="fas fa-sign-in-alt"></i> Anmelden</a></li>';
	
}else{
	// ist angemeldet
	// Abfrage wo sich der User befindet
	if(strpos($_SERVER['PHP_SELF'], 'index')){
		// der User ist auf der index.php 
		//echo '<li class="li_nav"><a href="index.php">Home</a></li>';
		echo '<li class="li_nav"><a id="Home" href="#Home">softnetz.de</a></li>';
		echo '<li class="li_nav"><a id="News" href="#News">News</a></li>';
		File::list_nav_files("inc_html");
		echo '<li class="li_nav"><a id="Kontakt" href="#Kontakt">Kontakt</a></li>';
		echo '<li class="li_nav"><a href="benutzer/hauptseite.php">Arbeitsbereich</a></li> ';
		echo '<li class="li_nav"><a href="register/anmeldung.php?abmeldung">Abmelden</a></li>';
		
		
	}
	else{
		// der User ist auf der hauptseite
		echo '<li class="li_nav"><a href="../index.php">Home</a></li>';
		echo '<li class="li_nav"><a href="#01">Arbeitsbereich</a></li>';
		echo '<li class="li_nav"><a href="#02">Homepagebearbeitung</a></li>';
		echo '<li class="li_nav"><a href="#03">System</a></li>';
		echo '<li class="li_nav"><a href="../register/anmeldung.php?abmeldung">Abmelden</a></li>';
		
		
		
		
	}
}
 

		
?>


</ul>	

</nav>
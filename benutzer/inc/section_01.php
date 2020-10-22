<?php
if(!$_SESSION["login"]){
	session_start();
}
if (!isset($_SESSION["login"])) {
 header("Location: ../../register/anmeldung.php");
 exit;
}
?>
<section id="01" class="tabcontent">
  <article>
    <h1>Willkommen <i><?php echo $_SESSION["benutzer"]; ?></i></h1>
    <hr>
    <h1><i>Apps</i></h1>
    
	
    
    
    <?php    
    include '../module/list_module.php';    
    ?>
    

  </article> 
</section>
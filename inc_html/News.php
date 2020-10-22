<!DOCTYPE html>
<html>
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width">
  <title>Demoseite</title>

  <style>
  

  /* Suche */
  form#suche {
  text-align: Right;
  }

  /* Kategorien */
  p#kategorien {
   text-align: Center;
  }

  /* Nachrichten */
  dl.nachrichten {
   padding: 1rem;
  }

  /* Nachrichten - Überschrift */
  dl.nachrichten dt span {
   font-size: 1.6rem;
   margin-left: 1.3rem;
   font-weight: bold;
  }
  /* Nachrichten - Überschrift */
  dl.nachrichten dt {   
   margin: 1.3rem;
  }  
  /* Nachrichten - Überschrift */
  dl.nachrichten dt small {
   float: right;
  }

  /* Nachrichten - Bild */
  dl.nachrichten dd img.bild {
   margin: 1rem 1rem 1rem 0;
   
   float: Left;
  }

  /* Nachrichten - Hintergrund zeilenweise einfärben! */
  dl.nachrichten {
   border: 1px solid black;
   box-shadow: 2px 5px 5px 3px #aaa;
   border-radius: 9px;
  }
  

  /* "NEU"-Markierung der Nachrichten */
  dl.nachrichten dt span.neu {
   color: #EE0000;
   font-size: 1.1rem;
   font-style: Oblique;
   text-shadow: 1px 1px 2px #FFFF00;
  }

  /* Navigation (Vorherige Seite - Nächste Seite) */
  p#navigation {
   text-align: Center;
  }

  /* Newsticker */
  marquee {
   width: 60%;
   margin: 0 20% 0 20%;
   outline: Solid 1px #E3E9EF;
  }
  </style>
<?php
//include_once "../admin/einstellungen.php";
//include_once "../module/mysql_nachrichten/verbindung.php";
include "../config.php";
include "../".ADMIN_PFAD."einstellungen.php";

	// Verbindung zur Datenbank aufbauen
	try {
	 $db = new PDO("mysql:host=" . $DB_HOST . ";dbname=" . $DB_NAME, $DB_USER, $DB_PASSWORD,
	 [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"]);
	}
	catch (PDOException $e) {
	 exit("<h4>Verbindung fehlgeschlagen!</h4>" . $e->getMessage());
	} 
 
 // Nachrichten auslesen
 $select = $db->prepare("SELECT `id`, `titel`, `autor`, `nachricht`, `bild`, `url`, `pin`, `datum`
                                                        FROM `nachrichten`
                                                        ORDER BY `id` DESC");
 $select->bindValue(':filter', $filter);
 if (isset($_GET["details"])) $select->bindValue(':id', $_GET["details"]);
 $select->execute();
 $nachrichten = $select->fetchAll(PDO::FETCH_OBJ);



 $arNachrichtenAktuell = array();
 foreach ($nachrichten as $nachricht) {
  // Datumsformat umwandeln
  sscanf($nachricht->datum, "%4s-%2s-%2s", $jahr, $monat, $tag);
  // Nachricht angepinnt
  $pin = $nachricht->pin == "1" ? '&#9873; ' : '';
  // Bild anzeigen
  $bild = $nachricht->bild != '' &&
              file_exists('../module/blog/bilder/'. $nachricht->bild) ?
   '<img src="./module/blog/bilder/' . $nachricht->bild . '" title="' . $nachricht->titel . '" alt="' . $nachricht->titel . '" class="bild"><br> ' : '';
  // URL zur einer externen Seite
  $url = $nachricht->url != '' ? ' -&raquo; <a href="' . $nachricht->url . '" target="_blank" rel="noopener">' . str_replace('http://', '', $nachricht->url) . '</a>' : '';
  // Autor
  $autor = $AUTOR_ANZEIGE == "ja" ? ' - von: <em>' . $nachricht->autor . '</em>' : '';
  // "Neu"-Markierung
  $neu = (floor((time() - mktime(0, 0, 0, $monat, $tag, $jahr)) / 86400)) <= $NEU_MARKIERUNG_TAGE &&
             $NEU_MARKIERUNG == "ja" ? '<span class="neu">Neu:</span> ' : '';
  // Anker für die Nachrichten-Liste
  $liste = $NACHRICHTEN_LISTE == "ja" ? chr(13) . '<a id="n' . $nachricht->id . '"></a>' : '';
  // Lange oder gekürzte Nachrichten
  $Nachrichten = $KURZNACHRICHTEN == "ja" &&
                         !isset($_GET["details"]) &&
                         strlen($nachricht->nachricht) > $KURZNACHRICHTEN_ZEICHEN ?
                           mb_substr($nachricht->nachricht, 0, $KURZNACHRICHTEN_ZEICHEN) . ' <a href="' . $scriptName . '?' . 'details=' .
                           $nachricht->id . $ampFilter . $ampSortierung . $ampAnzahl . '#anker">&hellip; Weiterlesen</a>' : $nachricht->nachricht;
  echo $liste . '
 <dl class="nachrichten">
  <dt>
   ' . $pin .  $neu . ' <span>' . $nachricht->titel . '</span>' . $autor . '<small>' . $tag . '.' . $monat . '.' . $jahr . '</small>
  </dt>
  <dd>' . $bild . nl2br($Nachrichten) . $url . ($bild != '' ? '<br style="clear:both">' : '')  . '</dd>
 </dl>' . chr(13);
   
  }
 





?>
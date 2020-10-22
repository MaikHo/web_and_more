<?php
/*
 * Nachrichten - nachrichten.php (utf-8)
 * https://werner-zenk.de
 */

include "verbindung.php";

// Überschrift und Anker für die interne Navigation
echo '<h2 id="anker"> Nachrichten</h2>';

$scriptName = basename($_SERVER["SCRIPT_NAME"]);

if (!isset($_POST["suchbegriff"]) &&
    !isset($_POST["suchen"]) &&
    !isset($_GET["sitemap"])) {

 // Kategorien ermitteln
 $ergebnis = $verbindung->query("SELECT `kategorie`
                                                    FROM `nachrichten`
                                                    WHERE `anzeige` = '1'
                                                    ORDER BY `kategorie` ASC");
 @$kategorien = $ergebnis->fetchAll(PDO::FETCH_COLUMN, 0);
 $ERSTE_KATEGORIE = !in_array($ERSTE_KATEGORIE, $kategorien)  ? @$kategorien[0] : $ERSTE_KATEGORIE;
 $kategorienListe = implode(",", $kategorien) . ",";

 // Filter
 $filter = isset($_GET["filter"]) ? rawurlDEcode($_GET["filter"]) : $ERSTE_KATEGORIE;
 $filter = in_array($filter, $kategorien) ? $filter : $ERSTE_KATEGORIE;
 $ampFilter = "&amp;filter=" . rawurlENcode($filter);

 // Anzahl
 $arAnzahl = ["5", "10", "15", "25", "50"];
 $anzahlAuswahl = isset($_GET["anzahl"]) ? $_GET["anzahl"] : $NACHRICHTEN_SEITE;
 $anzahlAuswahl = in_array($anzahlAuswahl, $arAnzahl) ? $anzahlAuswahl : $NACHRICHTEN_SEITE;
 $ampAnzahl = "&amp;anzahl=" . $anzahlAuswahl;

 // Aktuelle Seite - Alle Seiten
 $anzahlNachrichten = $KATEGORIEN == "ja" ? substr_count($kategorienListe, $filter . ",") : count($kategorien);
 $seiten = ceil($anzahlNachrichten / $anzahlAuswahl);
 $seite = isset($_GET["seite"]) ? abs((int)$_GET["seite"]) : 1;
 $seite = $seite < 1 || $seite > $seiten ? 1 : $seite;
 $start = $seite * $anzahlAuswahl - $anzahlAuswahl;

 // Sortierung
 $sortierung = (isset($_GET["sortierung"]) && in_array($_GET["sortierung"], ["datum", "titel"])) ? $_GET["sortierung"] : 'datum';
 $ampSortierung = "&amp;sortierung=" . $sortierung;

 // SQL
 $SQL_Kategorienfilter = $KATEGORIEN == "ja" ? " AND  `kategorie` = :filter" : " AND  `kategorie` = :filter"; // Kategorie filtern
 $SQL_Details = isset($_GET["details"]) ?  " AND `id` = :id" : ""; // Detail-Ansicht
 $SQL_Sortierung = ($sortierung == 'titel') ? "ORDER BY `titel` ASC" : "ORDER BY `pin` DESC, `datum` DESC"; // Sortierung "titel" oder "datum"

 // Newsticker anzeigen
 if ($NEWSTICKER == "ja") {
  $select = $verbindung->query("SELECT `id`, `kategorie`, `titel`
                                                        FROM `nachrichten`
                                                        WHERE `anzeige` = '1'
                                                        ORDER BY `datum` DESC
                                                        LIMIT " . $NEWSTICKER_ANZAHL);
  $nachrichten = $select->fetchAll(PDO::FETCH_OBJ);
  echo '<marquee onMouseOver="this.scrollAmount=0" onMouseOut="this.scrollAmount=5" scrollamount="5">';
  foreach ($nachrichten as $nachricht) {
   // Link zur Nachricht (Detail-Ansicht)
   echo '<a href="' . $scriptName . '?seite=' . $seite . '&amp;details=' . $nachricht->id . '&amp;filter=' . rawurlENcode($nachricht->kategorie) . $ampSortierung .  '#anker">' . $nachricht->titel . '</a> +++ ';
  }
  echo '</marquee>';
 }

 // Kategorien anzeigen
 if ($KATEGORIEN == "ja") {
  echo chr(13) . '<p id="kategorien">';
  $kategorien = array_unique($kategorien);
  foreach ($kategorien as $kategorie) {
   // Aktuelle Kategorie hervorheben
   echo ($filter == $kategorie ?
   '<strong>' . $kategorie . '</strong>' : 
   // Link zur Kategorie
   '<a href="' . $scriptName . '?seite=1&amp;filter=' . rawurlENcode($kategorie) . $ampSortierung . $ampAnzahl . '#anker">' . $kategorie .
   // Anzahl der Nachrichten in der jeweiligen Kategorie
   '</a>') . '<small><code>(' . substr_count($kategorienListe, $kategorie . ",") . ')</code></small> ' . chr(10);
  }
  echo '</p>';
 }

 // Formular-, und Link-Navigation
 define("NAVIGATION",  chr(13) . '<form action="' . $scriptName . '#anker" method="get" autocomplete="off" style="float:left">' . 
  (($seite - 1) > 0 ? '<a href="' . $scriptName . '?seite=' . ($seite - 1) . $ampFilter . $ampSortierung . $ampAnzahl . '#anker" title="Zurück zu Seite ' . ($seite - 1) . '">&#9668;</a>' : '') .
 ' <label>Seite: <input type="text" value="' . $seite . '" name="seite" size="3" maxlength="4" onclick="this.select()" title="Seitenzahl eingeben"></label> von ' . $seiten .
  (($seite + 1) <= $seiten ? ' <a href="' . $scriptName . '?seite=' . ($seite + 1) . $ampFilter . $ampSortierung . $ampAnzahl . '#anker" title="Weiter zu Seite ' . ($seite + 1) . '">&#9658;</a>' : '') .
  '<input type="hidden" name="filter" value="' . $filter . '">');

 // Auswahllisten anzeigen
 if ($AUSWAHLLISTEN == "ja") {
  // Auswahlliste "Sortierung"
  $auswahlliste = '&emsp;<label>Sortierung: <select name="sortierung" size="1" onChange="this.form.submit()">';
  foreach (["datum", "titel"] as $value) {
   $auswahlliste .= '<option value="' . $value . '"' .
   ((isset($_GET["sortierung"]) ? $_GET["sortierung"] : "") == $value ?
   ' selected="selected"' : '') . '>' . ucfirst($value) . '</option>';
  }
  $auswahlliste .= '</select></label>';
  // Auswahlliste "Anzahl"
  $auswahlliste .= '&emsp;<label>Anzahl: <select name="anzahl" size="1" onChange="this.form.submit()">';
  if (!in_array($NACHRICHTEN_SEITE, $arAnzahl)) {
   array_push($arAnzahl, $NACHRICHTEN_SEITE);
   sort($arAnzahl);
  }
  foreach ($arAnzahl as $value) {
   $auswahlliste .= '<option value="' . $value . '"' .
   ($anzahlAuswahl == $value ?
   ' selected="selected"' : '') . '>' . $value . '</option>';
  }
  $auswahlliste .= '</select></label> <noscript><input type="submit" value="&gt;" title="Auswahl anzeigen"></noscript></form>';
 }
 else {
  $auswahlliste = '</form>';
 }

 echo !isset($_GET["details"]) ? NAVIGATION . $auswahlliste :
  '<p>&NestedLessLess; <a href="' . $scriptName . '?seite=' . $seite . $ampFilter . $ampSortierung . $ampAnzahl . '#anker">Zurück</a></p>';

 // Seitenübersicht anzeigen
 if ($SITEMAP == "ja") {
  echo '<span style="float:right">&nbsp; <a href="' . $scriptName . '?sitemap&amp;seite=' . $seite . $ampFilter . $ampSortierung . $ampAnzahl . '#anker"><sub>Seitenübersicht</sub></a></span>';
 }

 // Such-Formular anzeigen
 if ($SUCHFORM == "ja" &&
    !isset($_GET["details"])) {
  echo chr(13) . '<form action="#anker" method="post" id="suche"> 
  <input type="search" name="suchbegriff" required="required" placeholder="Suchbegriff eingeben">
  <input type="hidden" name="seite" value="' . $seite . '">
  <input type="hidden" name="filter" value="' . $filter . '">
  <input type="submit" name="suchen" value="suchen">
  </form>';
 }
 else {
  echo '<br style="clear:both">';
 }

 // Nachrichten auslesen
 $select = $verbindung->prepare("SELECT `id`, `titel`, `autor`, `nachricht`, `bild`, `url`, `pin`, `datum`
                                                        FROM `nachrichten`
                                                        WHERE `anzeige` = '1'
                                                         " . $SQL_Kategorienfilter . $SQL_Details . "
                                                        " . $SQL_Sortierung . "
                                                        LIMIT "  . $start . ", " . $anzahlAuswahl);
 $select->bindValue(':filter', $filter);
 if (isset($_GET["details"])) $select->bindValue(':id', $_GET["details"]);
 $select->execute();
 $nachrichten = $select->fetchAll(PDO::FETCH_OBJ);

 if ($anzahlNachrichten > 0) {

 // Nachrichten-Liste anzeigen
 if ($NACHRICHTEN_LISTE == "ja" &&
     !isset($_GET["details"])) {
  echo '<h4>Auf dieser Seite:</h4>' .
   '<ul>';
  foreach ($nachrichten as $nachricht) {
   // Internen Link zur Nachricht setzen
   echo '<li><a href="' . $scriptName . '?seite=' . $seite . $ampFilter . $ampSortierung . $ampAnzahl .'#n' . $nachricht->id . '">' . $nachricht->titel . '</a></li>';
  }
  echo '</ul>';
 }

 // Nachrichten anzeigen
 $arNachrichtenAktuell = array();
 foreach ($nachrichten as $nachricht) {
  // Datumsformat umwandeln
  sscanf($nachricht->datum, "%4s-%2s-%2s", $jahr, $monat, $tag);
  // Nachricht angepinnt
  $pin = $nachricht->pin == "1" ? '&#9873; ' : '';
  // Bild anzeigen
  $bild = $nachricht->bild != '' &&
              file_exists($BILDPFAD . $nachricht->bild) ?
   '<img src="' . $BILDPFAD . $nachricht->bild . '" title="' . $nachricht->titel . '" alt="' . $nachricht->titel . '" class="bild"><br> ' : '';
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
   ' . $pin . '<small>' . $tag . '.' . $monat . '.' . $jahr . '</small> - ' . $neu . ' <span>' . $nachricht->titel . '</span>' . $autor . '
  </dt>
  <dd>' . $bild . nl2br($Nachrichten) . $url . ($bild != '' ? '<br style="clear:both">' : '')  . '</dd>
 </dl>' . chr(13);
   $arNachrichtenAktuell[] = $nachricht->id;
  }
 }

 // Link-Navigation
 if ($NAVIGATION == "ja" &&
     !isset($_GET["details"])) {
  if ($anzahlNachrichten > $anzahlAuswahl) {
   echo  '<p id="navigation">Seite ' . $seite . ' von ' . $seiten . '<br>' .
   ($seite > 1 ? '<a href="' . $scriptName . '?seite=1' . $ampFilter . $ampSortierung . $ampAnzahl . '#anker" title="Erste Seite">&#9665; Erste</a>' : '') .
   (($seite - 1) > 0 ? '&nbsp; <a href="' . $scriptName . '?seite=' . ($seite - 1) . $ampFilter . $ampSortierung . $ampAnzahl . '#anker" title="Zurück zu Seite ' . ($seite - 1) . '">&#9668; Zurück</a>' : '') .
   (($seite + 1)  <= $seiten ? '&nbsp; <a href="' . $scriptName . '?seite=' . ($seite + 1) . $ampFilter . $ampSortierung . $ampAnzahl . '#anker" title="Weiter zu Seite ' . ($seite + 1) . '">Weiter &#9658;</a>' : '') .
   ($seite < $seiten ? '&nbsp; <a href="' . $scriptName . '?seite=' . $seiten . $ampFilter . $ampSortierung . $ampAnzahl . '#anker" title="Letzte Seite">Letzte &#9655;</a>' : '') .
   (count($nachrichten) >= 5 ? '<br><small>^ <a href="#anker">Nach oben</a></small>' : '') .
   '</p>';
  }
 }

 // Weitere Nachrichten der aktuellen Kategorie anzeigen
 if ($KATEGORIEN == "ja" &&
     $KATEGORIE_LISTE == "ja") {
  // Aktuell gezeigte Nachrichten aussortieren
  $SQL_Aussortieren = " AND `id` != " . implode(" AND `id` != ", $arNachrichtenAktuell);
  $select = $verbindung->prepare("SELECT `id`, `titel`
                                                            FROM `nachrichten`
                                                            WHERE `anzeige` = '1'
                                                            " . $SQL_Kategorienfilter . $SQL_Aussortieren . "
                                                            " . $SQL_Sortierung . "
                                                            LIMIT " . $KATEGORIE_LISTE_ANZAHL);
  $select->execute([':filter' => $filter]);
  $nachrichten = $select->fetchAll(PDO::FETCH_OBJ);
  // Nur anzeigen wenn es weitere Nachrichten gibt
  if ($select->rowCount() > 0) {
   echo '<h4>Weitere Nachrichten:</h4>' .
    '<ul>';
   foreach ($nachrichten as $nachricht) {
    // Link zur Nachricht (Detail-Ansicht)
    echo '<li><a href="' . $scriptName . '?seite=' . $seite . '&amp;details=' . $nachricht->id . $ampFilter . $ampSortierung . $ampAnzahl . '#anker">' . $nachricht->titel . '</a></li>';
   }
   echo '</ul>';
  }
 }
}

// Suche durchführen
if (isset($_POST["suchen"]) ||
    isset($_POST["suchbegriff"])) {
  // Im Suchbegriff HTML-Elemente und Leerzeichen entfernen
  $_POST["suchbegriff"] = strip_tags(trim($_POST["suchbegriff"]));
 // Such-Formular anzeigen
 echo '<form action="#anker" method="post" id="suche"> 
  <input type="search" name="suchbegriff" value="' . $_POST["suchbegriff"] . '" min="' . $SUCHBEGRIFF_MIN . '" required="required">
  <input type="hidden" name="seite" value="' . $_POST["seite"] . '">
  <input type="hidden" name="filter" value="' . $_POST["filter"] . '">
  <input type="submit" name="suchen" value="suchen">
  </form>';

 if (strlen($_POST["suchbegriff"]) >= $SUCHBEGRIFF_MIN) {
  $select = $verbindung->prepare("SELECT `id`,`kategorie`, `titel`, `autor`, `nachricht`, `datum`
                                                            FROM `nachrichten`
                                                            WHERE (`kategorie` LIKE :suchbegriff OR
                                                                         `titel` LIKE :suchbegriff OR
                                                                         `autor` LIKE :suchbegriff OR
                                                                         `nachricht` LIKE :suchbegriff OR
                                                                         `url` LIKE :suchbegriff OR
                                                                         `datum` LIKE :suchbegriff) AND `anzeige` = '1'
                                                         ORDER BY `datum` DESC
                                                         LIMIT " . $SUCHERGEBNISSE_MAX);
  $select->bindValue(':suchbegriff', '%' . $_POST["suchbegriff"] . '%');
  $select->execute();
  $nachrichten = $select->fetchAll(PDO::FETCH_OBJ);
  $anzahlNachrichten = $select->rowCount();

  // Gefundene Nachrichten anzeigen
  if ($anzahlNachrichten > 0) {
   echo '<p>Es ' . ($anzahlNachrichten == 1 ? 'wurde 1 Nachricht' : 'wurden ' . $anzahlNachrichten . ' Nachrichten') . ' gefunden:</p>';
   foreach ($nachrichten as $zaehler => $nachricht) {
    // Datumsformat umwandeln
    sscanf($nachricht->datum, "%4s-%2s-%2s", $jahr, $monat, $tag);
    // Autor
    $autor = $AUTOR_ANZEIGE == "ja" ? ' - von: <em>' . $nachricht->autor . '</em>' : '';
    $ampFilter = "&amp;filter=" . rawurlENcode($nachricht->kategorie);
    // Textausschnitt
    $textausschnitt = 120; // Zeichen
    $start = strpos(strtolower($nachricht->nachricht), strtolower($_POST["suchbegriff"])) - $textausschnitt;
    if ($start < 0) $start = 0;
    $ende = strlen($_POST["suchbegriff"]) + $textausschnitt *2;
    $textteil = mb_substr($nachricht->nachricht, $start, $ende);
    // Suchbegriff hervorheben
    $Nachricht = preg_replace('/(' . $_POST["suchbegriff"] . ')/i', "<mark>\$1</mark>", $textteil);
    echo chr(13) . '  <dl class="nachrichten">
   <dt><small>' . $tag . '.' . $monat . '.' . $jahr . '</small> - <span>' . $nachricht->titel . '</span>' . $autor . '</dt>
   <dd>[&hellip;] ' . $Nachricht . ' [&hellip;] <a href="' . $scriptName . '?details=' . $nachricht->id . $ampFilter . '#anker">Nachricht anzeigen</a></dd>
  </dl>' . chr(13);
   }
  }
  else {
   echo '<p>Es wurden keine Nachrichten zum Suchbegriff gefunden!</p>';
  }
 }
 else {
  echo '<p>Es sind mindestens ' . $SUCHBEGRIFF_MIN . ' Zeichen nötig!</p>';
 }
 echo '<p>&NestedLessLess; <a href="' . $scriptName . '?seite=' . $_POST["seite"] . '&amp;filter=' . $_POST["filter"] . '">Zurück</a></p>'; // Kein Anker!
}

// Seitenübersicht
if (isset($_GET["sitemap"])) {
 echo '<h3>Seitenübersicht</h3>' .
  '<blockquote>';
 // Alle Nachrichten (Titel) auslesen und aufsteigend nach Kategorien sortieren
 $select = $verbindung->query("SELECT `id`, `kategorie`, `titel`, `datum`
                                                        FROM `nachrichten`
                                                        WHERE `anzeige` = '1'
                                                        ORDER BY `kategorie` ASC");
 $nachrichten = $select->fetchAll(PDO::FETCH_OBJ);
 $arListe = array();
 foreach ($nachrichten as $nachricht) {
  sscanf($nachricht->datum, "%4s-%2s-%2s", $jahr, $monat, $tag);
  // Kategorie nicht in $arListe -> Kategorie hinzufügen und Kategorie anzeigen
  if (!in_array($nachricht->kategorie, $arListe)) {
   $arListe[] = $nachricht->kategorie;
   echo '</blockquote>' .
    '<h4>' . $nachricht->kategorie . '</h4>' .
    '<blockquote>';
  }
  // Link zur Nachricht (Detail-Ansicht)
  echo '<a href="' . $scriptName . '?details=' . $nachricht->id . '&amp;filter=' . rawurlENcode($nachricht->kategorie) . '#anker">' . $nachricht->titel . '</a>' .
   ' - <small>' . $tag . '.' . $monat . '.' . $jahr . '</small><br>';
 }
 echo '</blockquote>' .
  '<p>&NestedLessLess; <a href="' . $scriptName . '?seite=' . $_GET["seite"] . '&amp;filter=' . $_GET["filter"] . '#anker">Zurück</a></p>';
}
?>
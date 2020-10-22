<?php
/*
 *  Event-Kalender - kalenderblatt.php
 * - https://werner-zenk.de

  Dieses Script wird in der Hoffnung verteilt, dass es nützlich sein wird, aber ohne irgendeine Garantie;
  ohne auch nur die implizierte Gewährleistung der Marktgängigkeit oder Eignung für einen bestimmten Zweck.
  Weitere Informationen finden Sie in der GNU General Public License.
  Siehe Datei: license.txt - http://www.gnu.org/licenses/gpl.html

  Diese Datei und der gesamte "Event-Kalender" ist urheberrechtlich geschützt (c) 2018 Werner Zenk alle Rechte vorbehalten.
  Sie können diese Datei unter den Bedingungen der GNU General Public License frei verwenden und weiter verbreiten.
 */

include_once "kalenderfunktionen.php";

// Aufruf
$mond = explode("-", mondphase($_GET["tag"], $_GET["monat"], $_GET["jahr"]));
$kw = kalenderwoche($_GET["tag"], $_GET["monat"], $_GET["jahr"]);
echo ANZEIGE . '<div id="kalenderblatt">&#9782; <span class="navLink" onClick="zeigeTagesansicht(' .  abs($_GET["tag"]) . ',' .  abs($_GET["monat"]) . ',' . $_GET["jahr"] . ')" title="Tagesansicht anzeigen"><mark class="mark">' . datum($_GET["tag"], $_GET["monat"], $_GET["jahr"]) . '</mark></span><br>' .
 '<div id="blatt">' . $_GET["tag"] . '</div>' . 
 (feiertag($_GET["tag"], $_GET["monat"], $_GET["jahr"]) !="" ? '&#9983; <span class="feiertag" title="Feiertag">' . feiertag($_GET["tag"], $_GET["monat"], $_GET["jahr"]) . '</span><br>' : '') .
 '&#9684; ' . gestern_heute_morgen($_GET["tag"], $_GET["monat"], $_GET["jahr"]) . '' . beginnJahreszeit($_GET["monat"], $_GET["tag"]) . '<br>' .
 '&#9676; <span class="navLink" onClick="zeigeKalenderwoche2(' . $_GET["jahr"] . ',' . $kw . ')" title="Kalenderwoche ' . $kw . ' anzeigen">Kalenderwoche: ' . $kw . '</span><br>' .
 '&#9676; Jahrestag: ' . date("z", mktime(0, 0, 0, $_GET["monat"], $_GET["tag"], $_GET["jahr"])) . '<br>' .
 '&#9676; Quartal: ' . quartal($_GET["monat"]) . '<br>' .
 '&#9676; Arbeitstage: ' . arbeitstage($_GET["jahr"], $_GET["monat"]) . '<br>' .
 '&#9676; Jahreszeit: ' . jahreszeit(mktime(0, 0, 0, $_GET["monat"], $_GET["tag"], $_GET["jahr"])) . '<br>' .
 '&#9684; Sommerzeit: ' . sommerzeit($_GET["tag"], $_GET["monat"], $_GET["jahr"]) . '<br style="clear:left"><br>' .
 sunrise($_GET["tag"], $_GET["monat"], $_GET["jahr"]) . '<br>' .
 '&#9676; Mondphase: ' . $mond[0] . ' <small>' . $mond[1] . '</small><br>' .
 '&#9676; Sternzeichen: ' . sternzeichen($_GET["tag"], $_GET["monat"], $_GET["jahr"]) . '<br><br>' .
 fortschrittsbalken($_GET["tag"], $_GET["monat"], $_GET["jahr"]) .
 addr($_GET["tag"], $_GET["monat"], $_GET["jahr"]);


// Mondphase berechnen (Umwandlung eines ehemaligen Visual-Basic Scripts)
function mondphase($tag, $monat, $jahr) {
 $phase = array("&#127765; - Vollmond", "&#127766; - Abnehmender Dreiviertelmond", "&#127768; - Letztes Viertel",
 "&#127767; - Abnehmender Halbmond", "&#127761; - Neumond", "&#127762; - Zunehmender Neumond",
 "&#127763; - Erstes Viertel", "&#127764; - Zunehmender Dreiviertelmond");
 $tag = $tag + 2;
 // Wichtige Werte berechnen
 $jahr = $jahr - intval((12 - $monat) / 10);
 $monat = $monat + 9;
 if ($monat >= 12) {
  $monat = $monat - 12;
 }
 $k1 = intval(365.25 * ($jahr + 4712));
 $k2 = intval(30.6 * $monat + .5);
 // $julian = Julianisches Datum um 12h UT am gewünschten Tag
 $julian = $k1 + $k2 + $tag + 59;
 // Synodische Phase berechnen
 $ynodische_phase = ($julian - 2451550.1) / 29.530588853;
 $ynodische_phase = $ynodische_phase - intval($ynodische_phase);
 if ($ynodische_phase < 0) {
  $ynodische_phase = $ynodische_phase + 1;
 }
 // Mondalter in Tagen
 $mondalter = intval($ynodische_phase * 29.53);
 if ($mondalter == 0 || $mondalter == 29) {$aktuellephase = 0;}
 else if ($mondalter >= 1 && $mondalter <= 6) {$aktuellephase = 1;}
 else if ($mondalter == 7) {$aktuellephase = 2;}
 else if ($mondalter >= 8 && $mondalter <= 13) {$aktuellephase = 3;}
 else if ($mondalter == 14) {$aktuellephase = 4;}
 else if ($mondalter >= 15 && $mondalter <= 21) {$aktuellephase = 5;}
 else if ($mondalter == 22) {$aktuellephase = 6;}
 else if ($mondalter >= 23 && $mondalter <= 28) {$aktuellephase = 7;}
 else {$aktuellephase = 4;}
 return $phase[$aktuellephase];
}

// Gestern, Heute, Morgen
function gestern_heute_morgen($tag, $monat, $jahr) {
 $datum = mktime(0, 0, 0, date($monat), date($tag), date($jahr));
 if ($datum == mktime(0, 0, 0, date("m"), date("d"), date("Y"))) {
  return ' Heute ' . date("H:i") . ' Uhr <small>(' . date("T - e") . ')</small><br>&#9684; @' . date("B") . ' (<abbr title="Biel Mean Time">BMT</abbr>)';
 }
 elseif ($datum == mktime(0, 0, 0, date("m"), date("d") - 1, date("Y"))) {
  return " Gestern";
 }
 elseif ($datum == mktime(0, 0, 0, date("m"), date("d") - 2, date("Y"))) {
  return " Vorgestern";
 }
 elseif ($datum == mktime(0, 0, 0, date("m"), date("d") + 1, date("Y"))) {
  return " Morgen";
 }
 elseif ($datum == mktime(0, 0, 0, date("m"), date("d") + 2, date("Y"))) {
  return " Übermorgen";
 }
 elseif ($datum >= mktime(0, 0, 0, date("m"), date("d") + 3, date("Y"))) {
  return " In " . intval(($datum - mktime(0, 0, 0, date("m"), date("d"), date("Y"))) / (3600 * 24)) . " Tagen";
 }
 elseif ($datum <= mktime(0, 0, 0, date("m"), date("d") - 3, date("Y"))) {
  return str_replace("-", "", " Vor " . intval(($datum - mktime(0, 0, 0, date("m"), date("d"), date("Y"))) / (3600 * 24)) . " Tagen");
 }
}

// Sonnenauf.- und Sonnenuntergang
function sunrise($tag, $monat, $jahr) {
 global $GEO_BREITE, $GEO_LAENGE;
 $onnenaufgang = date_sunrise(mktime(0, 0, 0, $monat, $tag, $jahr), SUNFUNCS_RET_STRING, $GEO_BREITE, $GEO_LAENGE, 90, date("O") / 100);
 $onnenuntergang = date_sunset(mktime(0, 0, 0, $monat, $tag, $jahr), SUNFUNCS_RET_STRING, $GEO_BREITE, $GEO_LAENGE, 90, date("O") / 100);
 return '&#9788;&#9650; Sonnenaufgang: ' . $onnenaufgang . ' Uhr <small>(B: ' . $GEO_BREITE . '&#176; - L: ' . $GEO_LAENGE . '&#176;)</small><br>' .
  '&#9788;&#9660; Sonnenuntergang: ' . $onnenuntergang . ' Uhr <br >' .
 '&#9788;&#9684;  Sonnenscheindauer: ' . (round(str_replace(":", ".", $onnenuntergang) - str_replace(":", ".", $onnenaufgang), 1)) . ' Stunden';
}

// Sommerzeit
function sommerzeit($tag, $monat, $jahr) {
return (((date("I", mktime(2, 0, 0, $monat, $tag, $jahr))) == 1) ? "Ja" : "Nein") .
 ' <small>(' . date("j.n", mktime(2, 0, 0, 3, 31 - date('w', mktime(2, 0, 0, 3, 31, $jahr)), $jahr)) .
 " - " . date("j.n", mktime(2, 0, 0, 10, 31 - date('w', mktime(2, 0, 0, 10, 31, $jahr)), $jahr)) . ')</small>';
}

// Sternzeichen
function sternzeichen($tag, $monat, $jahr) {
 $sz_tag = date("d", mktime(0, 0, 0, $monat, $tag, $jahr));
 $sz_monat = date("n", mktime(0, 0, 0, $monat, $tag, $jahr));
 if ($sz_tag > 20 && $sz_monat == 3 || $sz_tag < 21 && $sz_monat == 4) {$zeichen = ' &#9800; <i>WIDDER</i> <small>(Aries, 21.3 - 20.4)</small>';}
 if ($sz_tag > 20 && $sz_monat == 4 || $sz_tag < 21 && $sz_monat == 5) {$zeichen = '&#9801; <i>STIER</i> <small>(Taurus, 21.4 - 20.5)</small>';}
 if ($sz_tag > 20 && $sz_monat == 5 || $sz_tag < 22 && $sz_monat == 6) {$zeichen = '&#9802; <i>ZWILINGE</i> <small>(Gemini, 21.5 - 21.6)</small>';}
 if ($sz_tag > 21 && $sz_monat == 6 || $sz_tag < 23 && $sz_monat == 7) {$zeichen = '&#9803; <i>KREBS</i> <small>(Cancer, 22.6 - 22.7)</small>';}
 if ($sz_tag > 22 && $sz_monat == 7 || $sz_tag < 24 && $sz_monat == 8) {$zeichen = '&#9804; <i>LÖWE</i> <small>(Leo, 23.7 - 23.8)</small>';}
 if ($sz_tag > 23 && $sz_monat == 8 || $sz_tag < 24 && $sz_monat == 9) {$zeichen = '&#9805; <i>JUNGFRAU</i> <small>(Virgo, 24.8 - 23.9)</small>';}
 if ($sz_tag > 23 && $sz_monat == 9 || $sz_tag < 24 && $sz_monat == 10) {$zeichen = '&#9806; <i>WAAGE</i> <small>(Libra - 24.9 - 23.10)</small>';}
 if ($sz_tag > 23 && $sz_monat == 10 || $sz_tag < 23 && $sz_monat == 11) {$zeichen = '&#9807; <i>SKORPION</i> <small>(Scorpio, 24.10 - 22.11)</small>';}
 if ($sz_tag > 22 && $sz_monat == 11 || $sz_tag < 22 && $sz_monat == 12) {$zeichen = '&#9808; <i>SCHÜTZE</i> <small>(Sagittarius 23.11 - 21.12)</small>';}
 if ($sz_tag > 21 && $sz_monat == 12 || $sz_tag < 21 && $sz_monat == 1) {$zeichen = '&#9809; <i>STEINBOCK</i> <small>(Capricornus, 22.12 - 20.1)</small>';}
 if ($sz_tag > 20 && $sz_monat == 1 || $sz_tag < 20 && $sz_monat == 2) {$zeichen = '&#9810; <i>WASSERMANN</i> <small>(Aquarius, 21.1 - 19.2)</small>';}
 if ($sz_tag > 19 && $sz_monat == 2 || $sz_tag < 21 && $sz_monat == 3) {$zeichen = '&#9811; <i>FISCHE</i> <small>(Pisces, 20.2 - 20.3)</small>';}
 return $zeichen;
}

// Jahreszeit
function jahreszeit($t) {
 $m = date("n", $t);
 $d = date("j", $t);
 if (($m < 3) || (($m == 3) && ($d < 21)) || (($m == 12) && ($d >= 22))) {
  return 'Winter <small>(22.12 - 20.3)</small>';
 }
 else if (($m < 6) || (($m == 6) && ($d < 21))) {
   return 'Frühling <small>(21.3 - 20.6)</small>';
 }
 else if (($m < 9) || (($m == 9) && ($d < 23))) {
 return 'Sommer <small>(21.6 - 22.9)</small>';
 }
 else {
  return 'Herbst <small>(23.9 - 21.12)</small>';
 }
}

// Div. Funktionen
function addr($tag, $monat, $jahr) {
 # Tage bis zum 31 Dezember
 $txt = '&#9676; Tage bis zum 31 Dezember: ' . (int)((mktime(0, 0, 0, 12, 31, $jahr) - mktime(0, 0, 0, $monat, $tag, $jahr)) / 86400) . "<br />\n";
 $schaltjahr = date("L", mktime(0, 0, 0, 1, 1, $jahr));
 $txt .= '&#9676; Schaltjahr ' . $jahr . ':' . ($schaltjahr == 1 ? " Ja" : " Nein") . '<br>';
 $txt .= '&#9676; Römisches Jahr: ' . arab2rom($_GET["jahr"]) . '<br>';
 # Julianisches Datum
 $jd = jdtojulian(unixtojd(mktime(2, 0, 0, $monat, $tag, $jahr)));
 $jd = explode("/", $jd);
 $txt .= '&#9676; Julianisches Datum: ' . $jd[1] . '.' . $jd[0] . '.' . $jd[2] . '<br><br>';
 
 // Namenstage
 $namenstag = array();
 $namenstag[1][1]="Maria"; $namenstag[1][2] = "Makarius, Gregor, Otfried, Dietmar"; $namenstag[1][3] = "Genoveva, Odilo, Irma"; $namenstag[1][4] = "Angelika, Christiane"; $namenstag[1][5] = "Emilia, Johann Nep."; $namenstag[1][6] = "Raimund"; $namenstag[1][7] = "Reinhold, Valentin"; $namenstag[1][8] = "Severin, Erhard, Gudula, Heiko"; $namenstag[1][9] = "Adrian, Julian, Alice"; $namenstag[1][10] = "Paul Eins., Leonie"; $namenstag[1][11] = "Thomas v.C."; $namenstag[1][12] = "Ernst, Tatjana, Xenia"; $namenstag[1][13] = "Jutta, Hilmar, Hilarius"; $namenstag[1][14] = "Rainer, Felix, Engelmar"; $namenstag[1][15] = "Arnold, Romedius, Mauro, Arno"; $namenstag[1][16] = "Marcel, Tilman, Dietwald, Uli"; $namenstag[1][17] = "Anton Eins., Rosalind"; $namenstag[1][18] = "Margitta, Ulfried, Uwe"; $namenstag[1][19] = "Mario, Pia, Martha"; $namenstag[1][20] = "Fabian, Sebastian, Ursula"; $namenstag[1][21] = "Agnes, Meinrad, Ines"; $namenstag[1][22] = "Vinzenz, Dietlinde, Jana"; $namenstag[1][23] = "Hartmut, Emerentia, Guido"; $namenstag[1][24] = "Franz v. S., Vera, Thurid, Bernd"; $namenstag[1][25] = "Pauli Bekehrung., Wolfram"; $namenstag[1][26] = "Timotheus u. Titus, Paula"; $namenstag[1][27] = "Angela, Alrun, Gerd"; $namenstag[1][28] = "Manfred, Thomas v. A., Karl, Karolina"; $namenstag[1][29] = "Gerhard, Gerd, Josef Fr."; $namenstag[1][30] = "Martina, Adelgunde"; $namenstag[1][31] = "Johannes B., Marcella, Rudbert";
 $namenstag[2][1] = "Brigitta, Brigitte, Reginald, Barbara"; $namenstag[2][2] = "Bodo, Stephan"; $namenstag[2][3] = "Blasius, Ansgar, Oskar, Michael"; $namenstag[2][4] = "Andreas C., Veronika, Jenny"; $namenstag[2][5] = "Agatha, Albuin"; $namenstag[2][6] = "Dorothea, Doris, Paul M."; $namenstag[2][7] = "Richard, Ava, Ronan"; $namenstag[2][8] = "Elfrieda, Hieronymus. Philipp"; $namenstag[2][9] = "Apollonia, Anne-Kathrin, Anna, Katharina"; $namenstag[2][10] = "Scholastika, Siegmar, Bruno"; $namenstag[2][11] = "Theodora, Theodor"; $namenstag[2][12] = "Benedikt, Eulalia"; $namenstag[2][13] = "Christina, Irmhild, Adolf, Gisela"; $namenstag[2][14] = "Valentin, Cyrill, Method"; $namenstag[2][15] = "Siegfried, Jovita, Georgia"; $namenstag[2][16] = "Juliana, Liane"; $namenstag[2][17] = "Alexis, Benignus"; $namenstag[2][18] = "Constanze, Simon, Simone"; $namenstag[2][19] = "Irmgard, Irma, Hedwig"; $namenstag[2][20] = "Corona, Falko, Jacinta"; $namenstag[2][21] = "Petrus D., Gunhild, Enrica, Peter"; $namenstag[2][22] = "Petri Stuhlfeier, Isabella, Pit"; $namenstag[2][23] = "Romana, Raffaela, Polyk."; $namenstag[2][24] = "Matthias"; $namenstag[2][25] = "Walburga, Edeltraud"; $namenstag[2][26] = "Gerlinde, Ottokar, Edigna, Denis, Mechthild"; $namenstag[2][27] = "Gabriel, Marko, Baldur"; $namenstag[2][28] = "Roman, Silvana, Oswald, Detlev"; $namenstag[2][29] = "Oswald";
 $namenstag[3][1] = "Albin, Roger, Leontina"; $namenstag[3][2] = "Volker, Agnes, Karl"; $namenstag[3][3] = "Kunigunde, Camilla, Leif, Friedrich"; $namenstag[3][4] = "Kasimir, Edwin, Humbert"; $namenstag[3][5] = "Gerda, Olivia, Dietmar, Tim"; $namenstag[3][6] = "Fridolin, Nicola, Rosa, Nicole"; $namenstag[3][7] = "Reinhard, Felicitas, Perpet., Volker"; $namenstag[3][8] = "Johannes v.G., Gerhard"; $namenstag[3][9] = "Franziska, Bruno, Barbara, Dominik"; $namenstag[3][10] = "Emil, Gustav"; $namenstag[3][11] = "Rosina, Alram, Ulrich"; $namenstag[3][12] = "Beatrix, Almut, Serafina"; $namenstag[3][13] = "Judith, Pauline, Leander"; $namenstag[3][14] = "Mathilde, Eva, Evelyn"; $namenstag[3][15] = "Klemens, Louise"; $namenstag[3][16] = "Herbert, Rüdiger"; $namenstag[3][17] = "Gertrud, Gertraud, Patrick"; $namenstag[3][18] = "Edward, Sibylle, Cyrill"; $namenstag[3][19] = "Josef, Josefa, Josefine"; $namenstag[3][20] = "Claudia, Wolfram"; $namenstag[3][21] = "Christian, Axel, Emilia"; $namenstag[3][22] = "Lea, Elmar, Reinhilde"; $namenstag[3][23] = "Otto, Rebekka, Toribio"; $namenstag[3][24] = "Karin, Elias, Heidelinde"; $namenstag[3][25] = "Lucia"; $namenstag[3][26] = "Ludger, Manuel, Manuela, Lara"; $namenstag[3][27] = "Augusta, Heimo, Ernst"; $namenstag[3][28] = "Guntram, Ingbert, Willy"; $namenstag[3][29] = "Helmut, Ludolf, Berthold"; $namenstag[3][30] = "Amadeus, Diemut"; $namenstag[3][31] = "Cornelia, Conny, Nelly, Ben";
 $namenstag[4][1] = "Irene, Irina, Hugo"; $namenstag[4][2] = "Franz v.P., Mirjam, Sandra, Frank"; $namenstag[4][3] = "Richard, Lisa"; $namenstag[4][4] = "Isidor, Konrad, Kurt"; $namenstag[4][5] = "Crescentia, Vinzenz F., Juliane"; $namenstag[4][6] = "Sixtus, William"; $namenstag[4][7] = "Ralph, Johann Baptist"; $namenstag[4][8] = "Walter, Beate, Rose-Marie"; $namenstag[4][9] = "Waltraud, Casilda, Hugo"; $namenstag[4][10] = "Gernot, Holda, Ezechiel, Engelbert"; $namenstag[4][11] = "Stanislaus, Hildebrand, Reiner"; $namenstag[4][12] = "Herta, Julius, Zeno"; $namenstag[4][13] = "Ida, Hermenegild, Gilda"; $namenstag[4][14] = "Ernestine, Erna, Elmo"; $namenstag[4][15] = "Anastasia, Una, Damian"; $namenstag[4][16] = "Bernadette, Magnus, Joachim"; $namenstag[4][17] = "Eberhard, Wanda, Isadora, Max"; $namenstag[4][18] = "Werner, Wigbert"; $namenstag[4][19] = "Gerold, Emma, Leo, Timo"; $namenstag[4][20] = "Odetta, Hildegund"; $namenstag[4][21] = "Alexandra, Anselm"; $namenstag[4][22] = "Alfred, Kaj, Leonidas"; $namenstag[4][23] = "Georg, Jörg, Jürgen"; $namenstag[4][24] = "Wilfried, Egbert, Virginia, Marion"; $namenstag[4][25] = "Markus Ev., Erwin"; $namenstag[4][26] = "Helene, Consuela"; $namenstag[4][27] = "Zita, Petrus C, Montserrat"; $namenstag[4][28] = "Hugo, Pierre, Ludwig"; $namenstag[4][29] = "Katharina v.S., Roswitha, Katja"; $namenstag[4][30] = "Pauline, Silvio, Pius V.";
 $namenstag[5][1] = "Josef d. Arbeiter, Arnold"; $namenstag[5][2] = "Siegmund, Boris, Zoë"; $namenstag[5][3] = "Philipp u. Jakob, Viola, Alexander"; $namenstag[5][4] = "Florian, Guido, Valeria"; $namenstag[5][5] = "Gotthard, Sigrid, Jutta"; $namenstag[5][6] = "Gundula, Antonia, Britto"; $namenstag[5][7] = "Gisela, Silke, Notker, Helga"; $namenstag[5][8] = "Ida, Ulrike, Ulla, Klara"; $namenstag[5][9] = "Beat, Caroline, Volkmar, Theresia"; $namenstag[5][10] = "Isidor, Gordian, Liliana, Damian de Veuster"; $namenstag[5][11] = "Joachim, Mamertus"; $namenstag[5][12] = "Pankratius, Imelda, Joana"; $namenstag[5][13] = "Servatius, Rolanda"; $namenstag[5][14] = "Bonifatius, Ismar, Pascal, Christian"; $namenstag[5][15] = "Sophie, Sonja, Hertraud"; $namenstag[5][16] = "Johann Nepomuk, Adolf"; $namenstag[5][17] = "Dietmar, Pascal,Antonella"; $namenstag[5][18] = "Erich, Erika, Johannes I., Felix"; $namenstag[5][19] = "Ivo, Yvonne, Kuno"; $namenstag[5][20] = "Bernhardin, Elfriede,Mira"; $namenstag[5][21] = "Hermann, Wiltrud, Konst."; $namenstag[5][22] = "Julia, Rita, Ortwin, Renate"; $namenstag[5][23] = "Renate, Désirée, Alma"; $namenstag[5][24] = "Dagmar, Esther"; $namenstag[5][25] = "Urban, Beda, Magdalene, Miriam"; $namenstag[5][26] = "Marianne, Philipp N."; $namenstag[5][27] = "August, Bruno, Randolph"; $namenstag[5][28] = "Wilhelm, German"; $namenstag[5][29] = "Erwin, Irmtraud, Maximin"; $namenstag[5][30] = "Ferdinand, Johanna"; $namenstag[5][31] = "Petra, Mechthild, Helma";
 $namenstag[6][1] = "Simeon, Silka, Silvana"; $namenstag[6][2] = "Armin, Erasmus, Blandina"; $namenstag[6][3] = "Karl, Silvia, Hildburg, Karoline"; $namenstag[6][4] = "Christa, Klothilde, Iona, Eva"; $namenstag[6][5] = "Winfried Bonifatius, Erika"; $namenstag[6][6] = "Norbert, Bertrand, Kevin, Alice"; $namenstag[6][7] = "Robert, Gottlieb, Anita"; $namenstag[6][8] = "Medardus, Elga, Chlodwig"; $namenstag[6][9] = "Grazia, Annamaria, Ephr., Diana"; $namenstag[6][10] = "Diana, Heinrich, Heinz, Olivia"; $namenstag[6][11] = "Paula, Barnabas, Alice, Udo"; $namenstag[6][12] = "Guido, Leo III., Florinda"; $namenstag[6][13] = "Antonius v.P., Bernhard"; $namenstag[6][14] = "Hartwig, Meinrad"; $namenstag[6][15] = "Veit, Lothar, Gebhard, Bernhard"; $namenstag[6][16] = "Benno, Luitgard, Quirin, Julietta"; $namenstag[6][17] = "Adolf, Volker, Alena"; $namenstag[6][18] = "Elisabeth, Ilsa, Marina, Isabella"; $namenstag[6][19] = "Juliana, Romuald"; $namenstag[6][20] = "Adalbert, Florentina, Margot"; $namenstag[6][21] = "Alois, Aloisia, Alban, Ralf"; $namenstag[6][22] = "Rotraud, Thomas M."; $namenstag[6][23] = "Edeltraud, Ortrud, Marion"; $namenstag[6][24] = "Johannes d.T., Reingard"; $namenstag[6][25] = "Eleonora, Ella, Dorothea, Doris"; $namenstag[6][26] = "David, Konstantin, Vigil., Paul"; $namenstag[6][27] = "Hemma, Heimo, Cyrill, Daniel"; $namenstag[6][28] = "Harald, Ekkehard, Irenäus, Senta"; $namenstag[6][29] = "Peter u. Paul, Gero"; $namenstag[6][30] = "Otto, Bertram, Ehrentrud";
 $namenstag[7][1] = "Dietrich, Aaron, Theobald, Regina"; $namenstag[7][2] = "Wiltrud, Jakob"; $namenstag[7][3] = "Thomas Ap., Ramon, Ramona"; $namenstag[7][4] = "Ulrich, Berta, Elisabeth, Else"; $namenstag[7][5] = "Albrecht, Kira, Letizia"; $namenstag[7][6] = "Marietta G., Goar, Isaias"; $namenstag[7][7] = "Willibald, Edda, Firmin"; $namenstag[7][8] = "Kilian, Amalia, Edgar"; $namenstag[7][9] = "Veronika, Hermine, Hannes"; $namenstag[7][10] = "Knud, Engelbert, Raphael, Sascha"; $namenstag[7][11] = "Olga, Oliver, Benedikt"; $namenstag[7][12] = "Siegbert, Henriette, Felix, Eleonore"; $namenstag[7][13] = "Heinrich, Sarah, Arno"; $namenstag[7][14] = "Roland, Camillo, Goswin"; $namenstag[7][15] = "Bonaventura, Egon, Björn"; $namenstag[7][16] = "Carmen, Irmgard"; $namenstag[7][17] = "Gabriella, Charlotte"; $namenstag[7][18] = "Arnulf, Ulf, Friedrich"; $namenstag[7][19] = "Marina, Reto, Bernold"; $namenstag[7][20] = "Margaretha, Greta, Elias"; $namenstag[7][21] = "Daniel, Daniela, Stella, Julia"; $namenstag[7][22] = "Magdalena, Marlene, Verena"; $namenstag[7][23] = "Birgitta, Birgit, Liborius"; $namenstag[7][24] = "Christoph, Sieglinde, Luise"; $namenstag[7][25] = "Jakob d.Ä., Valentina"; $namenstag[7][26] = "Anna u. Joachim, Gloria"; $namenstag[7][27] = "Rudolf, Rolf, Pantaleon, Natalie"; $namenstag[7][28] = "Adele, Ada, Innozenz, Benno"; $namenstag[7][29] = "Martha, Olaf, Ladislaus, Flora"; $namenstag[7][30] = "Ingeborg, Inga, Petrus C."; $namenstag[7][31] = "Ignatius, Joseph v. Ar., Herrmann";
 $namenstag[8][1] = "Alfons, Kenneth, Peter F., Uwe"; $namenstag[8][2] = "Eusebius, Adriana, Julian, Julan"; $namenstag[8][3] = "Lydia, August, Nikodemus"; $namenstag[8][4] = "Johannes M.V., Rainer, Reinhard"; $namenstag[8][5] = "Oswald, Maria Schnee"; $namenstag[8][6] = "Christi Verklärung, Gilbert"; $namenstag[8][7] = "Cajetan, Afra, Albert"; $namenstag[8][8] = "Dominik, Cyriak, Elgar"; $namenstag[8][9] = "Edith, Altmann, Roman"; $namenstag[8][10] = "Laurenz, Lars, Astrid"; $namenstag[8][11] = "Klara, Philomena, Donald"; $namenstag[8][12] = "Radegunde, Innozenz XI., Andreas"; $namenstag[8][13] = "Hippolyt, Marko, Cassian"; $namenstag[8][14] = "Meinhard, Maximilian K."; $namenstag[8][15] = "Steven"; $namenstag[8][16] = "Stefan, Rochus, Alfried, Stephanie"; $namenstag[8][17] = "Gudrun, Hyazinth, Janine, Clara"; $namenstag[8][18] = "Helena, Rainald, Claudia"; $namenstag[8][19] = "Sebald, Johann E., Julius, Bert"; $namenstag[8][20] = "Bernhard, Bernd, Ronald, Samuel"; $namenstag[8][21] = "Pius X., Maximilian, Pia"; $namenstag[8][22] = "Regina, Maria Regina, Sigfried"; $namenstag[8][23] = "Rosa, Isolde, Zachäus"; $namenstag[8][24] = "Bartholomäus, Michaela, Isolde"; $namenstag[8][25] = "Ludwig, Elvira, Ebba, Patricia"; $namenstag[8][26] = "Patricia, Miriam, Teresa, Margarita"; $namenstag[8][27] = "Monika, Gebhard, Vivian"; $namenstag[8][28] = "Augustin, Adelinde, Aline, Vivian"; $namenstag[8][29] = "Beatrice"; $namenstag[8][30] = "Felix, Heribert, Rebekka, Alma"; $namenstag[8][31] = "Raimund, Aidan, Paulinus, Anja";
 $namenstag[9][1] = "Verena, Ruth, Ägidius"; $namenstag[9][2] = "Ingrid, René, Salomon, Franz"; $namenstag[9][3] = "Gregor, Silvia, Phoebe, Sonja"; $namenstag[9][4] = "Rosalie, Ida, Iris, Irmgard, Sven"; $namenstag[9][5] = "Roswitha, Urs, Hermine"; $namenstag[9][6] = "Magnus, Gundolf, Bertram, Beate"; $namenstag[9][7] = "Regina, Otto, Ralph"; $namenstag[9][8] = "Adrian, Otmar"; $namenstag[9][9] = "Otmar, Edgar, Pedro Cl."; $namenstag[9][10] = "Diethard, Isabella, Carlo, Niels"; $namenstag[9][11] = "Helga, Felix u. Regula, Louis"; $namenstag[9][12] = "Gerfried"; $namenstag[9][13] = "Notburga, Tobias, Johann."; $namenstag[9][14] = "Kreuzerhöhung, Albert, Jens"; $namenstag[9][15] = "Dolores, Melitta, Melissa"; $namenstag[9][16] = "Ludmilla, Cornelius"; $namenstag[9][17] = "Hildegard, Robert, Ariane"; $namenstag[9][18] = "Lambert, Herlinde, Rica"; $namenstag[9][19] = "Wilhelmine, Januarius, Thorsten"; $namenstag[9][20] = "Hertha, Eustach., Candida, Susanna"; $namenstag[9][21] = "Matthäus, Deborah, Jonas"; $namenstag[9][22] = "Mauritius, Emmeram, Gundula"; $namenstag[9][23] = "Linus, Thekla, Gerhild"; $namenstag[9][24] = "Rupert, Virgil, Gerhard"; $namenstag[9][25] = "Klaus, Serge, Irmfried"; $namenstag[9][26] = "Kosmas, Damian, Cosima"; $namenstag[9][27] = "Vinzenz, Hiltrud, Dietrich"; $namenstag[9][28] = "Wenzel, Lioba, Giselher"; $namenstag[9][29] = "Michael, Michaela, Gabriel, Gabriela, Gabi"; $namenstag[9][30] = "Hieronymus, Urs, Victor";
 $namenstag[10][1] = "Remigius, Theresia v.L., Werner, Andrea"; $namenstag[10][2] = "Gideon, Bianca, Jacqueline"; $namenstag[10][3] = "Ewald, Udo, Bianca, Paulina"; $namenstag[10][4] = "Franz v.A., Edwin, Aurora, Emma, Thea"; $namenstag[10][5] = "Herwig, Meinolf, Gallina"; $namenstag[10][6] = "Bruno, Adalbero, Melanie, Brunhild, Gerald"; $namenstag[10][7] = "Rosa Maria, Justina, Jörg, Denise, Marc"; $namenstag[10][8] = "Günther, Laura, Hannah, Gerda"; $namenstag[10][9] = "Sibylle, Sara, Dionys, Elfriede"; $namenstag[10][10] = "Viktor, Samuel, Gereon, Valerie"; $namenstag[10][11] = "Alexander, Manuela, Georg"; $namenstag[10][12] = "Maximilian, Horst, Pilár, David"; $namenstag[10][13] = "Koloman, Edward, Andre"; $namenstag[10][14] = "Burkhard, Calixtus, Alan, Otilie"; $namenstag[10][15] = "Theresia v.A., Aurelia, Franziska"; $namenstag[10][16] = "Hedwig, Gallus, Gordon, Carlo"; $namenstag[10][17] = "Rudolf, Marie-Louise, Adelheid"; $namenstag[10][18] = "Lukas, Gwenn, Justus, Viviana"; $namenstag[10][19] = "Frieda, Frida, Isaak, Paul v. K."; $namenstag[10][20] = "Wendelin, Ira, Irina, Jessica"; $namenstag[10][21] = "Ursula, Ulla, Celina, Holger"; $namenstag[10][22] = "Cordula, Salome, Ingbert"; $namenstag[10][23] = "Johannes C., Severin, Uta"; $namenstag[10][24] = "Anton, Armella, Alois, Aloisia, Victoria"; $namenstag[10][25] = "Ludwig, Lutz, Darja, Hans"; $namenstag[10][26] = "Amand., Albin, Wieland, Anastacia, Josephine"; $namenstag[10][27] = "Sabina, Wolfhard, Christa, Stefan"; $namenstag[10][28] = "Simon u. J. Thaddäus, Freddy"; $namenstag[10][29] = "Ermelinda, Melinda, Franco, Grete"; $namenstag[10][30] = "Dieter, Alfons, Angelo, Sabine"; $namenstag[10][31] = "Wolfgang, Quentin, Melanie";
 $namenstag[11][1] = "Harald"; $namenstag[11][2] = "Angela"; $namenstag[11][3] = "Hubert, Pirmin, Martin P., Silvia"; $namenstag[11][4] = "Karl, Karla, Modesta, Charles"; $namenstag[11][5] = "Emmerich, Zacharias, Hardy"; $namenstag[11][6] = "Leonhard, Christine, Nina"; $namenstag[11][7] = "Engelbert, Carina, Willibr., Tina"; $namenstag[11][8] = "Gottfried, Willehad, Karina"; $namenstag[11][9] = "Theodor, Herfried, Roland, Gregor"; $namenstag[11][10] = "Leo, Andrea, Andreas, Jens, Ted"; $namenstag[11][11] = "Martin, Senta, Mennas, Leonie"; $namenstag[11][12] = "Christian, Kunibert, Martin"; $namenstag[11][13] = "Eugen, Stanislaus, Livia, Rene"; $namenstag[11][14] = "Sidonia, Nikolaus T., Karl"; $namenstag[11][15] = "Leopold, Leopoldine, Albert, Nikolaus"; $namenstag[11][16] = "Margarita, Otmar, Arthur"; $namenstag[11][17] = "Gertrud, Hilda, Florin, Walter"; $namenstag[11][18] = "Odo, Alda, Roman, Bettina"; $namenstag[11][19] = "Elisabeth, Bettina, Lisa, Roman"; $namenstag[11][20] = "Edmund, Corbinian, Felix, Elisabeth"; $namenstag[11][21] = "Amalie, Amelia, Rufus"; $namenstag[11][22] = "Cäcilia, Silja, Salvator, Rufus"; $namenstag[11][23] = "Clemens, Detlef, Columb., Salvator"; $namenstag[11][24] = "Flora, Albert, Chrysogon, Clemens"; $namenstag[11][25] = "Katharina, Kathrin, Katja, Jasmin"; $namenstag[11][26] = "Konrad, Kurt, Anneliese"; $namenstag[11][27] = "Uta, Brunhilde, Albrecht, Ida"; $namenstag[11][28] = "Berta, Jakob, Albrecht"; $namenstag[11][29] = "Friedrich, Friederike, Berta"; $namenstag[11][30] = "Andreas, Andrea, Volkert, Kerstin";
 $namenstag[12][1] = "Blanka, Natalie, Eligius"; $namenstag[12][2] = "Bibiana, Lucius, Jan"; $namenstag[12][3] = "Franz Xaver, Jason"; $namenstag[12][4] = "Barbara, Johannes v.D."; $namenstag[12][5] = "Gerald, Reinhard, Niels"; $namenstag[12][6] = "Nikolaus, Denise, Henrike"; $namenstag[12][7] = "Ambros, Farah, Benedikte"; $namenstag[12][8] = "Edith"; $namenstag[12][9] = "Valerie, Liborius, Reinmar"; $namenstag[12][10] = "Emma, Imma, Loretta"; $namenstag[12][11] = "Arthur, Damasus, Tassilo"; $namenstag[12][12] = "Johanna, Hartmann"; $namenstag[12][13] = "Lucia, Ottilia, Jodok, Johanna"; $namenstag[12][14] = "Berthold, Johannes v.K."; $namenstag[12][15] = "Christiane, Nina, Paola"; $namenstag[12][16] = "Adelheid, Heidi, Elke"; $namenstag[12][17] = "Lazarus, Jolanda, Viviana"; $namenstag[12][18] = "Esperanza, Luise, Gratian"; $namenstag[12][19] = "Susanna, Benjamin"; $namenstag[12][20] = "Julius, Holger, Eike"; $namenstag[12][21] = "Ingmar, Ingo, Hagar"; $namenstag[12][22] = "Jutta, Francesca-Saveria"; $namenstag[12][23] = "Victoria, Johannes C."; $namenstag[12][24] = "Adam u. Eva"; $namenstag[12][25] = "Christfest (Weihnachten)"; $namenstag[12][26] = "Stephan, Stephanie"; $namenstag[12][27] = "Fabiola"; $namenstag[12][28] = "John"; $namenstag[12][29] = "David, Tamara, Jessica"; $namenstag[12][30] = "Hermine, Minna, Herma"; $namenstag[12][31] = "Melanie";
 $txt .= '&#9676; Namenstag:  <i>' . $namenstag[$monat][$tag] . '</i><br>';
 
 // Historischer Jahrestag
 $txt .= '&#9676; <a href="http://de.wikipedia.org/wiki/' . $tag . '._' . urlencode(monat($monat)) . '" target="_blank">' . $tag . '.' . $monat . ' - Historischer Jahrestag</a> <small>(wikipedia.de)</small></div>';
 return $txt;
}

// Quartal
function quartal($monat) {
 return (int)(($monat - 1) / 3) + 1;
}

// Arbeitstage
function arbeitstage($jahr, $monat, $anzahl = 0) {
 for($i = 1; $i <= date("t", mktime(0, 0, 0, $monat, 1, $jahr)); $i++) {
  if (date("w", mktime(0, 0, 0, $monat, $i, $jahr)) != 0 && date("w", mktime(0, 0, 0, $monat, $i, $jahr)) != 6) {
   $anzahl++;
  }
 }
 return $anzahl;
}

// Römisches Jahr
function arab2rom($eingabe) {
 $rom_zeichen = array(1000 => "M", 900 => "CM", 500 => "D", 400 => "CD", 100 => "C", 90 => "XC", 50 => "L", 40 => "XL", 10 => "X", 9 => "IX", 5 => "V", 4 => "IV", 1 => "I");
 $ausgabe = "";
 foreach ($rom_zeichen as $wert => $zeichen) {
  $zahl = floor($eingabe / $wert);
  if ($zahl > 0) {
   $ausgabe .= str_repeat($zeichen, $zahl);
  }
  $eingabe = $eingabe % $wert;
 }
 return $ausgabe;
}

// Beginn Jahreszeit
function beginnJahreszeit($monat, $tag) {
 $str = $tag . '.' . $monat;
 $a = ["21.3"=>"Frühlingsanfang", "21.6"=>"Sommeranfang", "23.9"=>"Herbstanfang",  "22.12"=>"Winteranfang"];
 if (array_key_exists($str, $a)) {
  return ' [' . $a[$str] . ']';
 }
}

// Fortschrittsbalken
function fortschrittsbalken($tag, $monat, $jahr) {
 $txt = '<div style="width: 40%; border:1px solid #7EB4EA;">';
 $StartDatum = mktime(0,0,0, 1, 1, $jahr);
 $EndDatum  =  mktime(0,0,0, 12, 31, $jahr);
 $u = mktime(0,0,0, $monat, $tag, $jahr);
 $Prozent = floor((($u - $StartDatum) / ($EndDatum - $StartDatum)) * 100);
 if ($Prozent > 100) $Prozent = 100;
 if ($Prozent < 1 ) $Prozent = 0;
 $txt .= '<span style="display: Table"><span style="display:Table-Cell; width: 1%; border-right: Solid 1px #7EB4EA; font-size: 0.80rem;">Jan.</span>';
 $txt .= '<span style="display:Table-Cell; width: 98%;"><span style="display: Inline-Block; width: ' . $Prozent . '%; background: #DEEEFC; text-align: right; color:#4191E0">'. $Prozent . '% </span></span>';
 $txt .= ' <span style="display:Table-Cell; width: 1%; border-left: Solid 1px #7EB4EA; font-size: 0.80rem;">Dez.</span></span>';
 return $txt . '</div>';
}
?>
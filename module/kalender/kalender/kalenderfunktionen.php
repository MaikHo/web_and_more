<?php
/*
 *  Event-Kalender - kalenderfunktionen.php
 * - https://werner-zenk.de

  Dieses Script wird in der Hoffnung verteilt, dass es nützlich sein wird, aber ohne irgendeine Garantie;
  ohne auch nur die implizierte Gewährleistung der Marktgängigkeit oder Eignung für einen bestimmten Zweck.
  Weitere Informationen finden Sie in der GNU General Public License.
  Siehe Datei: license.txt - http://www.gnu.org/licenses/gpl.html

  Diese Datei und der gesamte "Event-Kalender" ist urheberrechtlich geschützt (c) 2018 Werner Zenk alle Rechte vorbehalten.
  Sie können diese Datei unter den Bedingungen der GNU General Public License frei verwenden und weiter verbreiten.
 */


// Feiertage (gesetzliche- kirchliche- und Brauchtumstage je nach Bundesland verschieden)
function feiertage($jahr) {

 // Die festen Feiertage
 $ftag["Neujahr "] = mktime(0, 0, 0, 1, 1, $jahr);
 $ftag["Heilige 3 Könige "] = mktime(0, 0, 0, 1, 6, $jahr);
 $ftag["Valentinstag "] = mktime(0, 0, 0, 2, 14, $jahr);
 $ftag["Walpurgisnacht "] = mktime(0, 0, 0, 4, 30, $jahr);
 $ftag["Maifeiertag "] = mktime(0, 0, 0, 5, 1, $jahr);
 $ftag["Sommersonnenwende "] = mktime(0, 0, 0, 6, 21, $jahr);
 $ftag["Friedensfest "] = mktime(0, 0, 0, 8, 8, $jahr);
 $ftag["Maria Himmelfahrt "] = mktime(0, 0, 0, 8, 15, $jahr);
 $ftag["Tag der deutschen Einheit "] = mktime(0, 0, 0, 10, 3, $jahr);
 $ftag["Erntedankfest "] = strtotime("sunday october" . $jahr);
 $ftag["Kirchweih "] = strtotime("third sunday october" . $jahr); // Allerweltskirchweih!
 $ftag["Reformationstag "] = mktime(0, 0, 0, 10, 31, $jahr);
 $ftag["Halloween "] = mktime(0, 0, 0, 10, 31, $jahr);
 $ftag["Allerheiligen "] = mktime(0, 0, 0, 11, 1, $jahr);
 $ftag["Martinstag "] = mktime(0, 0, 0, 11, 11, $jahr);
 $ftag["Nikolaus "] = mktime(0, 0, 0, 12, 6, $jahr);
 $ftag["Wintersonnenwende "] = mktime(0, 0, 0, 12, 22, $jahr);
 $ftag["Heiligabend "] = mktime(0, 0, 0, 12, 24, $jahr);
 $ftag["1. Weihnachtsfeiertag "] = mktime(0, 0, 0, 12, 25, $jahr);
 $ftag["2. Weihnachtsfeiertag "] = mktime(0, 0, 0, 12, 26, $jahr);
 $ftag["Silvester "] = mktime(0, 0, 0, 12, 31, $jahr);
 // Hier weitere Feiertage (Geburtstage etc.) eintragen

 // Zeitumstellung
 $ftag["&#9684;&nbsp;Sommerzeit! "] = strtotime('-1 week sun april' . $jahr);
 $ftag["&#9684;&nbsp;Normalzeit! "] = strtotime('-1 week sun november' . $jahr);

 // Muttertag berechnen
 $ftag["Muttertag "] = mktime(0, 0, 0, 5, (14 - date("w", mktime(0, 0, 0, 5, 0, $jahr))), $jahr);

 // Ostersonntag berechnen
 $ostern = strtotime("+ " . (easter_days($jahr)) . " days", mktime(0, 0, 0, 3, 21, $jahr));

 // Die beweglichen Feiertage, abhängig vom Ostersonntag
 $ftag["Rosenmontag "] = strtotime("-48 day", $ostern);
 $ftag["Aschermittwoch "] = strtotime("-46 day", $ostern);
 $ftag["Palmsonntag "] = strtotime("-7 day", $ostern);
 $ftag["Karfreitag "] = strtotime("-2 day", $ostern);
 $ftag["Ostersonntag "] = strtotime("0 day", $ostern);
 $ftag["Ostermontag "] = strtotime("+1 day", $ostern);
 $ftag["Weißer Sonntag "] = strtotime("+7 day", $ostern);
 $ftag["Ch. Himmelfahrt, Vatertag "] = strtotime("+39 day", $ostern);
 $ftag["Pfingstsonntag "] = strtotime("+49 day", $ostern);
 $ftag["Pfingstmontag "] = strtotime("+50 day", $ostern);
 $ftag["Fronleichnam "] = strtotime("+60 day", $ostern);

 // Erster Advent berechnen
 $advent = strtotime("+1 sunday",mktime(0,0,0,11,27,$jahr));

 // Die beweglichen Feiertage, abhängig vom ersten Advent
 $ftag["1. Advent "] = strtotime("+0 day", $advent);
 $ftag["2. Advent "] = strtotime("+7 day", $advent);
 $ftag["3. Advent "] = strtotime("+14 day", $advent);
 $ftag["4. Advent "] = strtotime("+21 day", $advent);
 $ftag["Buß- und Bettag "] = strtotime("-11 day", $advent);
 $ftag["Totensonntag "] = strtotime("last sunday", $advent);
 $ftag["Volkstrauertag "] = strtotime("-14 day", $advent);
 asort($ftag);
 return $ftag;
}

// Feiertag
function feiertag($tag, $monat, $jahr) {
 $ausgabe = "";
 foreach (feiertage($jahr) as $ftag => $zeitstempel) {
  if ($zeitstempel == date("U", mktime(0, 0, 0, $monat, $tag, $jahr))) {
   $ausgabe .= $ftag;
  }
 }
 return $ausgabe;
}


// Monatsname
function monat($monat) {
 global $monate;
 return $monate[$monat];
}

// Datum
function datum($tag, $monat, $jahr) {
 global $monate, $wochentage;
 $wt = getdate(mktime(0, 0, 0, $monat, $tag, $jahr));
 return $wochentage[$wt["wday"]] . ', ' . round($tag) . ' ' . $monate[round($monat)] . ' ' . $jahr;
}

// Kalenderwoche
function kalenderwoche($tag, $monat, $jahr) {
 $zl = mktime(0, 0, 0, $monat, $tag, $jahr);
 return sprintf("%d", date("W", $zl), date("j.n.Y", strtotime("last Sunday", $zl)), date("j.n.Y", strtotime("+6 day", $zl)));
}

// Aktueller Event
function eventAktuell($tag, $monat, $jahr, $stunde, $minute, $ende) {
 if (substr($ende, 11, 8) != "23:59:00") {
  sscanf($ende, "%4s-%2s-%2s %2s:%2s", $a, $b, $c, $stunde, $minute);
 }
 return mktime(date("H"), date("i"), 0, date("m"), date("d"), date("Y")) >= mktime($stunde, $minute, 0, $monat, $tag, $jahr) ?
  ' <span title="Dieser Event ist abgelaufen">&nbsp;&#9872;</span>' : ' <span title="Dieser Event ist aktuell">&nbsp;&#9873;</span>';
}

// Titel erstellen
function title($tag, $monat, $jahr, $uhr, $txt) {
 $txt = strip_tags($txt);
 $txt = preg_replace('/\[.*?\](.*)\[\/.*?\]/isU', '$1', $txt);
 $txt = preg_replace('/(\s{4})\s+/', '$1', $txt);
 $txt = str_replace(["\n", "\r", "  ", "\"", "\'"], ["&#10;", "", " ", "", ""], $txt);
 $txt = $txt != "" ? mb_substr($txt, 0, 250) . (strlen($txt) > 250 ? ' &hellip;' : '') : 'Event anzeigen';
 return ' title="' . datum($tag, $monat, $jahr) . ' - ' . $uhr . ' Uhr&#10;' . $txt . '"';
}

// Auswahlliste Tag
function auswahlTag($tag) {
 $tr = chr(13) . '<label>Tag: <select name="tag">';
  for ($zaehler = 1; $zaehler <= 31; $zaehler++) {
  $tr .= ' <option value="' . sprintf("%02s", $zaehler) . '"' .
   ($tag == sprintf("%02s", $zaehler) ? ' selected="selected"' : "") .
   '>' . $zaehler . '</option>';
 }
 return $tr . ' </select></label>&nbsp;';
}

// Auswahlliste Monat
function auswahlMonat($monat) {
 $tr = '<label>Monat: <select name="monat">';
 foreach (range(1, 12) as $monatszahl) {
  $tr .= ' <option value="' . sprintf("%02s", $monatszahl) . '"' .
   ($monat == sprintf("%02s", $monatszahl) ? ' selected="selected"' : "") .
   '>' . $monatszahl .  '</option>';
 }
 return $tr . ' </select></label>&nbsp;';
}

// Eingabefeld Jahr (Type: Number)
function auswahlJahr($jahr) {
 return '<label>Jahr: <input type="number" name="jahr" min="1971" max="2087" value="' . $jahr . '"></label>';
}

// Auswahlliste Uhrzeit
function auswahlUhrzeit($h="08", $i="00") {
 $tr = '<label>&#9684; <select name="stunde" size="1">';
 foreach (range(0, 23) as $stunde) {
  $tr .= '<option' .  ($stunde == $h ? ' selected="selected"' : '') . '>' . sprintf("%02s", $stunde) . '</option>';
 }
 $tr .= '</select></label>';
 $tr .= ' <label>: <select name="minute" size="1">';
 foreach (range(0, 59) as $minute) {
  $tr .= '<option' .  ($minute == $i ? ' selected="selected"' : '') . '>' . sprintf("%02s", $minute) . '</option>';
 }
 $tr .= '</select></label>';
 return $tr;
}
function auswahlUhrzeit2($h="23", $i="59") {
 $tr = '&nbsp; (<label>bis  <select name="stunde2" size="1">';
 foreach (range(0, 23) as $stunde) {
  $tr .= '<option' .  ($stunde == $h ? ' selected="selected"' : '') . '>' . sprintf("%02s", $stunde) . '</option>';
 }
 $tr .= '</select></label>';
 $tr .= ' <label>: <select name="minute2" size="1">';
 foreach (range(0, 59) as $minute) {
  $tr .= '<option' .  ($minute == $i ? ' selected="selected"' : '') . '>' . sprintf("%02s", $minute) . '</option>';
 }
 $tr .= '</select></label>) Uhr';
 return $tr;
}

// Auswahlliste Priorität
function auswahlPrioritaet($prio=0, $arr) {
 $a = ' <label>Priorität: <select name="prioritaet"><option>0</option>';
 foreach ($arr as $nr => $el) {
  $a .= '<option ';
  if ($prio == $nr) {
   $a .= 'selected="selected"';
  }
  $a .= ' style="background-color:' . $el . ';">' . $nr . '</option>';
 }
 return $a .= '</select>';
}

function formatierung($text) {
 $text = preg_replace_callback('#(( |^)(((http|https|)://)|www.)\S+)#mi', 'linkUmwandeln', $text);
 // BBCode
 $text = preg_replace('/\[b\](.*)\[\/b\]/Usi', '<b>$1</b>', $text); // [b]
 $text = preg_replace('/\[i\](.*)\[\/i\]/Usi', '<i>$1</i>', $text); // [i]
 $text = preg_replace('/\[s\](.*)\[\/s\]/Usi', '<s>$1</s>', $text); // [s]
 $text = preg_replace('/\[q\](.*)\[\/q\]/Usi', '<q>$1</q>', $text); // [q]
 $text = preg_replace('/\[u\](.*)\[\/u\]/Usi', '<u>$1</u>', $text); // [u]
 $text = preg_replace('/\[mark\](.*)\[\/mark\]/Usi', '<mark>$1</mark>', $text); // [mark]
 $text = preg_replace('/\[color=(.*)\](.*)\[\/color\]/Usi', '<span style=\'color:$1\'>$2</span>', $text); // [color=#FF0000]  [color=green]
 $text = preg_replace('/\[code\](.*)\[\/code\]/Usi', '<code>$1</code>', $text); // [code]
 return nl2br($text, false);
}

// Link umwandeln
function linkUmwandeln($hit) {
 $url = trim($hit[1]);
 if ((substr($url, 0, 7) != 'http://') && (substr($url, 0, 8) != 'https://')) {
  $url = "http://" . $url;
 }
 return ' <a href="' . $url . '" target="_blank" rel="noopener">' . $url . '</a>';
}

// Events anzeigen
function anzeigen($datum, $ende, $name, $event, $beschreibung, $prioritaet, $wiederholung, $id,
 $DIREKTEINGABE, $PRIORITAET_ANZEIGEN, $PRIORITAET, $NAME_ANZEIGE, $KALENDERWOCHE_ANZEIGE, $EVENTEXPORT, $KALENDERBLATT_ANZEIGE) {
 $prioritaet = $prioritaet > 0 && $PRIORITAET_ANZEIGEN == "ja" ? '<span style="background-color: ' . $PRIORITAET[$prioritaet] . ';" class="prioritaet" title="Priorität ' . $prioritaet . '">' . $prioritaet . '</span>' : '';
 $beschreibung = formatierung($beschreibung);
 sscanf($datum, "%4s-%2s-%2s %2s:%2s", $dbJahr, $dbMonat, $dbTag, $dbStunde, $dbMinute);
 $abgelaufen = eventAktuell($dbTag, $dbMonat, $dbJahr, $dbStunde, $dbMinute, $ende);
 $ende = substr($ende, 11, 8) != "23:59:00" ? ' bis ' . substr($ende, 11, 5) : '';
 $kw = kalenderwoche($dbTag, $dbMonat, $dbJahr);
 $kalenderwoche = $KALENDERWOCHE_ANZEIGE == "ja" ? '<wbr><span class="navLink" onClick="zeigeKalenderwoche2(' . $dbJahr . ',' . $kw . ')" title="Kalenderwoche ' . $kw . ' anzeigen">KW&nbsp;' . $kw . '</span>' : '';
 $wiederholung = $wiederholung == 1 ? ' <span title="Jährliche Wiederholung">&#11118;</span>' : '';
 $name = $NAME_ANZEIGE == "ja" ? ' [' . $name . ']' : '';
 $kalenderblatt = $KALENDERBLATT_ANZEIGE == "ja" ? '<span class="navLink" onClick="zeigeKalenderblatt(' . abs($dbTag) . ',' . abs($dbMonat) . ',' . $dbJahr . ')" title="Kalenderblatt anzeigen">&#9782;</span>' : '&#9782;';
 $eventexport = $EVENTEXPORT == "ja" ? '<span class="eventLink" onClick="window.location.href=\'kalender_ical-export.php?export=' . $id . '\'" title="Event exportieren">Exportieren</span>&nbsp; ' : '';
 $direkt = $DIREKTEINGABE == "ja" ?
  '<dd>
   <span class="eventLink" onClick="zeigeFormular(\'eintragen\',' . abs($dbTag) . ',' . abs($dbMonat) . ',' . $dbJahr . ',null,true)" title="Event eintragen">Eintragen</span>&nbsp;  
   <span class="eventLink" onClick="zeigeFormular(\'aktualisieren\',' . abs($dbTag) . ',' . abs($dbMonat) . ',' . $dbJahr . ',' . $id . ',false)"title="Event aktualisieren">Aktualisieren</span>&nbsp; 
   ' . $eventexport . '
   <span class="eventLink" onClick="zeigeFormular(\'loeschen\',' . abs($dbTag) . ',' . abs($dbMonat) . ',' . $dbJahr . ',' . $id . ',false)" title="Event löschen">Löschen</span>
   </dd>' : '';

 return '<dl class="dl"><dt>' . $kalenderblatt . '<span class="navLink" onClick="zeigeTagesansicht(' .  abs($dbTag) . ',' .  abs($dbMonat) . ',' . $dbJahr . ')" title="Tagesansicht anzeigen">' . datum($dbTag, $dbMonat, $dbJahr) . '</span>' .
  $kalenderwoche . $wiederholung .
  '<wbr>&nbsp;<span class="nowrap">&#9684; ' . $dbStunde . ':' . $dbMinute . $ende . ' Uhr' . $abgelaufen . '</span></dt>' .
  '<dd>&#9655; <strong>' . $event . '</strong> ' . $prioritaet . $name . ' </dd><dd>' .
  $beschreibung . '</dd>' . $direkt . '</dl>';
}
?>
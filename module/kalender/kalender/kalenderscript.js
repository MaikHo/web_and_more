/*
 *  Event-Kalender - kalenderscript.js (utf-8)
 * - https://werner-zenk.de

  Dieses Script wird in der Hoffnung verteilt, dass es nützlich sein wird, aber ohne irgendeine Garantie;
  ohne auch nur die implizierte Gewährleistung der Marktgängigkeit oder Eignung für einen bestimmten Zweck.
  Weitere Informationen finden Sie in der GNU General Public License.
  Siehe Datei: license.txt - http://www.gnu.org/licenses/gpl.html

  Diese Datei und der gesamte "Event-Kalender" ist urheberrechtlich geschützt (c) 2018 Werner Zenk alle Rechte vorbehalten.
  Sie können diese Datei unter den Bedingungen der GNU General Public License frei verwenden und weiter verbreiten.
 */

"use strict";

// Wenn diese Datei und die Datei: "kalender.php" in einem anderen Verzeichnis
// liegen, dann muss der Verzeichnispfad hier angepasst werden.
var kalender = "kalender/kalender.php";

var xhr = new XMLHttpRequest();
var Aktuell = new Date();

window.addEventListener("DOMContentLoaded",
 zeigeKalender(Aktuell.getFullYear(), Aktuell.getMonth()+1));

// Kalender
function zeigeKalender(jahr, monat, mA, jA, sU) {
 xhr.open("GET", kalender +
  "?kalender&jahr=" + jahr +
  "&monat=" + monat +
  (mA == true ? '&monate' : '') +
  (jA == true ? '&jahre' : '') +
  (sU == true ? '&suche' : '') +
  '&rnd' + (Math.random()*1000), true);
 xhr.send(null);
 xhr.onreadystatechange = function() {
  if (xhr.readyState == 4 &&
      xhr.status == 200) {
   document.getElementById("kalender").innerHTML = xhr.responseText;
  }
 }
}

// Eingabeaufforderung (Jahr)
function zeigeKalenderEingabe(monat) {
 var Heute = new Date();
 var Jahr = Heute.getFullYear().toString();
 var jahr = prompt("Gewünschtes Jahr eingeben:", Jahr);
 if (jahr != "" &&
     jahr != null &&
    !isNaN(jahr)) {
  xhr.open("GET", kalender +
   "?kalender&jahr=" + jahr +
   "&monat=" + monat +
   '&rnd' + (Math.random()*1000), true);
  xhr.send(null);
  xhr.onreadystatechange = function() {
   if (xhr.readyState == 4 &&
       xhr.status == 200) {
    document.getElementById("kalender").innerHTML = xhr.responseText;
   }
  }
 }
}

// Event
function zeigeEvent(id, tag) {
 aktivenTagLoeschen();
 document.getElementById("n" + tag).classList.add("aktivtag");
 xhr.open("GET", kalender + "?event&id=" + id, true);
 xhr.send(null);
 xhr.onreadystatechange = ausgabe;
}

// Formular
function zeigeFormular(form, tag, monat, jahr, id, mtag) {
 if (mtag == true) {
  aktivenTagLoeschen();
  document.getElementById("n" + tag).classList.add("aktivtag");
 }
 xhr.open("GET", kalender +
  "?form=" + form +
  "&tag=" + tag +
  "&monat=" + monat +
  "&jahr=" + jahr +
  (id != null ? "&id=" + id : ''), true);
 xhr.send(null);
 xhr.onreadystatechange = ausgabe;
}

// Formular senden
function sendeFormular() {
 xhr.open("POST", kalender);
 var daten = new FormData(document.getElementById("Form"));
 xhr.send(daten);
 xhr.onreadystatechange = ausgabe;
}

// Suche
function suche() {
 xhr.open("POST", kalender);
 var daten = new FormData(document.getElementById("suchform"));
 xhr.send(daten);
 xhr.onreadystatechange = ausgabe;
}

// Kalenderblatt
function zeigeKalenderblatt(tag, monat, jahr) {
 aktivenTagLoeschen();
 document.getElementById("n" + tag).classList.add("aktivtag");
 xhr.open("GET", kalender +
  "?kalenderblatt&tag=" + tag +
  "&monat=" + monat +
  "&jahr=" + jahr, true);
 xhr.send(null);
 xhr.onreadystatechange = ausgabe;
}

// Wochentage
function wochenTage(jahr, wochentag) {
 xhr.open("GET", kalender +
  "?wochentage&jahr=" + jahr +
  "&wochentag=" + wochentag, true);
 xhr.send(null);
 xhr.onreadystatechange = ausgabe;
}

// Kalenderwoche
function zeigeKalenderwoche() {
 xhr.open("GET", kalender +
  "?kalenderwoche&jahr=" + document.getElementById("jahr").value +
  "&kwoche=" + document.getElementById("kwoche").value, true);
 xhr.send(null);
 xhr.onreadystatechange = ausgabe;
}

// Kalenderwoche (2)
function zeigeKalenderwoche2(jahr, kw) {
 xhr.open("GET", kalender +
  "?kalenderwoche&jahr=" + jahr +
  "&kwoche=" + kw, true);
 xhr.send(null);
 xhr.onreadystatechange = ausgabe;
}

// Tagesansicht
function zeigeTagesansicht(tag, monat, jahr) {
 aktivenTagLoeschen();
 document.getElementById("n" + tag).classList.add("aktivtag");
 xhr.open("GET", kalender +
  "?tagesansicht&tag=" + tag +
  "&monat=" + monat +
  "&jahr=" + jahr, true);
 xhr.send(null);
 xhr.onreadystatechange = ausgabe;
}

// Events
function zeigeAktuelleEvents() {
 xhr.open("GET", kalender +
  "?aktuelleevents", true);
 xhr.send(null);
 xhr.onreadystatechange = ausgabe;
}

// Ausgabe
function ausgabe() {
 if (xhr.readyState == 4 &&
     xhr.status == 200) {
  if (xhr.responseText.indexOf("|") == 4 &&
      xhr.responseText.length <= 7) {
   var datum = xhr.responseText.split("|");
   zeigeKalender(datum[0], datum[1]);
  }
  else {
   document.getElementById("anzeige").style.visibility = "visible";
   document.getElementById("anzeige").innerHTML = xhr.responseText;
   document.getElementById("anzeige").style.opacity = "0.0";
   scrollTo("anzeige");
   for (var i = 0 ; i <= 100 ; i++ ) {
    window.setTimeout('setOpacity("' + (i / 10) + '","' + "anzeige" + '")' , 4 * i);
   }
  }
 }
}

function setOpacity(wert, ID) {
  var opa = (wert / 10);
 document.getElementById(ID).style.opacity = opa.toString();
}

// Aktiven Tag löschen
function aktivenTagLoeschen() {
 for (var tag = 1; tag <= 31; tag++) {
  if (document.getElementById("n" + tag) !== null) {
   document.getElementById("n" + tag).classList.remove("aktivtag");
  }
 }
}

// Anzeige beenden
function anzeigeBeenden() {
 document.getElementById("anzeige").innerHTML = '';
 document.getElementById("anzeige").style.visibility = "hidden";
 scrollTo("kalender");
 aktivenTagLoeschen();
}

// Anzeige beenden 2
function anzeigeBeenden2() {
 document.getElementById("kalenderOptionen").outerHTML = '';
}

function scrollTo(id) {
 if (window.innerWidth <= 650) {
  document.getElementById(id).scrollIntoView();
 }
}
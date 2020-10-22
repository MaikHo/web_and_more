<?php
/*
 * Event-Kalender - Event exportieren - kalender_ical-export.php (utf-8)
 * - https://werner-zenk.de

  Dieses Script wird in der Hoffnung verteilt, dass es nützlich sein wird, aber ohne irgendeine Garantie;
  ohne auch nur die implizierte Gewährleistung der Marktgängigkeit oder Eignung für einen bestimmten Zweck.
  Weitere Informationen finden Sie in der GNU General Public License.
  Siehe Datei: license.txt - http://www.gnu.org/licenses/gpl.html

  Diese Datei und der gesamte "Event-Kalender" ist urheberrechtlich geschützt (c) 2018 Werner Zenk alle Rechte vorbehalten.
  Sie können diese Datei unter den Bedingungen der GNU General Public License frei verwenden und weiter verbreiten.
 */


if (isset($_GET["export"])) {
 include "verbindung.php";
 $select = $db->prepare("SELECT  `start`, `ende`, `name`, `event`, `beschreibung`, `id`
                                      FROM `" . $TABLE_PREFIX . "_kalender`
                                       WHERE `id` = :id");
 $select->execute([':id' => $_GET["export"]]);
 $event = $select->fetch();
 sscanf($event["start"], "%4s-%2s-%2s %2s:%2s", $dbJahr, $dbMonat, $dbTag, $dbStunde, $dbMinute);
 sscanf($event["ende"], "%4s-%2s-%2s %2s:%2s", $dbJahr2, $dbMonat2, $dbTag2, $dbStunde2, $dbMinute2);
 $cal_start = $dbJahr . $dbMonat . $dbTag . 'T' . $dbStunde . $dbMinute . '00'; // 20161028T143000
 $cal_end = $dbJahr2 . $dbMonat2 . $dbTag2 . 'T' . $dbStunde2 . $dbMinute2 . '00';
 $cal_current_time = date("Ymd") . 'T' . date("His");
 $cal_title = html_entity_decode($event["event"], ENT_COMPAT, 'UTF-8');
 $cal_location = preg_replace('/([\,;])/','\\\$1','Location');
 $cal_description = strip_tags($event["beschreibung"]);
 $cal_description = preg_replace('/\[.*?\](.*)\[\/.*?\]/isU', '$1', $cal_description);
 $cal_url = '';
 $cal_dname = utf8_decode($event["event"]);
 $cal_dname = preg_replace("/[^a-z0-9_-]/", "", strtolower(strtr($cal_dname, "äöüß ", "aous_")));
 $cal_dname = substr($cal_dname, 0, 45) . ".ics";
 $cal_ics_content = '
BEGIN:VCALENDAR
VERSION:2.0
CALSCALE:GREGORIAN
BEGIN:VEVENT
DTSTART:' . $cal_start . '
DTEND:' . $cal_end . '
LOCATION:' . $cal_location . '
DTSTAMP:' . $cal_current_time . '
SUMMARY:' . $cal_title . '
URL;VALUE=URI:' . $cal_url . '
DESCRIPTION:' . $cal_description . '
END:VEVENT
END:VCALENDAR';
 $length = strlen($cal_ics_content);
 header("Content-Type: text/calendar");
 header("Content-Disposition: " .
 (!strpos($_SERVER["HTTP_USER_AGENT"], "MSIE 5.5") ? "attachment; " : "") .
  "filename=" . $cal_dname);
 header("Content-Length: " . $length);
 header("Content-Transfer-Encoding: binary");
 header("Cache-Control: post-check=0, pre-check=0");
 echo $cal_ics_content;
 exit;
}
?>
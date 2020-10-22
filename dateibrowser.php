<?php
/*
 *  Webseitenschutz
 *  Diesen PHP-Code für alle Seiten benutzen
 *  die geschützt werden sollen.
 */
session_start();
if (!isset($_SESSION["login"])) {
	header("Location: index.php");
	exit; 
}
if ($_SESSION["Admin"] !== true) {
	header("Location: index.php");
	exit; 	
}






?>



<!DOCTYPE html>
<html lang="de">
 <head>
  <meta charset="UTF-8">
  <title>Dateibrowser</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <style>
  body, a:link, input, textarea {
   font-family: Verdana;
   font-size: 0.97rem;
   text-decoration: None;
  }

  body {
   cursor: Default;
  }

  a:link {
   color: #4169E1;
  }

  a:visited {
   color: #4169E1; /* #C000C0 */
  }

  a:hover {
   text-decoration: Underline;
  }

  a.nav:link, a.nav:visited {
   color: #000000;
   font-size: 1rem;
   text-decoration: None;
   text-shadow: 1px 1px 1px #D0D0D0;
      /*padding: 0px 1px 0px 1px;
   border: Solid 1px #FFFFFF;*/
  }

  a.nav:hover, a.nav:hover {
   color: #4169E1;
   /*background-color: #E3E9EF;*/
  }

  nav {
   word-spacing: 0.4rem;
   text-align: Center;
  }

  nav a.nav {
   font-family: Arial, Tahoma, Sans-Serif;
  }

  cite {
   color: #EE0000;
   font-style: Normal;
  }

  mark {
   font-family: Monospace;
   color: #4169E1;
   background-color: #F5F5F5;
   padding: 0px 5px 0 5px;
  }

  s, del {
   text-decoration: line-through Red;
  }

  hr {
   background-color: #E0E0E0;
   height: 1px;
   border: 0px None;
  }

  /* Status */
  .status {
   font-size: 0.85rem;
   color: #009900;
  }

  .anweisung, .fehler {
   color: #EE0000;
  }

  .readonly {
   background-color: #EEEEEE;
   border: Solid 1px #7A7A7A;
  }

  fieldset {
   padding-left: 10px;
  }

  legend a {
   font-weight: Bold;
   font-size: 20px;
  }

  /* Adresszeile */
  .adresse {
   outline: solid 1px #A8A8A8;
   font-size: 16px;
   padding: 2px 10px 2px 10px;
   white-space: Nowrap;
   cursor: Text;
  }

  .adresse a:link, .adresse a:visited {
    font-size: 16px;
    color: #0000EE;
    font-weight: Normal;
  }

  .adresse a:hover {
    /*color: #EE0000;*/
  }

  /* Filter */
  a.filter:link, a.filter:visited {
   font-size: 0.80rem;
  }

 #filterlist {
  background-color: #EEEEEE;
  padding: 4px;
  line-height: 22px;
  z-index: 25;
  position: Absolute;
  margin-top: 21px;
  margin-left: -15px;
 }

  /* Dateisymbole */
  a.ordner::before {
   content: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABIAAAAPCAYAAADphp8SAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAAadEVYdFNvZnR3YXJlAFBhaW50Lk5FVCB2My41LjEwMPRyoQAAAj9JREFUOE+N0+tPUmEcB3D+t3LN2lhzjRcpSNnNVUr3bGNptRQ3VutNa269abJqztscapFcJUEgVOR+B+XiEQEPMBD69vi4znbq0Drb99Xv+X52nt/OEdWZabC7arBpNcpxFUrRMZQiKhyEXqIQeA7GN4z8thJ7gXGweSPIIxKKqJJR46g6h9ZRjhx8h/3YKMLOITQqOrQaJi51VouMZ5R0/gXVFmiAJk18vR/b1qeoFqbRrOvJ7Csa7CLSzrtk/h/QCQZE1q5gw/AYO5sjFKiX51A7mELSoTget4HIfvhQE0FLL9wr95H6oaRAhZnEYe49EvYBMm8DHe6Mc0i9MoufLRY+w0W4dANIOB5SoLzzFsXka8RsN0mnDVROjXFIrfQZzUYWnm8SOJdvIG5TUKAQVYEJPiNXG8ReREN6AlAx/oJDWOYDqiUrNr90YV17CVFrPwXyvifIbt3Drvs2wqtXSU8AKkRHOKSYmQCTeAP3ohi2+W6ETX0ckHZeA8MwCJn7SE8AYkJKHpINqeBaOIu1mQsI6nsokLLLkfjeTaGg6TLpCUA5/xAPSXuG4Zg/A+uUGAGdhALxVQli5i4KBYxy0hOAMp4HPCTuegT7bAcsnzrhJ7s6BqJGMY10OQK/QUZ6AlB6Q8FDIrZB2GdOw/KxA76lE+B3Oie34NNLSU8ASrhu8ZCg5TqFzJpTf0ER43mErcK/iSjjfYWAuRdegxRevYxETiGj5hw8SxLyccroW3hXeghyB/tJLen9CUH0C3fbV+JhTzXkAAAAAElFTkSuQmCC");
  }

  a[href$=".txt"], a[href$=".js"], a[href$=".css"], a[href$=".csv"], a[href$=".sqt"], a[href$=".xml"], a[href$=".htaccess"] {
   padding-left: 17px;
   background: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABEAAAAQCAYAAADwMZRfAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAAadEVYdFNvZnR3YXJlAFBhaW50Lk5FVCB2My41LjEwMPRyoQAAAUZJREFUOE+Vkt9qgzAUh31uH6DXfYG+gV6ICEVb641QfIBRdlPHcOyymhj/tr/lxK1ME2ETfngIJ9/5EmIlSQIKY0L91+q+7yE/yxQFGYcBcRzjcnnF/T4+63Gc1rMsQxRFYJzJPQbI6XTCMPTI395RFJ8K8lM/HndVp2kKIQQOhwOqqpL7FpDj8ShVOwUiI5pOIApBKGEYYr/fq1CtQYjed+0EkuceCWYAfRQF8uvVDKHF80v+55CNBqHFthHo2gYdGXULo3FuZIQEQWCcuBbq1yC+70PUNRpRG4y+L1wZTfdE/RrE8zzjxLVQvwZxXRc1Y6g5l0b8adQ20qhdGMknQP0axHEc48S1UL8G2e12YPIVcpmZkbqn30bTW6J+DbLdbo0T10L9GmSz2aC63cDKUhqVyogrI2YwakD9GsS2bfw3cwisL6gkmjiVjSIyAAAAAElFTkSuQmCC") left no-repeat;
  }

  a[href$=".php"] {
   padding-left: 17px;
   background: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABEAAAAQCAYAAADwMZRfAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAAadEVYdFNvZnR3YXJlAFBhaW50Lk5FVCB2My41LjEwMPRyoQAAAedJREFUOE+V0t9q2nAUB3Afpo+wB+gjFDaGsDLG6BOUUWi86UXJ2ou1sovVRiKt0JuibCKCuERdwV64Vcquuo1qBtXZ6UU1fzXR737ntzWs/KxjgS8JP04+nJycSLVaxb9SLpeh6zrYFZkVjgS+jyDwMZkEPNPphIfO6F6v11EqlaDpGntnBlKpVOD743shSq1Wg+M4KBaLoHoBoTbH49FciLoggELPAqJpGsYjD+32BL1egE5nBKNl48vFAB+kLRwvLcMwDHTabXbemo3Q4chz8fPaR78fhPnRtfBZiSH2WEIymQq7om4EhA4918HVlYduNwjTajbw8WUUK89i2NtT5iOFQgEuG1qraYWA8d1C/3QT69En2JKPkFRS4YyoXkDy+Twc+/cMbpHm5RnqchQvVjfwNnsOhZA/A6d6AcnlcnAsC+eNHvtLU55P1TVsPI2yBatAf9/kCP01CtULSDabhW2aSLw5Qe5dg2fxwQIePXyOdLqM+K7OkdsVoHoByWQysIZDfL34hpR6iERCxfb2K+zuvMb+vgqF5eAgHe4S1QuIqqowGUIQdWSzT3NsCy6bEw3cc122Ah7fJYKoXkDi8TjMwYBBg78gcwbkcojqBUSWZQxvbu5A1hyI6gVEkiT8b+4iiPwC5PiCNKttjb4AAAAASUVORK5CYII=") left no-repeat;
  }

  a[href$=".gif"], a[href$=".png"], a[href$=".jpg"], a[href$=".jpeg"] {
   padding-left: 17px;
   background: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABMAAAAQCAYAAAD0xERiAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAAadEVYdFNvZnR3YXJlAFBhaW50Lk5FVCB2My41LjEwMPRyoQAAAkxJREFUOE+tlG1IU1EAhm/9DoJoVFBRQqIUFhpSaR/2w4qSom1gbvMDoQ+JPrSCtDHxQg4tda4y9EaEmZjmJP0hs6Cc/vBH5ijCZuu7EFtQ0aa5zafdK1qp2IJeeLjcw3sezj2ccwWLxYLBYECv16PT6UhLS5sVq9VKKMJMCKmpqQSDQcbGxv6K3JOFUyUTKDK5KCw3Ep1SzWZDPWcr+6hq+kCN7TNDXwL4RoOhLkovLNn8RAvJh+9QUfeCvPJexGv9XL37iT73KE9ejYS6/yDblt3Mhqwm4jIaic+ykVP6CGvzEAODAbz+AKOB8U+dkH189155/s6kLD7TRmxmC/HZbWgKurjUMkit/RuNXV4GvwYIqZSeVqvlVr6RioQkRkaUFU+XCVtq2Xr0HjHZ7WiMPRRIbhq7fTie/eDB0+80ODxYDx6hfHUc4q4UOjs7lXkzy9bXodK2EZFpJ+FEN1rRSV71S45XvSb9oou9+0zYouPYHhmFy+X6QzLBL1lSKytyHKgyHpJ4ppf0sgGOVb/h1JVeinceQIpYh8VsRq1Wh+ZNF8lMyp73e0Lv4+l766f1sZd88ToNMZsoiNpIrP6yskfhnbMFe5i3ZD/JaiOnxXrOqdO5v3IN+bs13LT3KB2Z8GTCUgWVahWFRcXcNoksnruIhct2ELn2EHNU+vBk8jURxUpyc4243W5KSi6g0WZQVGihtOwG580SJlNVeNdJjtfnQ3fSSk2NhCRJ+P1+ZXxqwpJ5PB46OjpwOp0MDw8rYzNlVtn/+wUh/AShSGjF2x1+5QAAAABJRU5ErkJggg==") left no-repeat;
  }

  a[href$=".htm"], a[href$=".html"] {
   padding-left: 17px;
   background: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAwAAAAQCAIAAACtAwlQAAAABnRSTlMAAAAAAABupgeRAAAB40lEQVR42n2RTUgbQRiG59hTBe89lQ0pxUIhFxV6aKRQbClFSo16ES+WVrQiehFKWw+ptHvLqSEhbl0j+TGbjazNtoFqxUSL1vw0giWapE2CTSG7O+tOWFf8NN4EHz6+GeZ9mBlmUCaT+XwBURShQ4QaCIJwfAFCiPg1Fub5cy8SicDqEWCcVaMZBscv6rq+EOLS6TTief5UMhrZ+QjwiwKUsBT1BwKI47hTSdc1jWAFS1INwFghGikU/uTzBc8Mg4LBIGwr/Svzm3vPYqXhdfnlhjK0Wor+KqqqChEIyOfzqYeHu9MPdt+3z0/ZzIHEvS9VcyC3Xdj7vSMSUgcBeb1eRVH237QUB02lKVOc7rnyMdlMJ2a+JfPZYYwxCIhlWVmWa9PX9wdM30cst0aZayPhq/1MNutW/76DCATEMAzMUr03cjZqtcP8YahzbPJpk3WClF4rW1ZJlkFAbrdbxWr0cWvqPpXro1ib5U5b14q9BS83/3h1G44DATmdTrhd7JMrZLmZ7qRyvVTxOVUep3ZemFa8rnq9DgJyOBzwdOVyJRoKu7q6PR13PQ+tc08eLbGzlcoBRCAgmqYNw4DPqknSdjK1Fk+sxdc3t35W/1c1TYMIBGS3241LAQH5/f63lwLCCU5CoGJJSQ9dAAAAAElFTkSuQmCC") left no-repeat;
  }

  a[href$=".zip"] {
   padding-left: 17px;
   background: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA4AAAAPCAIAAABbdmkjAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAAYdEVYdFNvZnR3YXJlAHBhaW50Lm5ldCA0LjAuM4zml1AAAAIdSURBVChTfc/JTxNRAMfx+c88eOHiRU0wxi3a9lCjJlKkkQIVaKuB0AqIaLWlq11SIlC1orgECIQ2LG0wEjvd6HSbdtqZzr50fEMJBw9O3mXy/eSX96DGvvmfU0q6vof6Mmua7m8t+0E++SB0d0wWP8lCtMO/73ARiQ0Xaph5NvZwYF4kfQQ8rYAurcZHZGGlw0c6XFBi/SLt8Qfdl3rvAcq33vK4UwFdWt5+3OEXO2xAoj0i5RTab1BO/g1jgHLYHIvNA3BKixv9EhuSaK9IOgTiFY/PibK8mmgCyqI2Bp0B4JQWfj4Q6XciuSAQr/nWLIc9D8V2bPYgoExlEk0a8j/ut0oHCs2uaUXKIxB2vvmCa9hYdNJodVF7ultqE4lYct/uVg7H9pe12bgHSn9WCYSTa748cRNM1RKJhE3jo5qb10lkHNR23kLkTdv+O9DRxxt8y842ZpjaBF0xU6VRqfo1uuQHFyALRlBxeKher295VdCv5SvKS2tTikOM5PFQd3Ur+pTIGUBtHvUBuulWQanFiyw6zVSeUcgTsjDYzumZ8urKUgCs4rAeVOxQq1CXGjoIX6CrVgoxk4VhIqcnMrqzVTzdD2o9eftaDN5YUEO7gR66MkUhpnZ+mMjo8bSOgX3d1dafR6Cie1d7vKl1hxpK+M7TZStZNLfzI0R2EIcHzlZbaT2otcRlZLM3HjZAO+5z/z/rDg1wxdSXv5it06I3cvzRAAAAAElFTkSuQmCC") left no-repeat;
  }

  a[href$=".mp3"], a[href$=".mp4"] {
   padding-left: 17px;
   background: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAwAAAAQCAYAAAAiYZ4HAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAAYdEVYdFNvZnR3YXJlAHBhaW50Lm5ldCA0LjAuOWwzfk4AAAGOSURBVDhPjZLLMgNRFEXvn/gTX8RclZRHXkI6QiIPCSGRZJSBIYYmHj9goIowoKkoCZGW7nS2uw8XwUBX7epbt/c6tc85rWq1GnaqVVQqFZTLZWxtbaNUKmFjcxPFYhHrhQL0o4wUzb7vf2pADb5Ur9dHAVam0fU8uK6Hvuui33fx+qFfAGMIYMwjQB+NRgOWZYlWUikoZiZA4+l1F+P5JtTUmWjm4B7D4VBHG8DzBkiurEKxQeY+veqKKbBv467joNV1GQWPjo/EYUuARDIJxWmwOVY25sCeLdUfuh4md28wFjsXwEosQ3F0BFjdmE2kCW222z05E4hbCSjO2QD2H8Dt4xewuBSHyufXpSmJpM12+x2iaJ7W7/H8pQY8xBaXoLK5HDwNnDSfpZIYdQxj5t3xZUf2FI3FoDLZrACO86o/PEk1E4nno4sOXnqOAJHoAlR6LSP5ehoYlSNGIwKhSBQqlU6PAt9MP4FgOAzFdct/9A/NBUNQXDc3yKVwzhwdp8EGmZkxWJnm2fkg3gD9DHh0rG/JbwAAAABJRU5ErkJggg==") left no-repeat;
  }

  a[href$=".pdf"] {
   padding-left: 17px;
   background: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAwAAAAQCAYAAAAiYZ4HAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAAYdEVYdFNvZnR3YXJlAHBhaW50Lm5ldCA0LjAuOWwzfk4AAAGWSURBVDhPjZG5TgJhFIX/xMTWTt/BxifxJSx4BqOFyCY7CLLLKpAQE3kEGyEmFhYkLnELCZWBBpCZYcnxPzMyoDROcjL/5D/fnXPvFZVKBRflMkqlEorFIvL5AnK5HM6zWWQyGaTSachHzCVons1mpqbUdKFqtfobYGUae0MNu4U21g8fsR18xc37AKo2XgUYg0Dqtoe9yw66A1UHd8JvEtDQarXQbDZRq9UQjkQgmJlAuzfC2sEDxL6hDeuTDqiqBkVqMpkiFD6FYIPM/dlXddP1Sx/3nSEaHzLSj3kOBEIhCE6DzWkyb6zRxZbjGZtS+bueaVZUVQf8gSAER0eADS60iELzSDEAnz8AwTkbgDT9Mhq6qtdhsVh0wOP1QSSTKQlMV4w0KbIy38YfJnB7vBDxRAITCfCC8vp85pnG+XksAZfbDRGLx01gueLy+2uk6IDTdQIRPYvp+XhBeWXO+ZnGZcDudEFEolETYMWRvKThrwjYHA4Irpsf/5HVZofgurlBLoVz5ug4DTbIzIzByjQfHdvwDf+lcWYi7AneAAAAAElFTkSuQmCC") left no-repeat;
  }

  a[href$=".ogg"] {
   padding-left: 17px;
   background: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA4AAAAQCAYAAAAmlE46AAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAAYdEVYdFNvZnR3YXJlAHBhaW50Lm5ldCA0LjAuOWwzfk4AAAIESURBVDhPlZE7T1MBGIY/nRt+gIM6uBE3dxddlMnJGEOQRDA4CIKSqPE6GCNEZCEqIsFEiRpIoCDhIj1cDocWCkYUS4pCKbYUwh1zLLSPX7GDTevg8OSc770M5z0CZCTcdp9Qyx19zexnFLeW5gkVC4uXhY3wjErpmTQhgd/TzcLDw0QqsvGZ7SqlZ9KEBL2mmwn/LN7JaTpcpkrpmTThVzRKm8vi0/Q8Y74Ab98b2LatVmou5UgQnDBx15czZTTiG2ikr6aUGa9LrdRcypHA+6SY4EVhXcexbwnhIsH96IJaqbmUIx7bob/wEKFLwpoWf2pxuUzoOXuA2M62Rv5RXFhcov32KeZuHmT5ioP1uw7C9/bTXHaS0EJEIxmKMXsTa9BFU+cAQyNeRodNvJ4hDNOiobmDPlc30a01jf5VtCNT/CjKouHGOVr7xxgYncAcn8T6+JVea5xXumxVSR5WjoNlv2e3vFvcMB7w+bTQkpPF8ON8PM7nuD+0MmI4cTuf0V+Ry+vjWXQdFebeXEsW43E2ao/wrVAwTgi+M2rmC6GCPSwV72WlRJjNFbqOCV/yhNWabIjHkJ31IHaVTl8prJRrQecPFwiR87qo/pbVUn1e1ZWvC9FqYfupENsM6Ci+amjb94d3Sn2SOuWF8jJJk9Kp9CjfK5FAbx2D+n3/w3xfLb8BeJaH+/Tr2j8AAAAASUVORK5CYII=") left no-repeat;
  }

  a[href$=".ttf"], a[href$=".woff"], a[href$=".woff2"], a[href$=".eot"] {
   padding-left: 19px;
   background: url("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAANCAIAAAAv2XlzAAAABnRSTlMAAAAAAABupgeRAAABVUlEQVR42oWSy06DQBSGeR0fxPgSbtzoQzTxHVy6cWWiGxPTWEorRqOhBVouldKiddOkFUEuUlAoK39maKW68Nswc/7zZc5MYARBePsPy7KGwyFD6ff7eb4klJ/leocgL0jTFI5pmqWw0Zdly2xTJKBb07TCoUKWZekfUKw6qIiiyPR6Pay+CJ8VaKWqAUVRGFmWESdJslgswjAajJ2PFSgiqjqqqjKSJNFudMiPTu3IcF3P9/0gCFD85ZQC7UbPBTfe3hfOr6x3AoqFE/84hSBKEoIwDPnOtH3/vFcTdw7u8PaO4+CQ0kkS3KcUdF1HgBlOLw11MGnejrZ2H26kF9u2Pc9DFEVRHMf0EEVRGVwc3dro9aw1mc1t05oenhjH7NNsPsdUiCqHpMUrdbpdn1D9F+jWdV0sMO16qkJgWZbjuCah0WAbK2gFUbvVvibwPF+v178ByRQhejCRJEkAAAAASUVORK5CYII=") left no-repeat;
  }

  iframe {
   background-color: #FFFFFF;
   border: Solid Thin #AFAFAF;
   margin-left: 10px;
   display: block;
   height: 550px;
   position: Fixed;
   top: 100px;
   right: 25px;
   box-shadow: 1px 1px 10px #AFAFAF;
  }

  div.sticky {
   position:Sticky;
   top: 0px;
   background-color: Whitesmoke;
   font-family: Arial, Sans-Serif;
   font-size: 1.4rem;
   padding-left: 15px;
  }

  figure img {
   max-width: 100%;
   height: Auto;
   box-shadow: 1px 1px 8px #999999;
  }

  fieldset.tools {
   background-color: #F5F5F5;
  }

  fieldset.tools legend {
   font-size: 1.1rem;
   letter-spacing: 1px;
  }

  input[type=checkbox]:checked + label {
   color: #4169E1;
  }

  input[type="text"], textarea {
   border: Solid 1px #7A7A7A;
   font-family: Verdana, Arial, Sans-Serif;
   caret-color: #FF4500;
  }

  input[type="text"]:focus, textarea:focus {
   border: Solid 1px #0078D7;
  }
  </style>

  <script>
  function anzeige(ID) {
   document.getElementById(ID).style.display= (document.getElementById(ID).style.display=="none") ? "inline-block" : "none";
  }

  function framestatus(datei) {
   document.getElementById("framestatus").innerHTML = "<mark>Frame: " + datei + "</mark>";
  }
  </script>

 </head>
<body>

<?php
/*
 * dateibrowser.php (utf-8)
 * https://werner-zenk.de
 */

error_reporting(E_ALL);
ini_set('display_errors', true);
date_default_timezone_set("Europe/Berlin");
setlocale(LC_TIME, "de_DE", "german");


$status = "";
if (strstr($_SERVER["REQUEST_URI"], "&")) {
 $status = explode("&", $_SERVER["REQUEST_URI"]);
 $status = ucfirst(end($status));
}


// Quelltext
if (isset($_GET["qt"])) {
 echo '<fieldset class="tools">
<legend>Quelltext: <strong>' . basename($_GET["qt"]) . '</strong></legend>';
 $text = highlight_file($_GET["qt"], true);
 echo iconv("","UTF-8", $text);
 exit('</fieldset>
</body>
</html>');
}


// Löschen
if (isset($_GET["delete"])) {
 if (file_exists($_GET["delete"])) {
  if (is_writeable($_GET["delete"])) {
   if (unlink($_GET["delete"])) {
    $status = 'Datei: "' . $_GET["delete"] . '" wurde gelöscht.';
   }
   else {
    $status = '<span class="fehler">&#128473; Fehler beim löschen!</span>';
   }
  }
 }
}


// Kopieren
if (isset($_GET["copy"])) {
 $nn = basename($_GET["name"]);
 if (!file_exists($_GET["name"]) &&
    $nn != "" &&
    $nn != "null") {
  if (copy($_GET["copy"], $_GET["name"])) {
   $status = 'Datei: "' . $_GET["copy"] . '" wurde kopiert.';
  }
  else {
   $status = '<span class="fehler">&#128473; Fehler beim kopieren!</span>';
  }
 }
}


// Umbenennen
if (isset($_GET["rename"])) {
 $nn = basename($_GET["name"]);
 if (!file_exists($_GET["name"]) &&
     $nn != "" &&
     $nn != "null") {
  if (is_writeable($_GET["rename"])) {
   if (rename($_GET["rename"] , $_GET["name"])) {
    $status = 'Datei: "' . $_GET["rename"] . '" wurde umbenannt.';
   }
   else {
    $status = '<span class="fehler">&#128473; Fehler beim umbenennen!</span>';
   }
  }
 }
}


// Neu / Bearbeiten
if (isset($_GET["edit"])) {
 $inhalt = "";
 $dateiname = "";
 $neu = "";
 $verzeichnis = str_replace("//", "", $_GET["verzeichnis"]);
 $pfad = pathinfo(rawUrlDecode($_GET["verzeichnis"]));
 if (isset($pfad["extension"]) &&
     strlen($pfad["extension"]) > 1) {
  $inhalt = file_get_contents(rawUrlDecode($_GET["verzeichnis"]));
  $dateiname = rawUrlDecode($pfad["basename"]);
 }
 else {
  $dateiname = "unbenannt.txt";
  $neu = "neu";
 }

 echo '<form action="?save&amp;neu=' . $neu . '&amp;verzeichnis=' . $pfad["dirname"] . '" method="post">
 <fieldset class="tools"><legend>Neu &#10072; Bearbeiten</legend>

<p>
 &#128449; Verzeichnis: <em class="status">' . dirname($verzeichnis) . '</em>
<input type="hidden" name="verzeichnis" value="' . $verzeichnis . '"><br>
<label>&#128463; Dateiname: <input type="text" name="dateiname" value="'. $dateiname .'" style="width: 50%;" ' . 
 ($neu == "" ? 'readonly="readonly" class="readonly"' : ' required="required" autofocus="autofocus"') . '></label>
 &nbsp; <input type="submit" value="&#128427; Speichern"><br>
</p>

<p><label>&#128442; Inhalt:<br>
<textarea rows="25" cols="75" name="inhalt" required="required" style="width: 100%;">' . $inhalt . '</textarea>
</label>
</p>
</fieldset>
</form>
</body>
</html>';
exit;
}


// Speichern
if (isset($_GET["save"])) {
 if (file_exists($_POST["verzeichnis"] . "/" . $_POST["dateiname"]) &&
     $_GET["neu"] != "neu") {
  unlink($_POST["verzeichnis"] . "/" . $_POST["dateiname"]);
 }
 file_put_contents($_POST["verzeichnis"] . ($_GET["neu"] != "neu" ? "" : "/" . $_POST["dateiname"]), $_POST["inhalt"]);
 $status = 'Die Datei wurde gespeichert.';
}

// Datei hochladen
if (isset($_GET["hochladen"])) {
 $verzeichnis = str_replace("//", "", $_GET["verzeichnis"]);
 $umf = ini_get('upload_max_filesize');

 echo '<fieldset class="tools">
 <legend>Datei hochladen</legend>

<form action="?hochladenstart&verzeichnis=' . $verzeichnis . '" method="post" enctype="multipart/form-data">

<p>
 &#128449; Verzeichnis: <em class="status">' . $verzeichnis . '</em><br>
 <input type="hidden" name="verzeichnis" value="' . $verzeichnis . '">
 <input type="checkbox" name="overwrite" id="overwrite"> <label for="overwrite">Eine vorhandene Datei überschreiben</label>
</p>

<p>
 <label>&#128463; Datei: <input type="file" name="datei"></label> 
 <input type="submit" value="&#11165; Hochladen"><br>
 <span class="status">PHP - upload_max_filesize: ' . $umf . '</span>
</p>
</form>

</fieldset>
</body>
</html>';
 exit;
}

// Hochladen
if (isset($_GET["hochladenstart"])) {
 if (is_uploaded_file($_FILES["datei"]["tmp_name"])) {
  if (file_exists($_POST["verzeichnis"] . '/' . $_FILES["datei"]["name"])) {
   if (isset($_POST["overwrite"])) {
    if (move_uploaded_file($_FILES["datei"]["tmp_name"], $_POST["verzeichnis"] . '/' . $_FILES["datei"]["name"])) {
     $status = 'Die Datei wurde hochgeladen und ersetzt.';
    }
   }
   else {
    $status = '<span class="fehler">&#128473; Die Datei ist bereits vorhanden!</span>';
   }
  }
  else {
   if (move_uploaded_file($_FILES["datei"]["tmp_name"], $_POST["verzeichnis"] . '/' . $_FILES["datei"]["name"])) {
    $status = 'Die Datei wurde hochgeladen.';
   }
  }
 }
}


// Base64
if (isset($_GET["base64"])) {
 $code = file_get_contents($_GET["base64"]);
 list($b, $h) = getImageSize($_GET["base64"]);
 echo '<fieldset class="tools"><legend>Base64</legend>
 <figure>
  <figcaption>
  <img src="' . $_GET["base64"] . '" width="' . $b . '" height="' . $h . '" alt="Bild"> <br>
  &#128463; Datei: ' . $_GET["base64"] . ' <br>' .
  filesize($_GET["base64"]) . ' Bytes&emsp;' . $b . ' x ' . $h . ' Pixel&emsp;' . strlen(base64_encode($code)) . ' Zeichen&emsp;' . date("d.m.Y - h:i", filemtime($_GET["base64"])) . ' Uhr
</figcaption>
 </figure>

 <p><textarea rows="12" cols="65" id="input" readonly="readonly" autofocus="autofocus">' . base64_encode($code) . '</textarea></p>

 <p><button id="copy-button" title="In die Zwischenablage kopieren">&#128464; Kopieren</button></p>

 <script>
 // Die Textauswahl in Zwischenablage kopieren
 var input = document.getElementById("input");
 var button = document.getElementById("copy-button");
 button.addEventListener("click", function (event) {
  event.preventDefault();
  input.select();
  document.execCommand("copy");
 });
 </script>
  
 </fieldset>
 </body></html>';
 exit;
}


 /* Browser */

$dateibrowser = basename($_SERVER["SCRIPT_NAME"]);
$lesezeichenDatei = "lesezeichen.txt"; // Datei muss erstellt werden, benötigt Schreibrechte Chmod 777

// Dateien die nicht angezeigt werden sollen
$versteckteDateien = [$dateibrowser,
                                 $lesezeichenDatei,
                                 "Desktop.ini",
                                 "desktop.ini",
                                 "favicon.ico",
                                ];

// Textdateien
$arTextdateien = ["txt", "htm", "html", "css", "js", "xml", "csv", "php", "tpl", "ini", "htaccess"];

// Bilddateien
$arBilddateien = ["gif", "jpg", "png"];

// Verzeichnis setzen
$verzeichnis = ((isset($_GET["verzeichnis"])) ? str_replace(["./.", "././", "//", "../"], ["./", "./", "/", "./"], $_GET["verzeichnis"]) : ".");

// Verzeichnis lesen
if (is_dir($verzeichnis)) {
 $inhalt = array_slice(scanDir($verzeichnis), 2);
}
else {
 $status = '<span class="fehler">&#128473; Verzeichnis: <em>' . $verzeichnis . '</em> wurde nicht gefunden!</span>';
 $verzeichnis = ".";
 $inhalt = array_slice(scanDir($verzeichnis), 2);
}


// Verzeichnis (absteigend/aufsteigend) sortieren
$sortieren = "";
if (isset($_GET["sortieren"])) {
 if ($_GET["sortieren"] == "absteigend") {
  rsort($inhalt);
  $sortieren = "&amp;sortieren=absteigend";
 }
 else if ($_GET["sortieren"] == "aufsteigend") {
  sort($inhalt);
  $sortieren = "&amp;sortieren=aufsteigend";
 }
}


// Brotkrumennavigation
$pfad = explode("/", $verzeichnis);
$brotkrume = $neupfad = "";
foreach ($pfad as $element) {
 if ($element == end($pfad)) {
  $brotkrume .= iconv("","UTF-8", $element);
 }
 else {
  $neupfad .= "/" . iconv("","UTF-8", $element);
  $brotkrume .= "<a href=\"" . $dateibrowser . "?verzeichnis=." . $neupfad . $sortieren . "\" title='Verzeichnis wechseln'>" . $element . "</a>/";
 }
}


// Verzeichnisinhalt ermitteln
$folder = $liste = $buchstaben = "";
$folder_nr = $file_nr = $size_all = 0;
$filter_link = [];
$filter = ((isset($_GET["filter"])) ? $_GET["filter"] : "");

foreach ($inhalt as $datei) {
if (is_dir($verzeichnis . "/" . $datei)) { // Verzeichnis
 if (preg_match("/$filter/i", $datei)) {
  $folder .= "<div style='background:" . (($folder_nr % 2) ? "#F9F9F9" : "#FFFFFF") . "'><a href=\"" . $dateibrowser . "?verzeichnis=" . rawurlENcode($verzeichnis . "/" . $datei . $sortieren) . "\" class=\"ordner\">" . iconv("","UTF-8", $datei) . "</a></div>\n";
  $folder_nr++;
  }
 }
 else if (preg_match("/$filter/i", $datei)) { // Datei / Filter
 if (!in_array($datei , $versteckteDateien)) {
   $type = pathinfo($verzeichnis . "/" . $datei);
   $type = isset($type["extension"]) ? $type["extension"] : $datei;
   $size = fileSize($verzeichnis . "/" . $datei);
   $file_nr++;
   if (isset($_GET["A-Z"])) {
    if (!strstr($buchstaben, $datei[0])) {
     $buchstaben .= $datei[0];
     $liste .= "<div class='sticky'>" . $datei[0] . "</strong></div>";
    }
   }
   $liste .= "<div style='background:" . (($file_nr % 2) ? "#F9F9F9" : "#FFFFFF") . "'>" . (isset($_GET["1-9"]) ? "<small>" . $file_nr . "</small> - " : "") . "<a href=\"" . iconv("","UTF-8", $verzeichnis . "/" . $datei) . "\"" . (isset($_GET["frame"]) ? ' target="fenster" onClick="framestatus(\'' . $datei . '\')"' : "") . ">" . iconv("","UTF-8", $datei) . "</a> <span class=\"status\"> " . 
   (isset($_GET["datum"]) ? '&#10151; ' . date("d.m.Y \&\\e\m\s\p\; H:i \U\h\\r \&\\e\m\s\p\;[", fileMtime($verzeichnis . "/" . $datei)) . intval((time() - fileMtime($verzeichnis . "/" . $datei)) / 86400) . " Tage] " : "") .
   (isset($_GET["chmod"]) ? '&#10151; ' . substr(sprintf('%o', fileperms($verzeichnis . "/" . $datei)), -4) . (is_executable($verzeichnis . "/" . $datei) ? " ausf&uuml;hrbar" : " <s>ausf&uuml;hrbar</s>") . (is_readable($verzeichnis . "/" . $datei) ? " lesbar" : " <s>lesbar</s>") . (is_writeable($verzeichnis . "/" . $datei) ? " beschreibbar" : " <s>beschreibbar</s>")  : "") . 
   (isset($_GET["dateipfad"]) ? '&#10151; ' . realpath($verzeichnis . "/" . $datei) : "") .
   (isset($_GET["size"]) ? '&#10151; ' . (($size < 1024) ? $size . " Bytes" : (($size >= 1048576) ? number_format(($size / 1024 / 1024), 2, ",", ".") . " MB" : number_format(($size / 1024), 2, ",", ".") . " KB")) : "") . 
   (in_array($type, $arTextdateien) && isset($_GET["quelltext"]) ?
   "&#10151; <a href=\"" . $dateibrowser . "?verzeichnis=" . $verzeichnis . "&amp;qt=" . $verzeichnis . "/" . $datei . "&amp;=uu\"><cite><small>Quelltext</small></cite></a>" :
   (in_array(strtolower($type), $arBilddateien) && isset($_GET["quelltext"]) ? '&#10151; <a href="' . $dateibrowser . '?verzeichnis=' . $verzeichnis . '&amp;base64=' . $verzeichnis . "/" . $datei . '&amp;=uu"><small><cite>Base64</cite></small></a><figure><img src="' . $verzeichnis . "/" . $datei . '" alt=""></figure>' : ''));


   // Bildgröße/Abmessungen
   if (isset($_GET["size"]) &&
       in_array(strtolower($type), $arBilddateien)) {
    $bildinfo = getImageSize($verzeichnis . "/" . $datei);
    $liste .= ' - ' .  $bildinfo[0] . ' x ' . $bildinfo[1] . ' Pixel';
   }


   // Titel / Meta-Tags
   if (isset($_GET["titel"])) {
    if ($type == "php" ||
        $type == "htm" ||
        $type == "html") {
      preg_match("/<title>(.*?)</i", file_get_contents($verzeichnis . "/" . $datei), $titel);
      if (isset($titel[1])) {
       $liste .= "&#10151; &bdquo;$titel[1]&rdquo; ";
       unset($titel[1]);
      }
     $MetaTags = get_meta_tags($verzeichnis . "/" . $datei);
     $liste .= (((isset($MetaTags['author'])) ? " <strong>Autor:</strong> ". $MetaTags['author'] : ""));
     $liste .= (((isset($MetaTags['description'])) ? " <strong>Beschreibung:</strong> ". $MetaTags['description'] : ""));
    }
   }


   // Anweisung
   if (isset($_GET["anweisung"])) {
    // Löschen
    $liste .= "&#10151; <a href=\"javascript:frage=confirm('Datei%20$datei%20loeschen?');if(frage==true){window.location.href='$dateibrowser?verzeichnis=$verzeichnis&amp;delete=$verzeichnis/$datei'}else{alert('Loeschen%20wurde%20abgebrochen');}\" title='Löschen'>&#128465;</a>&nbsp;";
    // Umbenennen
    $liste .= "<a href=\"javascript:name=prompt('Umbenennen:','$datei');window.location.href='$dateibrowser?verzeichnis=$verzeichnis&amp;rename=$verzeichnis/$datei&amp;name=$verzeichnis/'+name\" title='Umbenennen'>&#128461;</a>&nbsp;";
    // Kopieren
    $liste .= "<a href=\"javascript:name=prompt('Kopieren:','$datei');window.location.href='$dateibrowser?verzeichnis=$verzeichnis&amp;copy=$verzeichnis/$datei&amp;name=$verzeichnis/'+name\" title='Kopieren'>&#128464;</a>&nbsp;";
    // Bearbeiten
    if (in_array($type, $arTextdateien)) {
      $datei = rawUrlEncode($datei);
     $liste .= "<a href=\"$dateibrowser?verzeichnis=$verzeichnis/$datei&amp;edit\" title='Bearbeiten'>&#128442;</a>";
    }
   }


   // Suche
   if (isset($_GET["suche"])) {
    if (in_array($type, $arTextdateien)) {
     if (strlen($_GET["suche"]) > 2 && $_GET["suche"] != "null") {
      $umlaute = strtr($_GET["suche"], ["ä"=>"&auml;", "ö"=>"&ouml;", "ü"=>"&uuml;", "ß"=>"&szlig;", "\n"=>""]);
      $text = strtolower(file_get_contents($verzeichnis . "/" . $datei));
      if (strstr($text, $_GET["suche"]) ||
          strstr($text, $umlaute)) {
       $gefunden = (mb_substr_count(mb_strtolower($text), $_GET["suche"]) < 1) ? mb_substr_count(strtolower($text), $umlaute) : mb_substr_count(strtolower($text), $_GET["suche"]);
       $liste .= " &#10151; <strong>" . $gefunden . "x</strong> &bdquo;<i>" . $_GET["suche"] . "&rdquo;</i>";
      }
     }
    }
    $status = 'Suche nach: "' . $_GET["suche"] . '"';
   }


   $liste .= "&thinsp;</span></div>\n";
   $size_all += $size;
   array_push($filter_link, "<a href=\"" . $dateibrowser . "?verzeichnis=" . $verzeichnis . "&amp;filter=." . $type . "\" class=\"filter\">" . strtoupper($type) . "</a><br>\n");
   $liste .= ($file_nr % 45) ? "" : '<div style="text-align: right;"><a href="#top" class="nav">&#8793; <small>Seitenanfang</small></a>&nbsp;</div>';
  }
 }
}


// Lesezeichen-Verwaltung
$lesezeichen = "";
if (isset($_GET["lesezeichen"]) ||
    isset($_GET["lesezeichen_setzen"]) ||
    isset($_GET["lesezechen_loeschen"])) {
 if (file_exists($lesezeichenDatei)) {
  $meineLesezeichen = file($lesezeichenDatei, FILE_SKIP_EMPTY_LINES);

  // Lesezeichen setzen
  if (isset($_GET["lesezeichen_setzen"])) {
   if (!strstr(implode("", $meineLesezeichen), $_GET["lesezeichen_setzen"])) {
    array_push($meineLesezeichen, $verzeichnis . "|" . time() . "|\n");
    sort($meineLesezeichen);
    file_put_contents($lesezeichenDatei, implode("", $meineLesezeichen));
   }
  }

  // Lesezechen loeschen
  if (isset($_GET["lesezechen_loeschen"])) {
   foreach ($meineLesezeichen as $zaehler => $element) {
    list($lz, $id) = explode("|", $element);
    if (trim($id) == $_GET["lesezechen_loeschen"]) {
     array_splice($meineLesezeichen, $zaehler, 1);
     file_put_contents($lesezeichenDatei, implode("", $meineLesezeichen));
     $status = 'Lesezeichen wurde gelöscht.';
    }
   }
  }

  // Lesezeichen anzeigen
  foreach ($meineLesezeichen as $nr => $element) {
   list($lz, $id) = explode("|", $element);
   $lesezeichenName = explode("/", $lz);
   $lesezeichenName = end($lesezeichenName);
   $lesezeichenName = str_replace(["-", "_"], " ", $lesezeichenName);
   $lesezeichenName = ucwords($lesezeichenName);
   $lesezeichen .= "<div style='background-color: " . (($nr % 2) ? "#F9F9F9" : "#FFFFFF") . ";'>&#10030; ";
   $lesezeichen .= is_dir($lz) ? "<a href=\"" . $dateibrowser . "?verzeichnis=" . $lz . "\"><em>" . $lesezeichenName . "</em></a> &#10151; <small>" . $lz . " <b>" . date("d.m.Y", $id) . "</b></small> " :
                                           "" . $lesezeichenName . " &#10151; <small><del>" . $lz . "</del> <b>" . date("d.m.Y", $id) . "</b></small>";
   $lesezeichen .= " <a href=\"" . $dateibrowser . "?verzeichnis=" . $verzeichnis . "&amp;lesezechen_loeschen=" . $id . "\" title=\"Lesezeichen löschen\"><span class=\"anweisung\">&#128465;</span></a> </div>";
  }
  $lesezeichen .= "<hr>";
 }
 else {
  echo '<span class="fehler">&#128473; Datei "' . $lesezeichenDatei . '" nicht vorhanden!</span>';
 }
}


$size_all = ($size_all < 1024) ? $size_all . " Bytes" : (($size_all >= 1048576) ? number_format(($size_all / 1024 / 1024), 2, ",", ".") . " MB" : number_format(($size_all / 1024), 2, ",", ".") . " KB");
$referrer = isset($_SERVER["HTTP_REFERER"]) ? pathinfo($_SERVER["HTTP_REFERER"]) : "./";
$referrer = str_replace("&", "", @$referrer["basename"]);
$filter_link = implode("", array_unique($filter_link));
$filter_link = isset($_GET["filter"]) ? $_GET["filter"] : $filter_link;
$liste .= ($file_nr + $folder_nr) > 40 ? '<br><a href="#top" class="nav">&#8793; <small>Seitenanfang</small></a>' : "";
$liste = ((isset($_GET["frame"])) ? $liste . '<a name="frame">&nbsp;</a><iframe name="fenster" srcdoc="Bitte eine Datei auswählen!" width="75%" scrolling="auto"></iframe>' : $liste);

$status = $status != "" ? '<mark>' . $status . '</mark>' : $status;
$referrerText = urldecode($referrer);
$server =  $_SERVER["SERVER_NAME"];


// Daten ausgeben
echo <<<EOT
<fieldset>
 <legend>
   &thinsp; <a href="javascript:history.back()" title="Zur&uuml;ck: $referrerText" class="nav">&#9665;</a>&thinsp;
  <a href="javascript:history.forward()" title="Vorw&auml;rts" class="nav">&#9655;</a>&nbsp;
  <a href="javascript:location.reload()" title="Aktuelle Seite neu laden" class="nav">&#11118;</a>&nbsp;
  <a href="$dateibrowser?verzeichnis=./" title="Home" class="nav">&#9750;</a>&nbsp;
 <span class="adresse">&#128449; $brotkrume</span>&nbsp;
  <a href="$dateibrowser?verzeichnis=$verzeichnis&amp;lesezeichen" title="Lesezeichen anzeigen" class="nav">&#128366;</a>&thinsp;
  <a href="$dateibrowser?verzeichnis=$verzeichnis&amp;lesezeichen_setzen=$verzeichnis" title="Lesezeichen für dieses Verzeichnis setzen" class="nav">&#10030;</a>&nbsp;
  <a href="$dateibrowser?verzeichnis=$verzeichnis&amp;sortieren=aufsteigend" title="Aufsteigend sortieren" class="nav">&#9650;</a>
  <a href="$dateibrowser?verzeichnis=$verzeichnis&amp;sortieren=absteigend" title="Absteigend sortieren" class="nav">&#9660;</a>&nbsp;
  <a href="javascript:var%20s=prompt('Suche%20in%20Dateien%20nach:','');location.href='$dateibrowser?verzeichnis=$verzeichnis&amp;suche='+s.toLowerCase()" title="Suche in Dateien" class="nav">&#9711;</a> <span id="framestatus">$status</span>&thinsp;
 </legend>
 <nav>
  <a href="$dateibrowser?verzeichnis=$verzeichnis&amp;A-Z$sortieren" class="nav" title="Alphabetische Auflistung A-Z">A-Z</a> 
  <a href="$dateibrowser?verzeichnis=$verzeichnis&amp;1-9$sortieren" class="nav" title="Nummerische Auflistung 1-9">1-9</a> 
  <a href="$dateibrowser?verzeichnis=$verzeichnis&amp;datum$sortieren" class="nav" title="Datum (Letzte Änderung)">Datum</a> 
  <a href="$dateibrowser?verzeichnis=$verzeichnis&amp;size$sortieren" class="nav" title="Dateigröße | Abmessungen">Größe</a> 
  <a href="$dateibrowser?verzeichnis=$verzeichnis&amp;dateipfad$sortieren" class="nav" title="Dateipfad">Pfad</a> 
  <a href="$dateibrowser?verzeichnis=$verzeichnis&amp;chmod$sortieren" class="nav" title="Dateiberechtigung">Chmod</a> 
  <a href="$dateibrowser?verzeichnis=$verzeichnis&amp;quelltext$sortieren" class="nav" title="Quelltext | Bilder | Base64">Quelle</a> 
  <a href="$dateibrowser?verzeichnis=$verzeichnis&amp;titel$sortieren" class="nav" title="HTML-Titel | Meta-Tags">Titel</a> 
  <a href="$dateibrowser?verzeichnis=$verzeichnis&amp;frame$sortieren#frame" class="nav" title="Framefenster">Frame</a> 
  <a href="$dateibrowser?verzeichnis=$verzeichnis&amp;edit" class="nav" title="Neue Datei erstellen">Neu</a> 
  <a href="$dateibrowser?verzeichnis=$verzeichnis&amp;hochladen" class="nav" title="Datei hochladen">Hochladen</a> 
  <a href="$dateibrowser?verzeichnis=$verzeichnis&amp;anweisung$sortieren" class="nav" title="Bearbeiten | Umbenennen | Kopieren | Löschen">Anweisung</a> 
  <nobr><a href="javascript:var%20f=prompt('Filter (Dateiname, Verzeichnis):','');location.href='$dateibrowser?verzeichnis=$verzeichnis&amp;filter='+f" class="nav" title="Filter eingeben ...">Filter</a> <a href="javascript:anzeige('filterlist')" class="nav" title="Filterliste anzeigen">&#9830;</a> <span id="filterlist" style="display: none;">$filter_link</span></nobr>
 </nav>
 <hr>
 <span class="status">
 $lesezeichen$folder$liste
 <hr>
 Verzeichnisse: $folder_nr - Dateien: $file_nr ($size_all) &mdash; Server: $server
 </span>
</fieldset>
EOT;
?>

</body>
</html>
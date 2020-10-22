<!DOCTYPE html>
<html>
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width">
  <title>Demoseite</title>

  <style>
  body {
   font-family: Verdana, Arial, Sans-Serif;
   font-size: 0.85rem;
  }

  /* Link */
  a:link, a:visited {
   color: #0000EE;
   text-decoration: None;
  }
  a:hover {
   color: #EE0000;
  }

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
   padding: 0.5rem;
  }

  /* Nachrichten - Überschrift */
  dl.nachrichten dt span {
   font-size: 1.3rem;
  }

  /* Nachrichten - Bild */
  dl.nachrichten dd img.bild {
   margin: 1rem 1rem 1rem 0;
   border: Solid Medium #808080;
   float: Left;
  }

  /* Nachrichten - Hintergrund zeilenweise einfärben! */
  dl.nachrichten:nth-child(even) {
   background-color: #EAEAEA;
   border-radius: 6px;
  }
  dl.nachrichten:nth-child(odd) {
   background-color: #F5F5F5;
   border-radius: 3px;
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

 </head>
<body>

<h2>Demoseite</h2>

<p>Diese Seite ist eine Demonstration, wie die Nachrichten angezeigt werden können.<br>
Mit CSS werden die Nachrichten formatiert; Zum ändern, hier im Quelltext die CSS-Anweisungen anpassen.</p>

<?php
include "nachrichten.php";
?>

</body>
</html>
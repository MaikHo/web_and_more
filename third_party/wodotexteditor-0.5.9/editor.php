<?php
session_start();
if (!isset($_SESSION["login"])) {
 header("Location: ../index.php");
 exit; 
}
?>

<!DOCTYPE HTML>
<html style="width:100%; height:100%; margin:0px; padding:0px" xml:lang="en" lang="en">
  <head>
    <!--
    This HTML page is meant to bootstrap the webodf based ODF editor. This
    is not the HTML page hosting any collaborative editing.
    It will run on a standard HTTP server and as usual the webodf magic
    happens client-side in your browser.
    This page is meant to be served out of a webodf build directory.
    -->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <title>Local Editor</title>

    <!-- editor: start -->
    <script src="wodotexteditor/wodotexteditor.js" type="text/javascript" charset="utf-8"></script>
    <script src="FileSaver.js" type="text/javascript" charset="utf-8"></script>
    <script src="localfileeditor.js" type="text/javascript" charset="utf-8"></script>
    <!-- editor: end -->
  </head>

  <body style="width:100%; height:100%; margin:0px; padding:0px" onload="createEditor();">
    <div id="editorContainer" style="width:100%; height:100%; margin:0px; padding:0px">
    </div>
  </body>
</html>

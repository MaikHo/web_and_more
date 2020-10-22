<!--
<div class="toolbar hidden">
<hr>
  <a href="#" data-command='undo'><i class='fa fa-undo'></i></a>
  <a href="#" data-command='redo'><i class='fa fa-redo'></i></a>
  <div class="fore-wrapper"><i class='fa fa-font' style='color:#C96;'></i>
    <div class="fore-palette">
    </div>
  </div>
  <div class="back-wrapper"><i class='fa fa-font' style='background:#C96;'></i>
    <div class="back-palette">
    </div>
  </div>
  <a href="#" data-command='bold'><i class='fa fa-bold'></i></a>
  <a href="#" data-command='italic'><i class='fa fa-italic'></i></a>
  <a href="#" data-command='underline'><i class='fa fa-underline'></i></a>
  <a href="#" data-command='strikeThrough'><i class='fa fa-strikethrough'></i></a>
  <a href="#" data-command='justifyLeft'><i class='fa fa-align-left'></i></a>
  <a href="#" data-command='justifyCenter'><i class='fa fa-align-center'></i></a>
  <a href="#" data-command='justifyRight'><i class='fa fa-align-right'></i></a>
  <a href="#" data-command='justifyFull'><i class='fa fa-align-justify'></i></a>
  <a href="#" data-command='indent'><i class='fa fa-indent'></i></a>
  <a href="#" data-command='outdent'><i class='fa fa-outdent'></i></a>
  <a href="#" data-command='insertUnorderedList'><i class='fa fa-list-ul'></i></a>
  <a href="#" data-command='insertOrderedList'><i class='fa fa-list-ol'></i></a>
  <a href="#" data-command='h1'>H1</a>
  <a href="#" data-command='h2'>H2</a>
  <a href="#" data-command='createlink'><i class='fa fa-link'></i></a>
  <a href="#" data-command='unlink'><i class='fa fa-unlink'></i></a>
  <a href="#" data-command='insertimage'><i class='fa fa-image'></i></a>
  <a href="#" data-command='p'>P</a>
  <a href="#" data-command='subscript'><i class='fa fa-subscript'></i></a>
  <a href="#" data-command='superscript'><i class='fa fa-superscript'></i></a>
</div>
-->


<div class="toolbar hidden">
<hr>
	<div id="alerts"></div>
    <div class="btn-toolbar" data-role="editor-toolbar" data-target="#editor">
      <div class="btn-group">
        <a class="btn dropdown-toggle" data-toggle="dropdown" title="Schriftart"><i class="icon-font"></i><b class="caret"></b></a>
          <ul class="dropdown-menu">
          </ul>
        </div>
      <div class="btn-group">
        <a class="btn dropdown-toggle" data-toggle="dropdown" title="Schriftgröße"><i class="icon-text-height"></i>&nbsp;<b class="caret"></b></a>
          <ul class="dropdown-menu">
          <div><a data-edit="fontSize 7"><font size="7">Größe 7</font></a></div>
          <div><a data-edit="fontSize 6"><font size="6">Größe 6</font></a></div>
          <div><a data-edit="fontSize 5"><font size="5">Groß</font></a></div>
          <div><a data-edit="fontSize 4"><font size="4">Größe 4</font></a></div>
          <div><a data-edit="fontSize 3"><font size="3">Normal</font></a></div>
          <div><a data-edit="fontSize 2"><font size="2">Größe 2</font></a></div>
          <div><a data-edit="fontSize 1"><font size="1">Small</font></a></div>
          </ul>
      </div>
      <div class="btn-group">
        <a class="btn dropdown-toggle" data-toggle="dropdown" title="Schriftfarbe"><i class='fa fa-font' style='color:#C96;'></i><b class="caret"></b></a>
          <ul class="dropdown-menu">
          </ul>
        </div>
      <div class="btn-group">
        <a class="btn dropdown-toggle" data-toggle="dropdown" title="Schrifthintergrundfarbe"><i class='fa fa-font' style='color:#C96;'></i><b class="caret"></b></a>
          <ul class="dropdown-menu">
          </ul>
        </div>  
      <div class="btn-group">
        <a class="btn" data-edit="bold" title="Fett (Ctrl/Cmd+B)"><i class="icon-bold"></i></a>
        <a class="btn" data-edit="italic" title="Kursiv (Ctrl/Cmd+I)"><i class="icon-italic"></i></a>
        <a class="btn" data-edit="strikethrough" title="Durchstreichen"><i class="icon-strikethrough"></i></a>
        <a class="btn" data-edit="underline" title="Unterstreichen (Ctrl/Cmd+U)"><i class="icon-underline"></i></a>
      </div>
      <div class="btn-group">
        <a class="btn" data-edit="insertunorderedlist" title="Bullet list"><i class="icon-list-ul"></i></a>
        <a class="btn" data-edit="insertorderedlist" title="Number list"><i class="icon-list-ol"></i></a>
        <a class="btn" data-edit="outdent" title="Reduce indent (Shift+Tab)"><i class="icon-indent-left"></i></a>
        <a class="btn" data-edit="indent" title="Indent (Tab)"><i class="icon-indent-right"></i></a>
      </div>
      <div class="btn-group">
        <a class="btn" data-edit="justifyleft" title="Align Left (Ctrl/Cmd+L)"><i class="icon-align-left"></i></a>
        <a class="btn" data-edit="justifycenter" title="Center (Ctrl/Cmd+E)"><i class="icon-align-center"></i></a>
        <a class="btn" data-edit="justifyright" title="Align Right (Ctrl/Cmd+R)"><i class="icon-align-right"></i></a>
        <a class="btn" data-edit="justifyfull" title="Justify (Ctrl/Cmd+J)"><i class="icon-align-justify"></i></a>
      </div>
      <div class="btn-group">
		  <a class="btn dropdown-toggle" data-toggle="dropdown" title="Hyperlink"><i class="icon-link"></i></a>
		    <div class="dropdown-menu input-append">
			    <input class="span2" placeholder="URL" type="text" data-edit="createLink"/>
			    <button class="btn" type="button">Add</button>
        </div>        
      </div>
      <div class="btn-group">
		  <a class="btn dropdown-toggle" data-toggle="dropdown" title="HTML Elemente"><i class="icon-link"></i></a>
		    <div class="dropdown-menu input-append">
			    <input class="span2" placeholder="URL" type="text" data-edit="insertHtml"/>
			    <button class="btn" type="button">Add</button>
        </div>        
      </div>
      <div class="btn-group">
        <a class="btn" title="Füge ein Bild hinzu (oder drag & drop)" id="pictureBtn"><i class="icon-picture"></i></a>
        <input type="file" data-role="magic-overlay" data-target="#pictureBtn" data-edit="insertImage" />
      </div>
      
      <div class="btn-group">
        <a class="btn" data-edit="undo" title="Zurück (Ctrl/Cmd+Z)"><i class="icon-undo"></i></a>
        <a class="btn" data-edit="redo" title="Vor (Ctrl/Cmd+Y)"><i class="icon-repeat"></i></a>
      </div>
      <div class="btn-group">
        <a class="btn" id="delete_video" title="Drag & Drop Video löschen"><i class="icon-undo"></i></a>
        
      </div>
      <input type="text" data-edit="inserttext" id="voiceBtn" x-webkit-speech="">
    </div>
</div>





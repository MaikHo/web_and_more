$(document).ready(function(){
//$('').click(function(){
//	
//});
$('#edit_page').css('display','none' );

$('#hamburger').click(function(){
	$(this).toggleClass('open');

	if ($('#hamburger').is('.open')){
	  //alert("öffnen");
	  //$('.tabcontent').hide(750);
	  $('nav').show();
	} else {
	  //alert("schließen");
	  $('nav').hide();
	  //$('#home').show(750);
	}

});
var is_screen_width = window.screen.width;

function loadCSS(url) {
    var link = document.createElement("link");
    link.type = "text/css";
    link.rel = "stylesheet";
    link.href = url;
    document.getElementsByTagName("head")[0].appendChild(link);
}
if(is_screen_width < 800){
	loadCSS('../css/handy.css');
	loadCSS('../css/hamburger.css');
	
}
if(is_screen_width > 800){
	loadCSS('../css/desktop.css');	
}


loadCSS('../css/tabs.css');
loadCSS('../third_party/fontawesome-free-5.0.4/web-fonts-with-css/css/fontawesome-all.min.css');
loadCSS('https://netdna.bootstrapcdn.com/font-awesome/3.0.2/css/font-awesome.css');
loadCSS('../third_party/jquery-confirm-master/css/jquery-confirm.css');
loadCSS('../css/module.css');





$('.page_show').click(function(){
		var id = $(this).attr("id");		
		//$("#show_page").load('../inc_html/'+id+'.html');		
		$("#show_page").css('display', 'block');

		$.ajax({
		  url: '../inc_html/'+id+'.html',
		  cache: false
		})
		.done(function( html ) {
		$( "#show_page" ).html(html);
		});		
		$('#edit_page').css('display','block' );
		$("#edit_page").after('<div style="float:left;" id="'+id+'" class="delete_seite kachel">Die Seite '+id+' löschen</div>');
		$('.delete_seite').click(function(){
			var page_id;
			var id = $(this).attr("id");
			$.confirm({
			    title: 'Löschen der Seite '+id+'!',
			    content: '' +
			    '<form action="" class="formName">' +
			    '<div class="form-group">' +
			    '<label>Wenn Sie die Seite löschen wollen, geben Sie zur Sicherheit den Namen ein!</label><br><br>' +
			    '<input type="text" placeholder="'+id+'" class="name form-control" required />' +
			    '</div>' +
			    '</form>',
			    
			    theme: "supervan",
				animation: "bottom",
				closeAnimation: "top",
				boxWidth: "30%",
				useBootstrap: false,
			    
			    buttons: {
			        formSubmit: {
			            text: 'Löschen',
			            btnClass: 'btn-red',
			            action: function () {
			                var page_id = this.$content.find('.name').val();
							$.ajax({
								url : '../ajax/delete_page.php',
								type : 'post',
								data : {
									'data': '',
									'page': page_id
									//'text2': 'hello World',
								}
							}).done(function(msg){
								//  Cross-Origin-Request kann nur erfolgreich durchgeführt werden wenn der Server die gleiche Domain hat 
								// oder der Server bei seiner Antwort den Zugriff durch entsprechende HTTP-Header erlaubt
								// Beispiel: Access-Control-Allow-Origin: http://foo.example
								$("#ajax_meldung").html(msg);
								
								
							}).fail(function(){
								alert("Fehler");
							
							});	
			            }
			        },
			        cancel: {
			            text: 'Abbrechen',
			            btnClass: 'btn-blue',
			            action: function () {
			                
			            }
			        }
			    }
			});	
			

		});		
				
		
});




var editable_elements = '#aside_zusatz, .editable_elements';

$('#edit_page').click(function(){
	//$(editable_elements).css('background','blue' );
	$('.toolbar').removeClass('hidden');
	
	$('#edit_cancel').css('display','block' );
	
	$('#edit_cancel').click(function(){
		location.reload();		
	});  	
	
	
			var my_edit_elem = $(editable_elements);
		var my_edit = $(my_edit_elem).children();
		var page_id = $(my_edit).attr('id');
		
		
		$('#edit_page').css('display','none' );
		$('#save').css('display','block' );
		
		$(my_edit).attr('contenteditable', 'false');
	    if ($( my_edit ).attr('contenteditable') == "false" ) {
	        $(editable_elements).css('background','blue' );
	        $(editable_elements).attr('contenteditable', 'false');
	        $(my_edit).attr('contenteditable', 'true');
	        $(my_edit).attr('id', 'editor');
	        



  $(function(){
    function initToolbarBootstrapBindings() {
      var fonts = ['Serif', 'Sans', 'Arial', 'Arial Black', 'Courier', 
            'Courier New', 'Comic Sans MS', 'Helvetica', 'Impact', 'Lucida Grande', 'Lucida Sans', 'Tahoma', 'Times',
            'Times New Roman', 'Verdana', 'Showcard Gothic'],
            fontTarget = $('[title=Schriftart]').siblings('.dropdown-menu');
      $.each(fonts, function (idx, fontName) {
          fontTarget.append($('<li><a data-edit="fontName ' + fontName +'" style="font-family:\''+ fontName +'\'">'+fontName + '</a></li>'));
      });

      var color = ['FFFFFF', '000000', 'FF9966', '6699FF', '99FF66', 'CC0000', '00CC00', '0000CC', '333333', '0066FF', 'FFFF00', 'FF00FF', 'D3D3D3', '808080'],
            fontTarget = $('[title=Schriftfarbe]').siblings('.dropdown-menu');
      $.each(color, function (idx, foreColor) {
          fontTarget.append($('<li><a data-edit="foreColor  #' + foreColor +'" style="color:\#'+ foreColor +'"><i>&#9609;</i></a></li>'));
      });
      
      
      var backcolor = ['FFFFFF', '000000', 'FF9966', '6699FF', '99FF66', 'CC0000', '00CC00', '0000CC', '333333', '0066FF', 'FFFF00', 'FF00FF', 'D3D3D3', '808080'],
            fontTarget = $('[title=Schrifthintergrundfarbe]').siblings('.dropdown-menu');
      $.each(backcolor, function (idx, backColor ) {
          fontTarget.append($('<li><a data-edit="backColor   #' + backColor  +'" style="color:\#'+ backColor  +'"><i>&#9609;</i></a></li>'));
      });

      $('a[title]').tooltip({container:'body'});
    	$('.dropdown-menu input').click(function() {return false;})
		    .change(function () {$(this).parent('.dropdown-menu').siblings('.dropdown-toggle').dropdown('toggle');})
        .keydown('esc', function () {this.value='';$(this).change();});
      $('[data-role=magic-overlay]').each(function () { 
        var overlay = $(this), target = $(overlay.data('target')); 
        overlay.css('opacity', 0).css('position', 'absolute').offset(target.offset()).width(target.outerWidth()).height(target.outerHeight());
      }); 
 
// Drag and Drop Videos löschen      
$('#delete_video').click(function() {
  $('.preview').remove();
});	
 
  
  
  
     	
var dropbox;
//_V_.options.techOrder = ["flash"];
dropbox = document.getElementById("editor");
dropbox.addEventListener("dragenter", dragenter, false);
dropbox.addEventListener("dragover", dragover, false);
dropbox.addEventListener("drop", drop, false);

function dragenter(e) {
  e.stopPropagation();
  e.preventDefault();
}

function dragover(e) {
  e.stopPropagation();
  e.preventDefault();
}

function drop(e) {
  e.stopPropagation();
  e.preventDefault();

  var dt = e.dataTransfer;
  var files = dt.files;

  handleFiles(files);
  
}






function handleFiles(files) {
	
  for (var i = 0; i < files.length; i++) {
    var file = files[i];

    var video = document.createElement("video")
    video.classList.add("obj");
    video.file = file;
    video.setAttribute("controls","controls");
    video.className = "preview";
    //dropbox.appendChild(video);
	dropbox.insertAdjacentElement("afterbegin", video);
	
	
    var reader = new FileReader();
    reader.onload = (function(aVideo) { return function(e) { aVideo.src = e.target.result; test = aVideo.src; }; })(video);
    reader.readAsDataURL(file);
    
    
    
    
  }
}     	
     	
    
 
   
   
      
      
      if ("onwebkitspeechchange"  in document.createElement("input")) {
        var editorOffset = $('#editor').offset();
        $('#voiceBtn').css('position','absolute').offset({top: editorOffset.top, left: editorOffset.left+$('#editor').innerWidth()-35});
      } else {
        $('#voiceBtn').hide();
      }
	};
	function showErrorAlert (reason, detail) {
		var msg='';
		if (reason==='unsupported-file-type') { msg = "Unsupported format " +detail; }
		else {
			console.log("error uploading file", reason, detail);
		}
		$('<div class="alert"> <button type="button" class="close" data-dismiss="alert">&times;</button>'+ 
		 '<strong>File upload error</strong> '+msg+' </div>').prependTo('#alerts');
	};
    initToolbarBootstrapBindings();  
	$('#editor').wysiwyg({ fileUploadError: showErrorAlert} );
    window.prettyPrint && prettyPrint();
  });

	        
	        
	
	
	        
	        $(my_edit).focus();
	        
	        //$(this).css('background', 'rgba(0,255,0,0.25)');
	        
/*	        
		  var colorPalette = ['000000', 'FF9966', '6699FF', '99FF66', 'CC0000', '00CC00', '0000CC', '333333', '0066FF', 'FFFFFF'];
		  var forePalette = $('.fore-palette');
		  var backPalette = $('.back-palette');

		  for (var i = 0; i < colorPalette.length; i++) 			{
    forePalette.append('<a href="#" data-command="forecolor" data-value="' + '#' + colorPalette[i] + '" style="background-color:' + '#' + colorPalette[i] + ';" class="palette-item"></a>');
    backPalette.append('<a href="#" data-command="backcolor" data-value="' + '#' + colorPalette[i] + '" style="background-color:' + '#' + colorPalette[i] + ';" class="palette-item"></a>');
  }

		  $('.toolbar a').click(function(e) 												{
		    var command = $(this).data('command');
		    if (command == 'h1' || command == 'h2' || command == 'p') {
		      document.execCommand('formatBlock', false, command);
		    }
		    if (command == 'forecolor' || command == 'backcolor') {
		      document.execCommand($(this).data('command'), false, $(this).data('value'));
		    }
		    if (command == 'createlink' || command == 'insertimage') {
		      url = prompt('Enter the link here: ', 'http:\/\/');
		      document.execCommand($(this).data('command'), false, url);
		    } else document.execCommand($(this).data('command'), false, null);
		  });
	        
	        
	        
		  $('#editor').keypress(function(e)							{
		      if (e.which == 13)
		      {
		        txt = document.getElementById("editor");
		 		insertAtCursor(txt, '<br>');
		        
		      }
		  });
	        	        
*/
	        
	        $("#save").click(function(event){
				$('#save').css('display','none' );
				$('#edit_page').css('display','block' );
				$(editable_elements).css('background','' );
		        $(my_edit).attr('contenteditable', 'false');
		        $(my_edit).removeAttr('contenteditable');
		        $(this).css('background', '');
		        $(my_edit).attr('id', page_id);
		        //var trog = $(my_edit).html();
				$('.toolbar').addClass('hidden');
				
				
				if(page_id != 'editor')
					$.ajax({
						url : '../ajax/write.php',
						type : 'post',
						data : {
							'data': $(my_edit_elem).html(),
							'page': page_id+'.html'
							//'text2': 'hello World',
						}
					}).done(function(msg){						
						$("#ajax_meldung").html(msg);
					}).fail(function(){
						alert("Fehler");
					});	
			});
	    } 


		
		function insertAtCursor(myField, myValue) {
		   //IE support
		   if (document.selection) {
		     myField.focus();
		     sel = document.selection.createRange();
		     sel.text = myValue;
		   }
		   //MOZILLA/NETSCAPE support
		   else if (myField.selectionStart || myField.selectionStart == '0') {
		     var startPos = myField.selectionStart;
		     var endPos = myField.selectionEnd;
		     myField.value = myField.value.substring(0, startPos)
		                   + myValue 
		                   + myField.value.substring(endPos, myField.value.length);
		   } else {
		     myField.value += myValue;
		   }
		 }		


});









$('#besucher').click(function(){
	$.confirm({    
	    theme: "supervan",
	    animation: "bottom",
		closeAnimation: "top",
	    boxWidth: "30%",
	    useBootstrap: false,
	    title: '',
	    content: 'url:../cache/counter.txt',
	    onContentReady: function () {
	        var self = this;
	        this.setContentPrepend('<div>Ihre Hompage hatte insgesamt</div>');
	        setTimeout(function () {
	            self.setContentAppend('<div>Besucher</div>');
	        }, 0);
	    },
	    columnClass: 'medium',
	    buttons: {
	        OK: {
	            text: 'Schließen',
	            action: function () {
	                
	            }
	        }
	    }
	});
});

$('.log').click(function(){
	page_id = $(this).attr('id');
	$.confirm({    
	    theme: "supervan",
	    animation: "bottom",
		closeAnimation: "top",
	    boxWidth: "50%",
	    useBootstrap: false,
	    title: '',
	    content: 'url:../cache/logs/'+page_id+'.txt',
	    onContentReady: function () {
	        var self = this;
	        this.setContentPrepend('<div>Alle Log Einträge für '+page_id+'</div>');
	        setTimeout(function () {
	            self.setContentAppend('<div></div>');
	        }, 0);
	    },
	    columnClass: 'medium',
	    buttons: {
	        OK: {
	            text: 'Schließen',
	            action: function () {
	                
	            }
	        }
	    }
	});
});

$('.errorlog').click(function(){
	 	window.open('../cache/php_log/php_error.txt', 'PHP ERROR', 'width=800,height=800,location=no,menubar=no');  
});


$('#erstelle_seite').click(function(){
	var page_id;
	
	$.confirm({
	    title: 'Erstellen einer neuen Seite!',
	    content: '' +
	    '<form action="" class="formName">' +
	    '<div class="form-group">' +
	    '<label>Name der zu erstellenden Seite!</label><br><br>' +
	    '<input type="text" placeholder="z.B Jobs" class="name form-control" required />' +
	    '</div>' +
	    '</form>',
	    
	    theme: "supervan",
		animation: "bottom",
		closeAnimation: "top",
		boxWidth: "30%",
		useBootstrap: false,
	    
	    buttons: {
	        formSubmit: {
	            text: 'OK',
	            btnClass: 'btn-blue',
	            action: function () {
	                var page_id = this.$content.find('.name').val();
					$.ajax({
						url : '../ajax/html_page.php',
						type : 'post',
						data : {
							'data': '',
							'page': page_id
							//'text2': 'hello World',
						}
					}).done(function(msg){
						//  Cross-Origin-Request kann nur erfolgreich durchgeführt werden wenn der Server die gleiche Domain hat 
						// oder der Server bei seiner Antwort den Zugriff durch entsprechende HTTP-Header erlaubt
						// Beispiel: Access-Control-Allow-Origin: http://foo.example
						$("#ajax_meldung").html(msg);
						
						
					}).fail(function(){
						alert("Fehler");
					
					});	
	            }
	        },
	        cancel: function () {
	            //close
	        },
	    }
	});	
	

});


  
// navigation Links richtig öffnen lassen
$('li a').click(function(){
    a = $(this).attr('href');  
		
		
    	if ($('#hamburger').is('.open')) {   
	      $('nav').toggle(); 
	      $('#hamburger').toggleClass('open');

	    }
		
    // Schauen ob das erste Zeichen im String ein # ist und wenn true tabcontent öffnen und alle anderen schließen
    // Bei false passiert erstmal nix, das target im Link öffnet im neuen Tap den Link
    if (a[0] == '#'){
        $('.tabcontent').hide();
        $(a).show(); 
    }
    
});






$('.app').click(function(){
	 	window.open($(this).data('app_url'), $(this).data('app_name'), 'width=1200,height=800');

  
});







document.getElementById("loader").style.display = "none";
  document.getElementById("hilfscontainer").style.display = "block";




});    
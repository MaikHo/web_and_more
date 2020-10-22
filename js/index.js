$(document).ready(function(){

$('#close_dialog').click(function(){
	//dialog = document.getElementById('cookie_dialog'),
	//dialog.close();
	$('#cookie_dialog').hide(250);
}); 

/* --------------------------------------------
 Navigation
 --------------------------------------------- */


$('#hamburger').click(function(){
	$(this).toggleClass('open');
	if($('#hamburger').is('.open')){
		$('nav').show();
	}else{
		$('nav').hide();
	}
}); 
 
document.getElementById("loader").style.display = "none";
$("li a").click(function(){
	document.getElementById("loader").style.display = "block";
	document.getElementById("load_html").style.display = "none";
	var id = $(this).attr("id");
	var a = $(this).attr("href");
    
    // für Hamburger menü
    if ($('#hamburger').is('.open')) {
    	$('nav').toggle();
    	$('#hamburger').toggleClass('open');
    	}
    	if (a[0] == "#"){
    		var url;
    		if(id == 'Kontakt' || id == 'News' || id == 'Impressum'){
    			url = "inc_html/"+id+".php";
    		}else{
				url = "inc_html/"+id+".html";
			}
			$.ajax({
				url: url,
				cache: false
			})
			.done(function( html ) {
			
			
			$("html").scrollTop(-5);
			
			setTimeout(showhtml, 800);
			function showhtml(){
				$( ".editable_elements" ).html(html);
				document.getElementById("loader").style.display = "none";
  				document.getElementById("load_html").style.display = "block";
			}
			
			
			});
		}
});

/* --------------------------------------------
 Platform detect
 --------------------------------------------- */
	// Opera 8.0+
    var isOpera = (!!window.opr && !!opr.addons) || !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0;

    // Firefox 1.0+
    var isFirefox = typeof InstallTrigger !== 'undefined';

    // Safari 3.0+ "[object HTMLElementConstructor]" 
    var isSafari = /constructor/i.test(window.HTMLElement) || (function (p) { return p.toString() === "[object SafariRemoteNotification]"; })(!window['safari'] || safari.pushNotification);

    // Internet Explorer 6-11
    var isIE = /*@cc_on!@*/false || !!document.documentMode;

    // Edge 20+
    var isEdge = !isIE && !!window.StyleMedia;

    // Chrome 1+
    var isChrome = !!window.chrome && !!window.chrome.webstore;

    // Blink engine detection
    var isBlink = (isChrome || isOpera) && !!window.CSS;

	var is_screen_width = window.screen.width;
	
	var is_screen_height = window.screen.height;

	function loadCSS(url) {
	    var link = document.createElement("link");
	    link.type = "text/css";
	    link.rel = "stylesheet";
	    link.href = url;
	    document.getElementsByTagName("head")[0].appendChild(link);
	}
		
	function is_touch_device() {
		return 'ontouchstart' in window        // works on most browsers
	      || navigator.maxTouchPoints;       // works on IE10/11 and Surface
	};
	function check_touch_device(){
		
		if(is_touch_device()) {
		//$('html').addClass('touch');
			
			if(is_screen_width < is_screen_height){
				//alert('hochkannt');
			}else{
				//alert('quer');
			}			
			if(is_screen_width < 800){
				loadCSS('css/handy.css');
				loadCSS('css/hamburger.css');
				loadCSS('css/minstyle.css');
				
			}
			if(is_screen_width > 800 && is_screen_width < 1025){
				$('#hamburger').addClass('hidden');
				loadCSS('css/tablet.css');
			}
			if(is_screen_width > 1025){
				$('#hamburger').addClass('hidden');
				loadCSS('css/desktop.css');
			}
		}
		else {
		//$('html').addClass('no-touch');
			$('#hamburger').addClass('hidden');
			loadCSS('css/desktop.css');
		}
	}	
	
	if(isFirefox){
		check_touch_device();
	}else if(isChrome){
		check_touch_device();
	}else if(isSafari){
		check_touch_device();
	}else if(isOpera){
		check_touch_device();
	}else if(isIE){
		check_touch_device();
	}else if(isEdge){
		check_touch_device();
	}else if(isBlink){
		check_touch_device();
	}else{
		check_touch_device();
	}



  
});









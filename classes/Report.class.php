<?php
class Report{
	public static function aktions_meldung($meldung, $title, $red_or_green){
		
		if($red_or_green == 'green'){
						$buttons = '		
					buttons: {
							        weiter: {
							            text: "Weiter und Seite neuladen",
							            btnClass: "btn-green",
							            action: function(){
							            	location.reload();
							            }
							        }							        
							    }
						';
		}else{
			$buttons = '		
					buttons: {
							        weiter1: {
							            text: "Weiter und Seite neuladen",
							            btnClass: "btn-red",
							            action: function(){
							            	location.reload();
							            }
							        },
							        weiter2: {
							            text: "Zurück zum bearbeiten",
							            btnClass: "btn-red",
							            action: function(){
							            }
							        }
							    }
						';
		}
		
			
		
		
		$html_meldung = '<script>
			
				    $.confirm({
				    theme: "supervan",
				    animation: "bottom",
    				closeAnimation: "top",
				    boxWidth: "30%",
				    useBootstrap: false,
				    icon: "fas fa-exclamation-circle",
				    title: "'.$title.'",
				    content: "'.$meldung.'",
				    type: "'.$red_or_green.'",				    
				    typeAnimated: true,
				    '.$buttons.'
				}); 
				
			</script>';
			Log::write_log($meldung, $title);
			
		echo $html_meldung;
	}
}
?>
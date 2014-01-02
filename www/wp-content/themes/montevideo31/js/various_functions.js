jQuery(document).ready(function() {
	
	/*
	 *	HANDLE FOCUS / BLUR ON SIDEBAR'S INPUTS
	 */ 
	var default_newsletter = "Votre email";
	var default_sms = "Tel. Mobile";
	
	// initialize  default values
	jQuery("#sidebar_newsletter").val(default_newsletter);
	jQuery("#sms_sidebar_input_text").val(default_sms);
	
	// handle input / blur for SMS input
	jQuery("#sms_sidebar_input_text").focus( function(){
	
		if( this.value == default_sms  ){
			jQuery("#sms_sidebar_input_text").val("");
		}
	
	});
	
	jQuery("#sms_sidebar_input_text").blur( function(){
		
		if( this.value == ''  ){
			jQuery("#sms_sidebar_input_text").val(default_sms);
		}
		
	});
	
	// handle input / blur for NEWSLETTER input
	jQuery("#sidebar_newsletter").focus( function(){
	
		if( this.value == default_newsletter  ){
			jQuery("#sidebar_newsletter").val("");
		}
	
	});
	
	jQuery("#sidebar_newsletter").blur( function(){
		
		if( this.value == ''  ){
			jQuery("#sidebar_newsletter").val(default_newsletter);
		}
		
	});
	
	
	/*
	 *	VALIDATE SMS'S SIDEBAR INPUTS
	 */ 
	jQuery('#sms_sidebar_submit').click(function(){
		var phone = jQuery('#sms_sidebar_input_text').val();
		var prefix = phone.substr(0,2);
		var formatted_prefix = "33";
		var replace = phone.substr(1,9);
		
		if(/^[0-9]{10}$/.test(phone) && (prefix==="06"||prefix==="07") ){
			jQuery('#sms_sidebar_input').val(formatted_prefix+replace);
		}else{
			alert("Le num\351ro de t\351l\351phone est invalide.");
			jQuery('#sms_sidebar_input').val("");
			return false;
		}
			
	}); 
	
	
	/*
	 *		PROFIL PAGE : AJAX ARCHIVE PETITE ANNONCE
	 */
	 
	jQuery('.profil-archive-petite-annonce').click(function(){
		
		var postId = this.attr("id");
		// post ajax
		jQuery.post(
		   'wp-admin/admin-ajax.php', 
		   {
				'action':'profil_archive_pa',
				'postId' : postId
		   }, 
		   function(response, status){
		      	// test if Ajax feedback is Okay
				if(status==="success"){
					// test if book saving is ok
					if( response ){
						// update saved
						
					//	console.log("archivé");
						
						
					}else{
						// update not saved
						
					//	console.log("pas archivé");
						
					}
				}else{
					// Problème Ajax
					//console.log("pblm ajax");
				} // end of ajax feedback test
		   }// end of function ajax response
		); // end of function juquery post
		
		return false;			
	}); 
	 
		
		
		
		
	//////
	$('#input').submit(function(){
								 
		var phone = jQuery('#bew_user_adu_user_mobile').val();
		if( phone != "" ){
			var prefix = phone.substr(0,2);
			var formatted_prefix = "33";
			var replace = phone.substr(1,9);
						
			if(/^[0-9]{10}$/.test(phone) && (prefix==="06"||prefix==="07") ){
				jQuery('#bew_user_adu_user_mobile').val(formatted_prefix+replace);
			}else{
				alert("Le num\351ro de t\351l\351phone est invalide.");
				jQuery('#bew_user_adu_user_mobile').val("");
				return false;
			}
		}
					
	});
			
	
	
	/*
		Page profil : désinscription
	*/
	jQuery('#profile_page_button').click(function(){
	
		var answer = confirm("\312tes vous certain d'appliquer les choix coch\351s ci-dessous?")
		if (!answer){
			return false;
		}	
		
	});
	
	
  // ---- popover calendars
  
  jQuery("#cal_subscribe").click(function(){
   jQuery("#popover").fadeToggle();
  });
  // 
  // 
  var mouse_is_inside = false;
  
  
  jQuery('#popover').hover(function(){ 
         mouse_is_inside=true; 
     }, function(){ 
         $(this).fadeOut();
         mouse_is_inside=false; 
     });
    
	
});

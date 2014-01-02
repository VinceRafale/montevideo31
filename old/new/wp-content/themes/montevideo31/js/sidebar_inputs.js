jQuery(document).ready(function() {
	
	/*
	 *	HANDLE FOCUS / BLUR ON SIDEBAR'S INPUTS
	 */ 
	
	
	var default_newsletter = "Votre email";
	var default_sms = "Tel. Mobile";
	
	// initialize défault values
	jQuery("#sidebar_newsletter").val(default_newsletter);
	jQuery("#sidebar_sms_input").val(default_sms);
	
	// handle input / blur for SMS input
	jQuery("#sidebar_sms_input").focus( function(){
	
		if( this.value == default_sms  ){
			jQuery("#sidebar_sms_input").val("");
		}
	
	});
	
	jQuery("#sidebar_sms_input").blur( function(){
		
		if( this.value == ''  ){
			jQuery("#sidebar_sms_input").val(default_sms);
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
	
});

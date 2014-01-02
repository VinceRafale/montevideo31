// JavaScript Document


jQuery(document).ready(function(){
								
		
		jQuery(".carousel .tabs").tabs(".carousel .slide", {

			// enable "cross-fading" effect
			effect: 'fade',
			fadeOutSpeed: "fast",
		
			// start from the beginning after the last tab
			rotate: true
		
		// use the slideshow plugin. It accepts its own configuration
		}).slideshow({ autoplay:true, clickable : false}); 
		
		
		
		jQuery(".fp .tabs").tabs(".fp .slide", {

			// enable "cross-fading" effect
			effect: 'fade',
			fadeOutSpeed: "fast",
		
			// start from the beginning after the last tab
			rotate: true
		
		// use the slideshow plugin. It accepts its own configuration
		}).slideshow({ autoplay:true }); 
		
		
		var fp = jQuery(".fp .tabs").data("tabs");

		jQuery('#fp_next').click(function(){  fp.next(); });
		jQuery('#fp_prev').click(function(){  fp.prev(); });	
								
								
});
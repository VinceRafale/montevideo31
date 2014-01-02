<?php


add_filter('the_content', 'bew_calendar_in_content', 3);


function bew_calendar_in_content($content){ 

	// slugs=catslug,catslug,catslug&number=0&display_title=true&display_tb=true&display_content=true&custom_class=class
				
	$regex = '#{calendar}(.*?){/calendar}#s';
				 
	$content = preg_replace_callback($regex, 'bew_calendar_in_content_cb', $content);

	return $content; 
	 
}


function bew_calendar_in_content_cb($match){

	
	$match[1] = htmlspecialchars_decode($match[1]);
	
	if(current_user_can('edit_posts')) $match[1].= '&disable_transient=true';
	
	//var_dump($match);
	
	$dst_sa = new bewCalendar($match[1]); 
	
	return "<div class='bew-calendar'>$dst_sa</div>";
	
	
	
	

}
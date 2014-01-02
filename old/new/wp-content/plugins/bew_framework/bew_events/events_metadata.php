<?php





	if(isset($_REQUEST['bew_book_event_places'])) add_action('init', 'bew_events_check_meta');	

	function bew_events_check_meta(){
	
	
		if($_REQUEST['bew_book_event_places'] != '0' && (empty($_REQUEST['bew_book_event_places']) || $_REQUEST['bew_book_event_places'] != strval($_REQUEST['bew_book_event_places']))) return bew_admin_message('Please specify the number of places availables for this event. You must enter just a number.', 'error');
		
		add_action('edit_post', 'bew_events_save_meta');
	
	}
		
	function bew_events_save_meta($id){

		 update_post_meta($id, '_bew_event_places',strval($_REQUEST['bew_book_event_places']));
	
	}
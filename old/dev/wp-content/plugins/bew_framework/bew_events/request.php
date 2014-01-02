<?php


	add_filter('posts_request', bew_modify_events_request, 99, 2);



//modification de la requete interceptée si besoin

function bew_modify_events_request($request, $current_query){

	

	global $bew_modify_events_request_active;
	
	if($current_query->query->post_type == 'events' && ! $bew_modify_events_request_active){
	
			$bew_modify_events_request_active = true;
	
			$q = $current_query->query;
			
			$q['post_status'] = 'any';

			$r = new WP_Query($q);
		
			$bew_modify_events_request_active = false;
			
			$request = $r->request;
	}
	
	var_dump($request);
	
	return $request;
	
}
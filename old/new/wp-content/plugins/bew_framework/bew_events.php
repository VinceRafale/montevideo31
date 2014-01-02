<?php

/*
Plugin Name: BEW Culture framework - Events management
Plugin URI: BEW Culture website
Description: Events management with user subscriptions

Author: Adrien Menoret
Author URI: mailto: adrien.menoret@gmail.com 

Version: 0.2
*/


require_once(dirname(realpath(__FILE__)).'/bew_events/custom_post_taxonomy.php');
require_once(dirname(realpath(__FILE__)).'/bew_events/bookings_metabox.php');
require_once(dirname(realpath(__FILE__)).'/bew_events/booking_helper.php');
require_once(dirname(realpath(__FILE__)).'/bew_events/events_metadata.php');
require_once(dirname(realpath(__FILE__)).'/bew_events/template_tags.php');



global $bew_bookings_db_version;
		$bew_bookings_db_version = "1.0";
		
	
global $bew_bookings_table;
 $bew_bookings_table = "bew_bookings";
		
function bew_events_install () {
		   global $wpdb;
		   global $bew_bookings_db_version;
		   global $bew_bookings_table;

		
		   $table_name = $wpdb->prefix .  $bew_bookings_table;
		   if($wpdb->get_var("show tables like '$table_name'") != $table_name) {
			  
			  $sql = "CREATE TABLE " . $table_name . " (
			  id mediumint(9) NOT NULL AUTO_INCREMENT,
			  time bigint(11) DEFAULT '0' NOT NULL,
			  user_id mediumint(9) NOT NULL,
			  event_id mediumint(9) NOT NULL,
			  metadata text NOT NULL,
			  deleted tinyint(1) NOT NULL DEFAULT '0',
			  UNIQUE KEY id (id)
			);";
		
			  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			  dbDelta($sql);
			 
			  add_option("bew_bookings_db_version", $bew_bookings_db_version);
		
		   }
}

register_activation_hook(__FILE__, 'bew_events_install');

 

function bew_events_alter_translation($translation, $text, $domain) {
    
	
	global $post;
		
	if (is_object($post) && $post->post_type == 'events') {
	
		$replaces = array(
		
		'Scheduled for: <b>%1$s</b>' => 'Event Date: <b>%1$s</b>',
		'Published on: <b>%1$s</b>' => 'Event Date: <b>%1$s</b>',
		'Publish <b>immediately</b>' => 'Event Date: <b>%1$s</b>',
		'Publish <b>immediately</b>' => 'Event Date: <b>%1$s</b>',
		
		
		);
 
		$translations = &get_translations_for_domain( $domain);
		
		if ( isset($replaces[$text]) ) return $translations->translate($replaces[$text]);
			
		
	}
 
	return $translation;
	
}
 
add_filter('gettext', 'bew_events_alter_translation', 10, 4);


function bew_events_show_scheduled_posts($posts) {
   global $wp_query, $wpdb;
   if(is_single() && $wp_query->post_count == 0) {
      $posts = $wpdb->get_results($wp_query->request);
   }
   return $posts;
}
 
add_filter('the_posts', 'bew_events_show_scheduled_posts');
add_filter('posts_request', 'bew_modify_events_request', 99, 2);

//modification de la requete interceptée si besoin
function bew_modify_events_request($request, $current_query){
	global $bew_modify_events_request_active;
	if($bew_modify_events_request_active) 
		return $request;
	if((isset($current_query->query['post_type']) && $current_query->query['post_type'] == 'events') || (isset($current_query->query['taxonomy']) && $current_query->query['taxonomy'] == 'event_category')){
		$bew_modify_events_request_active = true;
		$q = $current_query->query;
		$q['post_status'] = 'future';
		$q['orderby'] = 'date';
		$q['order'] = 'ASC';
		if(!isset($current_query->query['disable_events_time_filtering'])){
			if(isset($_GET['event_month'])) $q['monthnum'] = strval($_GET['event_month']);
			if(isset($_GET['event_year'])) $q['year'] = strval($_GET['event_year']);
			if(isset($_GET['event_day'])) $q['day'] = strval($_GET['event_day']);
			if(isset($_GET['past_events'])) $q['post_status'] = 'any';
		}
		$r = new WP_Query($q);
		$bew_modify_events_request_active = false;
		$request = $r->request;
	}
	return $request;
	
}







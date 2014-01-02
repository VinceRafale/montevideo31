<?php


function bew_events_posttype() {

	// Add new taxonomy, make it hierarchical (like categories)
	  $labels = array(
		'name' => _x( 'Event Category', 'taxonomy general name' ),
		'singular_name' => _x( 'Event Categories', 'taxonomy singular name' ),
		'search_items' =>  __( 'Search Event Categories' ),
		'all_items' => __( 'All Event Categories' ),
		'parent_item' => __( 'Parent Event Category' ),
		'parent_item_colon' => __( 'Parent Event Category:' ),
		'edit_item' => __( 'Edit Event Category' ), 
		'update_item' => __( 'Update Event Category' ),
		'add_new_item' => __( 'Add New Event Category' ),
		'new_item_name' => __( 'New Event Category Name' ),
	  ); 	
	
	  register_taxonomy('event_category',array('events'), array(
		'hierarchical' => true,
		'labels' => $labels,
		'show_ui' => true,
		'rewrite' => array( 'slug' => 'events' ),
		'show_in_nav_menus' => true
	  ));



	register_post_type( 'events',
		array(
			'labels' => array(
				'name' => __( 'Events' ),
				'singular_name' => __( 'Event' ),
				'add_new' => __( 'Add New Event' ),
				'add_new_item' => __( 'Add New Event' ),
				'edit_item' => __( 'Edit Event' ),
				'new_item' => __( 'Add New Event' ),
				'view_item' => __( 'View Event' ),
				'search_items' => __( 'Search Event' ),
				'not_found' => __( 'No events found' ),
				'not_found_in_trash' => __( 'No events found in trash' )
			),
			'public' => true,
			'supports' => array( 'title', 'editor', 'thumbnail', 'comments', 'custom-fields' ),
			'capability_type' => 'post',
			'taxonomies' => array( 'event_category'),
			'rewrite' => array("slug" => "event"), // Permalinks format
			'menu_icon' => get_bloginfo('url').'/wp-content/plugins/bew_framework/bew_events/date.gif',  // Icon Path
			'menu_position' => '5',
			'register_meta_box_cb' => 'bew_events_metaboxes'
		)
	);
}

add_action( 'init', 'bew_events_posttype' );
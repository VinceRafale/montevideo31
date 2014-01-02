<?php


// Enregistrement custom_post et custom_taxonomy des newsletters
 



add_action( 'init', 'bew_nw_post_type_newsletter', 99 );  

function bew_nw_post_type_newsletter(){

	$labels = array(
    	'name' => _x('Newsletters', 'Sidebar Posts'),
    	'singular_name' => _x('Newsletter', 'Sidebar Posts'),
    	'add_new' => _x('Add Newsletter', 'Add new custom post'),
    	'add_new_item' => __('Add Newsletter', 'dstpa'),
    	'edit_item' => __('Edit Newsletter', 'dstpa'),
    	'new_item' => __('New Newsletter', 'dstpa'),
    	'view_item' => __('View Newsletter', 'dstpa'),
    	'search_items' => __('Search Newsletter', 'dstpa'),
    	'not_found' =>  __('No Newsletter yet', 'dstpa'),
    	'not_found_in_trash' => __('No Newsletter found in trash'), 
    	'parent_item_colon' => ''
  	);
  
  	$args = array(
    	'labels' => $labels,
    	'public' => true,
    	'publicly_queryable' => true,
    	'show_ui' => true, 
    	'query_var' => false,
    	'rewrite' => array('slug' => 'newsletter','with_front' => true),
    	'capability_type' => 'post',
    	'hierarchical' => false,
    	'menu_position' => 9,
    	'supports' => array('title', 'editor', 'custom-fields','revisions'),
			//REMOVED : 'page-attributes', 'excerpt', 'thumbnail', 'comments', 'author'
  	); 
  
  	register_post_type('bew_nw', $args);

}



//Gestion des catégories des newsletters

add_action( 'init', 'bew_nw_taxonomy', 99 );

function bew_nw_taxonomy() {
  
  $labels = array(
    'name' => _x( 'Newsletters Categories', 'taxonomy general name' ),
    'singular_name' => _x( 'Newsletter Category', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Newsletter Category' ),
    'all_items' => __( 'All Newsletter Categories' ),
    'parent_item' => __( 'Parent Newsletter Category' ),
    'parent_item_colon' => __( 'Parent Newsletter Category :' ),
    'edit_item' => __( 'Edit Newsletter Category' ), 
    'update_item' => __( 'Update Newsletter Category' ),
    'add_new_item' => __( 'Add Newsletter Category' ),
    'new_item_name' => __( 'Newsletter Category Name' ),
  ); 	

  register_taxonomy(
  	'bew_nw_taxonomy',
	array('newsletter'), 
		array(
		'hierarchical' => true,
		'labels' => $labels,
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'c-newsletter' ),
  	));
}

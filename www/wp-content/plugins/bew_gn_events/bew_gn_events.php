<?php
/*
Plugin Name:Bew GN Evenements plugin
Plugin URI: http://bew
Description: Events handler plugin for montevideo
Version: 1.0
Author: Bewculture
Author URI: http://bew
*/



add_action( 'init', 'gn_events_posttype', 99 );

function gn_events_posttype() {
		
		
		  $labels = array(
			'name' => _x( 'Cat&#233;gorie d\'&#233;venement', 'taxonomy general name' ),
			'singular_name' => _x( 'Cat&#233;gories d\'&#233;venement', 'taxonomy singular name' ),
			'search_items' =>  __( 'Chercher dans les cat&#233;gorie d\'&#233;venement' ),
			'all_items' => __( 'Toutes les cat&#233;gories d\'&#233;venement' ),
			'parent_item' => __( 'Cat&#233;gorie parente d\'&#233;venement' ),
			'parent_item_colon' => __( 'Cat&#233;gorie d\'&#233;venement :' ),
			'edit_item' => __( 'Edtier la cat&#233;gorie d\'&#233;venement' ), 
			'update_item' => __( 'Mettre ˆ jour la cat&#233;gorie d\'&#233;venement' ),
			'add_new_item' => __( 'Ajouter une cat&#233;gorie d\'&#233;venement' ),
			'new_item_name' => __( 'Nouvelle cat&#233;gorie d\'&#233;venement' ),
		  ); 	

		  register_taxonomy('categorie_evenement',array('petite_annonce'), array(
			'hierarchical' => false,
			'labels' => $labels,
			'show_ui' => true,
			'show_in_nav_menus' => true
		  ));
		
		
		
	
		$labels = array(
	    	'name' => 'Nos Evenements',
	    	'singular_name' => 'Un évenement',
	    	'add_new' => 'Ajouter une petite annonce',
	    	'add_new_item' => 'Ajouter une petite annonce',
	    	'edit_item' => 'Editer une petite annonce',
	    	'new_item' => 'Ajouter une petite annonce',
	    	'view_item' => 'Visualier une petite annonce',
	    	'search_items' => 'Chercher une petite annonce',
	    	'not_found' => 'Aucune petite annonce trouv&eacute;e',
	    	'not_found_in_trash' => 'Aucune annonce trouv&eacute;e dans la corbeille', 
	    	'parent_item_colon' => ''
	  	);

	  	$args = array(
	    	'labels' => $labels,
	    	'public' => true,
	    	'publicly_queryable' => true,
	    	'show_ui' => true, 
	    	'query_var' => false,
	    	'rewrite' => array('slug' => 'petite_annonce','with_front' => true),
	    	'capability_type' => 'post',
	    	'hierarchical' => false,
	    	'menu_position' => 6,
	    	'supports' => array('title', 'editor', 'custom-fields','revisions','page-attributes', 'excerpt', 'thumbnail', 'comments', 'author')
	  	); 

	  	register_post_type('petite_annonce', $args);

	
	
	
	
	
	
	
	
	
	
	
}


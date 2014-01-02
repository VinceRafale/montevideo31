<?php
/*
Plugin Name: Bew - GN - Petites annonces
Plugin URI: BEW Culture website
Description: Add custom post type petites annonces
Author: Gabriel Nau
Author URI: mailto: gabriel.nau@gmail.com
Version: 0.1
*/

// remove admin menu for users capabilities < editor



add_action( 'init', 'gn_post_type_petite_annonce', 99 );

function gn_post_type_petite_annonce(){

	$labels = array(
    	'name' => 'Petites annonces',
    	'singular_name' => 'Petite annonce',
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

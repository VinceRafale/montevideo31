<?php
/*
Plugin Name: Bew - GN - Faire parts
Plugin URI: BEW Culture website
Description: Add custom post type faire parts
Author: Gabriel Nau
Author URI: mailto: gabriel.nau@gmail.com
Version: 0.1
*/

add_action( 'init', 'gn_post_type_faire_part', 99 );

function gn_post_type_faire_part(){

	$labels = array(
    	'name' => 'Faire-part',
    	'singular_name' => 'Faire-part',
    	'add_new' => 'Ajouter un Faire-Part',
    	'add_new_item' => 'Ajouter un Faire-Part',
    	'edit_item' => 'Editer un Faire-Part',
    	'new_item' => 'Ajouter un Faire-Part',
    	'view_item' => 'Visualiser un Faire-Part',
    	'search_items' => 'Chercher un Faire-Part',
    	'not_found' => 'Aucun Faire-Part trouv&eacute;',
    	'not_found_in_trash' => 'Aucun Faire-Part trouv&eacute; dans la corbeille', 
    	'parent_item_colon' => ''
  	);
  
  	$args = array(
    	'labels' => $labels,
    	'public' => true,
    	'publicly_queryable' => true,
    	'show_ui' => true, 
    	'query_var' => false,
    	'rewrite' => array('slug' => 'faire-part','with_front' => true),
    	'capability_type' => 'post',
    	'hierarchical' => false,
    	'menu_position' => 6,
    	'supports' => array('title', 'editor', 'custom-fields','revisions','page-attributes', 'excerpt', 'thumbnail', 'comments', 'author')
  	); 
  
  	register_post_type('faire-part', $args);

}

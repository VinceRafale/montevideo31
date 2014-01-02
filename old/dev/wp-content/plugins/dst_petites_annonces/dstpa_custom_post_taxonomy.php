<?php


// Enregistrement custom_post et custom_taxonomy des Petites Annonces


//Gestion des posts des Petites Annonces

add_action( 'init', 'post_type_petite_annonce', 99 );

function post_type_petite_annonce(){

	$labels = array(
    	'name' => _x('Petites annonces', 'Sidebar Posts'),
    	'singular_name' => _x('Petite annonce', 'Sidebar Posts'),
    	'add_new' => _x('Ajouter une petite annonce', 'Add new custom post'),
    	'add_new_item' => __('Add new personnal columns', 'dstpa'),
    	'edit_item' => __('Edit personnal columns', 'dstpa'),
    	'new_item' => __('New personnal columns', 'dstpa'),
    	'view_item' => __('View personnal columns', 'dstpa'),
    	'search_items' => __('Search personnal columns', 'dstpa'),
    	'not_found' =>  __('No  personnal columns found', 'dstpa'),
    	'not_found_in_trash' => __('Aucune annonce trouv&eacute;e dans la corbeille'), 
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


function rds_register_petite_annonce() { 

  register_setting( 'petite_annonce', 'f-e_year' );
  register_setting( 'petite_annonce', 'f-e_url' );

}



//Gestion des catégories des Petites Annonces

add_action( 'init', 'dst_cat_petite_annonce', 99 );

function dst_cat_petite_annonce() {
  
  $labels = array(
    'name' => _x( 'Cat&eacute;gories petite annonce', 'taxonomy general name' ),
    'singular_name' => _x( 'Cat&eacute;gories petite annonce', 'taxonomy singular name' ),
    'search_items' =>  __( 'Rechercher cat&eacute;gories petite annonce' ),
    'all_items' => __( 'Toutes cat&eacute;gories petite annonce' ),
    'parent_item' => __( 'Parent cat&eacute;gories petite annonce' ),
    'parent_item_colon' => __( 'Parent cat&eacute;gories petite annonce :' ),
    'edit_item' => __( 'Editer cat&eacute;gories petite annonce' ), 
    'update_item' => __( 'Modifier cat&eacute;gories petite annonce' ),
    'add_new_item' => __( 'Ajouter Nouvelle Cat&eacute;gories petite annonce' ),
    'new_item_name' => __( 'Nouveau Genre Cat&eacute;gories petite annonce' ),
  ); 	

  register_taxonomy(
  	'cat_petite_annonce',
	array('petite_annonce'), 
		array(
		'hierarchical' => true,
		'labels' => $labels,
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'cat_petite_annonce' ),
  	));
}

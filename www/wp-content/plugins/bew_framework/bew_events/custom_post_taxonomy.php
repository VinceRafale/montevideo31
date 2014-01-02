<?php


function bew_events_posttype() {

	// Add new taxonomy, make it hierarchical (like categories)
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
	
	  register_taxonomy('categorie_evenement',array('evenement'), array(
		'hierarchical' => true,
		'labels' => $labels,
		'show_ui' => true,
		//'rewrite' => array( 'slug' => 'evenement' ),
		'show_in_nav_menus' => true
	  ));



	register_post_type( 'evenement',
		array(
			'labels' => array(
				'name' => 'Evenements',
				'singular_name' => 'Evenement',
				'add_new' => 'Ajouter un nouvel &#233;venement',
				'add_new_item' => 'Ajouter un nouvel &#233;venement',
				'edit_item' => 'Editer un &#233;venement',
				'new_item' => 'Ajouter un nouvel &#233;venement' ,
				'view_item' => 'Visualier un &#233;venement',
				'search_items' => 'Chercher dans les &#233;venements',
				'not_found' => 'Aucun &#233;venement trouv&#233;',
				'not_found_in_trash' => 'Aucun &#233;venement trouv&#233; dans la corbeille.'
			),
			'public' => true,
			'supports' => array( 'title', 'editor', 'thumbnail', 'comments', 'custom-fields' ),
			'capability_type' => 'activate_plugins',
			'taxonomies' => array( 'categorie_evenement'),
			'menu_icon' => get_bloginfo('url').'/wp-content/plugins/bew_framework/bew_events/date.gif',  // Icon Path
			'menu_position' => '2',
			'register_meta_box_cb' => 'bew_events_metaboxes'
		)
	);
}

add_action( 'init', 'bew_events_posttype' );
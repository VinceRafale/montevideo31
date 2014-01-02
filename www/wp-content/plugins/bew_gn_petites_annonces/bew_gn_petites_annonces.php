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
    	'menu_icon' => $icon = plugin_dir_url(__FILE__)."icon_annonce.png",
    	'supports' => array('title', 'editor', 'custom-fields', 'author'),
    	'register_meta_box_cb' => 'pa_metaboxes'
  	); 
  
  	register_post_type('petite_annonce', $args);

}

function pa_metaboxes(){
	
    add_meta_box('pa_price', 'Prix', 'pa_metabox_html', 'petite_annonce', 'normal', 'default');
	add_meta_box('pa_contact', 'Contact', 'pa_contact_metabox_html', 'petite_annonce', 'normal', 'default');
	remove_meta_box( 'postcustom', 'petite_annonce', 'normal' );
	remove_meta_box( 'authordiv', 'petite_annonce', 'normal' );
	

}

function pa_contact_metabox_html(){
	global $current_user;
	get_currentuserinfo();	
	echo "L'email de contact affich&#233; dans la petite annonce sera : " . $current_user->user_email;
}

function pa_metabox_html(){
	
	global $post;
	$pa_price = get_post_meta( $post->ID, 'annonce_prix', true); 
	wp_nonce_field( 'my_pa_meta_box_nonce', 'meta_box_nonce' );
	?>
	Prix de l'annonce :
	<input type="text" value="<?= $pa_price ?>" name="pa_admin_price"> &#128;
	<?php
}

function pa_save_price_meta($post_id) {
 
    // Bail if we're doing an auto save  
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return; 
 
    // if our nonce isn't there, or we can't verify it, bail 
    if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'my_pa_meta_box_nonce' ) ) return; 
 
    // if our current user can't edit this post, bail  
    if( !current_user_can( 'edit_post' ) ) return;  
  
    // Make sure your data is set before trying to save it  
    if( isset( $_POST['pa_admin_price'] ) ){
    	
    	$my_pa_price = $_POST['pa_admin_price'];
    	
    	if( get_post_meta($post_id, 'annonce_prix' , true)) {
	    	update_post_meta($post_id, 'annonce_prix', $my_pa_price );
	    } else { // If the custom field doesn't have a value
	    	add_post_meta($post_id, 'annonce_prix', $my_pa_price );
	    }
    }  
    
    
}
 
add_action('save_post', 'pa_save_price_meta', 1, 2); // save the custom fields



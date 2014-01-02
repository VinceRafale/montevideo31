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
    	'menu_icon' => $icon = plugin_dir_url(__FILE__)."icon_fp.png",
    	'supports' => array('title', 'editor', 'custom-fields'),
    	'register_meta_box_cb' => 'fairepart_metaboxes'
  	); 
  
  	register_post_type('faire-part', $args);

}

function my_post_types($types) {
	$types[] = 'faire-part';
	return $types;
}

add_filter('s2_post_types', 'my_post_types');

function fairepart_metaboxes(){
	
    add_meta_box('fairepart_categorie', 'Cat&#233;gorie du Faire part', 'fairepart_metabox_html', 'faire-part', 'normal', 'default');
	
	remove_meta_box( 'postcustom', 'faire-part', 'normal' );
	

}

function fairepart_metabox_html(){
	
	global $post;
	$fp_cat = get_post_meta( $post->ID, 'fairepart_cat', true); 
	if( empty($fp_cat) ){
		$fp_cat = "Autre";
	}
	
	wp_nonce_field( 'my_fairepart_meta_box_nonce', 'meta_box_nonce' );
	
	?>
	S&#233;lectionnez la cat&#233;gorie du Faire-part que vous publiez : 
	
	<select name="fairepart_category">
		<option value="Naissance" <?php if($fp_cat == "Naissance"){ echo "selected"; } ?>  >Naissance</option>
		<option value="D&#233;c&#232;s" <?php if($fp_cat == "Décès"){ echo "selected"; } ?> >D&#233;c&#232;s</option>
		<option value="Mariage" <?php if($fp_cat == "Mariage"){ echo "selected"; } ?>>Mariage</option>
		<option value="Bar Mitzvah" <?php if($fp_cat == "Bar Mitzvah"){ echo "selected"; } ?>>Bar Mitzvah</option>
		<option value="Autre" <?php if($fp_cat == "Autre"){ echo "selected"; } ?> >Autre</option>
	</select>

	<?php
}

// Save the Metabox Data
 
function fairepart_save_meta($post_id) {
 
    // Bail if we're doing an auto save  
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return; 
 
    // if our nonce isn't there, or we can't verify it, bail 
    if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'my_fairepart_meta_box_nonce' ) ) return; 
 
    // if our current user can't edit this post, bail  
    if( !current_user_can( 'edit_post' ) ) return;  
  
    // now we can actually save the data  
    $allowed = array(  
        'a' => array( // on allow a tags  
            'href' => array() // and those anchors can only have href attribute  
        )  
    );  
  
    // Make sure your data is set before trying to save it  
    if( isset( $_POST['fairepart_category'] ) ){
    	
    	$my_fp_cat = $_POST['fairepart_category'];
    	
    	if(  get_post_meta($post_id, 'fairepart_cat' , true)) {
	    	update_post_meta($post_id, 'fairepart_cat', $my_fp_cat );
	    } else { // If the custom field doesn't have a value
	    	add_post_meta($post_id, 'fairepart_cat', $my_fp_cat );
	    }
    
    
    
    }  
    
    
}
 
add_action('save_post', 'fairepart_save_meta', 1, 2); // save the custom fields



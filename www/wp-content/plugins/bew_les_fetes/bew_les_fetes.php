<?php
/*
Plugin Name: Bew - GN - Gestion des fêtes
Plugin URI: BEW Culture website
Description: Add custom post type les-fetes
Author: Gabriel Nau
Author URI: mailto: gabriel.nau@gmail.com
Version: 0.1
*/

add_action( 'init', 'gn_post_type_eve_fete', 99 );

function gn_post_type_eve_fete(){

	$labels = array(
    	'name' => 'Fêtes',
    	'singular_name' => 'Fête',
    	'add_new' => 'Ajouter une fête',
    	'add_new_item' => 'Ajouter une fête',
    	'edit_item' => 'Editer une fête',
    	'new_item' => 'Ajouter une fête',
    	'view_item' => 'Visualiser une fête',
    	'search_items' => 'Chercher une fête',
    	'not_found' => 'Aucune fête trouv&eacute;e',
    	'not_found_in_trash' => 'Aucune fête trouv&eacute;e dans la corbeille', 
    	'parent_item_colon' => ''
  	);
  
  	$args = array(
    	'labels' => $labels,
    	'public' => true,
    	'publicly_queryable' => true,
    	'show_ui' => true, 
    	'query_var' => false,
    	'rewrite' => array('slug' => 'les-fetes','with_front' => true),
    	'capability_type' => 'post',
    	'hierarchical' => false,
    	'menu_position' => 6,
    	'supports' => array('title', 'editor','revisions', 'excerpt', 'thumbnail', 'comments')
  	); 
  
  	register_post_type('les-fetes', $args);

}

register_activation_hook( __FILE__, 'gn_post_type_eve_fetes_Activate' );

function gn_post_type_eve_fetes_Activate(){
    
    flush_rewrite_rules(false);

}

/*
    This code add the custom meta boxes "Nombre de places " and "Participants"
    in the Create/Edit post section, only for the custom post types cours, fêtes, ..

*/

/*
add_action( 'add_meta_boxes', 'add_meta_box_cours' );
add_action( 'save_post', 'meta_box_cours_save' );
*/
/* Adds a box to the main column on the Post and Page edit screens */
/*
function add_meta_box_cours() {
    add_meta_box( 
        'myplugin_sectionid',
        'Gestion des participants',
        'meta_box_cours',
        'les-cours' 
    );

}


function meta_box_cours( $post ) {

    // retrieve previous value of "nombre de places" if it exists
    $nb_places = get_post_meta($post->ID, 'gn_nombre_places', true);
    // retrive participants, if there is any
    $participants = get_post_meta($post->ID, 'gn_participants', true);
    // retrive "produit payant" checkbox if there is any
    $checked = get_post_meta($post->ID, 'gn_payant', true);
    if( $checked == 1){
        $checked = "checked";
    }else{
        $checked = "";
    }
    
    // Display whether the event is past or not
    if( $post->post_status == "publish" ){
        ?><h3>Évènement passé</h3><?php
    }
    // HTML FORM
    ?>
    <h4>Nombre de places :</h4>
    <label for="gn_cours_nombre_places">
        Rentrer le nombre de places disponibles pour cet évènement :
    </label>
    <input type="text" id="gn_events_places_field" name="gn_cours_nombre_places" value="<?= $nb_places ?>" >
    (si aucune réservation n'est possible, rentrer la valeur 0 )
    <h4>Type de produit :</h4>
    <label for="gn_cours_payant">
        Cocher cette case si le produit est payant :
    </label>
    <input type="checkbox" name="gn_cours_payant" <?= $checked ?> >
    
    <h4>Participants :</h4>
    <?php
    if( empty($participants) ){
        echo "Il n'y a aucun participant inscrit à cet évènement";
    }else{
        ?>
        <table id="gn_table_booked_users">
            <tr>
                <th>Pseudonyme</th>
                <th>Prénom / Nom</th>
                <th>Email</th>
                <th>Nombre de places</th>
            </tr>
            <?php
            foreach( $participants as $user_id => $tickets ){
                $user_info = get_userdata( $user_id );
                if( $user_info ){
                ?>
                <tr>
                    <td><?= $user_info->user_login ?></td>
                    <td><?php echo $user_info->first_name ." ". $user_info->last_name ?></td>
                    <td><?= $user_info->user_email ?></td>
                    <td><?= $tickets ?></td>
                </tr>
                <?php
                }
            }
            ?>
        </table>
        <?php
    }
    ?>
    <style type="text/css">
        
        #gn_table_booked_users{
            border:gray 1px solid;
            border-collapse:collapse;
            text-align:left;
            padding:5px;
        }

        #gn_table_booked_users tr, #gn_table_booked_users td{
            border:gray 1px solid;
            padding:5px;
        }   

        #gn_table_booked_users th{
            border:gray 1px solid;
            padding:5px;
            color:white;
            background-color: #0086CB;
            font-weight: normal;
            font-size: 13px;
        }
    </style>
    <?php
}


function meta_box_cours_save( $post_id ) {
    // Save the data
    if( !empty($_POST['gn_cours_nombre_places'])){
        
        // Nombre de places
        update_post_meta($post_id, "gn_nombre_places", $_POST['gn_cours_nombre_places'] );
        
        // Nombre de places restantes
        $oldValue = get_post_meta($post_id, 'gn_nombre_places_restant', true);
        $newValue = (int)$_POST['gn_cours_nombre_places'] - (int)$oldValue;
        update_post_meta($post_id, "gn_nombre_places_restant",  $newValue);
        
        // Cours payant ?
        if( $_POST['gn_cours_payant'] == "on" ){
            update_post_meta($post_id, "gn_payant", 1 );
        }else{
            update_post_meta($post_id, "gn_payant", 0 );
        }
         
    }
}

*/

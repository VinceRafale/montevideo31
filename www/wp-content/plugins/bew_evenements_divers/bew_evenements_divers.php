<?php
/*
Plugin Name: Bew - GN - Gestion des événements last
Plugin URI: BEW Culture website
Description: Add custom post type les-evenements
Author: Gabriel Nau
Author URI: mailto: gabriel.nau@gmail.com
Version: 0.1
*/

add_action( 'init', 'gn_post_type_eve_divers', 99 );

function gn_post_type_eve_divers(){

	$labels = array(
    	'name' => 'Évènements',
    	'singular_name' => 'Évènement',
    	'add_new' => 'Ajouter un &eacute;vènement',
    	'add_new_item' => 'Ajouter un &eacute;vènement',
    	'edit_item' => 'Editer un &eacute;vènement',
    	'new_item' => 'Ajouter un &eacute;vènement',
    	'view_item' => 'Visualiser un &eacute;vènement',
    	'search_items' => 'Chercher un &eacute;vènement',
    	'not_found' => 'Aucun &eacute;vènement trouv&eacute;',
    	'not_found_in_trash' => 'Aucun évènement trouv&eacute; dans la corbeille', 
    	'parent_item_colon' => ''
  	);
  
  	$args = array(
    	'labels' => $labels,
    	'public' => true,
    	'publicly_queryable' => true,
    	'show_ui' => true, 
    	'query_var' => false,
    	'rewrite' => array('slug' => 'les-evenements','with_front' => true),
    	'capability_type' => 'post',
    	'hierarchical' => false,
    	'menu_position' => 6,
    	'supports' => array('title', 'editor','revisions', 'excerpt', 'thumbnail', 'comments')
  	); 
  
  	register_post_type('les-evenements', $args);

}

register_activation_hook( __FILE__, 'gn_post_type_eve_divers_Activate' );

function gn_post_type_eve_divers_Activate(){
    
    flush_rewrite_rules(false);

}

/*
    This code add the custom meta boxes "Nombre de places " and "Participants"
    in the Create/Edit post section, only for the custom post types cours, fêtes, ..

*/


add_action( 'add_meta_boxes', 'add_meta_box_divers' );
add_action( 'save_post', 'meta_box_divers_save' );

/* Adds a box to the main column on the Post and Page edit screens */
function add_meta_box_divers() {
    add_meta_box( 
        'myplugin_sectionid',
        'Gestion des participants',
        'meta_box_evenements',
        'les-evenements' 
    );

}


function meta_box_evenements( $post ) {

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


function meta_box_divers_save( $post_id ) {
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



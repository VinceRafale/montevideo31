<?php

/*
Plugin Name: BEW Culture framework - Advanced Users Management
Plugin URI: BEW Culture website
Description: Advanced management of users : User groups, content locking, frontend login/register/profile editing

Author: Adrien Menoret
Author URI: mailto: adrien.menoret@gmail.com 

Version: 0.2
*/

 
//files


include(dirname(realpath(__FILE__)).'/bew_users_management/includes/helpers.php');
include(dirname(realpath(__FILE__)).'/bew_users_management/includes/custom_user_data.php');
include(dirname(realpath(__FILE__)).'/bew_users_management/includes/custom_wp_user_search.php');
include(dirname(realpath(__FILE__)).'/bew_users_management/admin_pages/modification/edit_user.php');
include(dirname(realpath(__FILE__)).'/bew_users_management/admin_pages/modification/users.php');
include(dirname(realpath(__FILE__)).'/bew_users_management/admin_pages/modification/edit_post.php');
include(dirname(realpath(__FILE__)).'/bew_users_management/frontend_um/frontend_um.php');

// language

$plugin_dir = basename(dirname(__FILE__));
load_plugin_textdomain( 'default', 'wp-content/plugins/' . $plugin_dir, $plugin_dir  ); 

//// Activation hook
register_activation_hook( __FILE__, 'adu_activate' );

function adu_activate(){
    $groups = array( 
		"all_users" => array('name' => __('All registered users'))
    );
	update_option('adu_groups', $groups);
}


/// Add group menu in wp admin
add_action('admin_menu', 'adu_admin_menu', 280);

function adu_admin_menu() {
	add_submenu_page( 'users.php', __('Groupes utilisateurs'), __('Groupes'), 'edit_users', 'users_groups', 'adu_manage_users_groups'); 
}

function adu_manage_users_groups() {
	$columns = array('group_name' => __('Nom du groupe'), 'users' => __('Utilisateurs'), 'actions' => __('Actions'));
	register_column_headers('adu_users_groups', $columns);
	include(dirname(realpath(__FILE__)).'/bew_users_management/admin_pages/new/manage_groups.php');
}

add_filter('the_content', 'bew_frontend_um_the_content', 99, 1);
	

// Archive or unarchive annonce
if( !empty( $_POST['archive_annonce_action']) ) {
	
	$my_post = array();
  	$my_post['ID'] = $_POST['archive_annonce_id'];
  	
	if( $_POST['archive_annonce_action'] == "Archiver" ){
		$my_post['post_status'] = 'draft';
	}elseif( $_POST['archive_annonce_action'] == "Remettre en ligne" ){
		$my_post['post_status'] = 'pending';
	}else{
		$my_post['post_status'] = 'pending';
	}
		
	$pid = wp_update_post( $my_post );
	// handle query result
	if( !$pid ){
		// post not saved
		$saved = false;
		$technicalIssue = true;	
	}else{
		// post saved
		$saved = true;
	}


}




/// quicktag 	
function bew_frontend_um_the_content($content){
	
	/*
	 *	SIGN UP PAGE
	 */
	if(stristr($content, '[bew-register]')){
		$content = str_replace('[bew-register]', new bew_frontend_um_register(), $content );
		$content .= "
		<script type=\"text/javascript\">
    	jQuery(document).ready(function() {
    		jQuery('#fake_username').attr('disabled', true);
			jQuery('#first_name, #last_name').live('keyup', function(){
				fakename = jQuery('#first_name').val() + '.' + jQuery('#last_name').val();  
				jQuery('#fake_username').val(fakename);
			});			
		});
		</script>
		";
		
			
	}
	
	/*
	 *	LOGIN PAGE
	 */		
	if(stristr($content, '[bew-login]') ){
		// test if $_SESSION['product_id'] exists in the case a user not registered try to buy product
		
		if( isset( $_SESSION['product_id'] ) ){
		
			// user comes from product page, change the header
			$bew_p_redirect = get_ID_by_slug('inscription');
		    $content = "<span class='bp_advise' >Il est n&#233;c&#233;ssaire d'&#234;tre inscrit au pr&#233;alable pour pouvoir effectuer une donation ou un achat sur le site.<br/>Si vous n'&#234;tes pas inscrit, cliquez ici : <a href='".$bew_p_redirect."' >Inscription</a></span>";
			$content .= new bew_frontend_um_login();
			$content = str_replace('[bew-login]', new bew_frontend_um_login(), $content);
			
		}elseif( isset($_SESSION['alert_sms']) ){
			
			// user comes from "inscription alertes SMS"
			$bew_p_redirect = get_ID_by_slug('inscription');
		    $content = "<span class='bp_advise' >Il est n&#233;c&#233;ssaire d'&#234;tre inscrit au pr&#233;alable pour pouvoir s'abonner aux Alertes SMS.<br/>Si vous n'&#234;tes pas inscrit, cliquez ici : <a href='".$bew_p_redirect."' >Inscription</a></span>";
			$content .= new bew_frontend_um_login();
			$content = str_replace('[bew-login]', new bew_frontend_um_login(), $content);
		
		}else{
			// user comes from any page, normal header
			$content = str_replace('[bew-login]', new bew_frontend_um_login(), $content);
		}
		
		$content .= "Mot de passe oubli&#233; ? Cliquer <a href='http://synagogue-montevideo31.com/wp-login.php?action=lostpassword' >ici</a>";
	
	}
	
	/*
	 *	PROFILE PAGE
	 */
	if(stristr($content, '[bew-profile]') ){
		
		//$content = str_replace('[bew-profile]', "[bew-profile] $script", $content);
		$content = str_replace('[bew-profile]', new bew_frontend_um_profile(), $content);
	//	$content .= new bew_frontend_profile_pass();
		
		
	}
	
	
	/*
	 *	PAIEMENTS EFFECTUES
	 */
	if(stristr($content, '[bew-bought-items]') ){
		
		if(!is_user_logged_in()) {
        	//no user logged in
      		$content = str_replace('[bew-bought-items]', "" , $content);
      	}else{
      		// user is logged
			$content = str_replace('[bew-bought-items]', bew_retrieve_user_paiements() , $content);
		}
	}
	
	/*
	 *	PETITES ANNONCES
	 */
	if(stristr($content, '[bew-annonces]') ){
		
		if(!is_user_logged_in()) {
        	// user not logged in : do not display the field
      		$content = str_replace('[bew-annonces]', "" , $content);
      	}else{
			// user logged : display petites annonces fieldset
			$content = str_replace('[bew-annonces]', bew_retrieve_user_annonces() , $content);
      	}
      	
      	
	}
	
	/*
	 *	UNSUBSCRIBE
	 */
	if(stristr($content, '[bew-unsubscribe]') ){
		
		if(!is_user_logged_in()) {
        	// user not logged in : do not display the field
      		$content = str_replace('[bew-unsubscribe]', "" , $content);
      	}else{
			// user logged : display petites annonces fieldset
			$content = str_replace('[bew-unsubscribe]', bew_unsubscribe_form() , $content);
      	}
      	
      	
	}
	
	/*
	 *	RETURN HACKED CONTENT
	 */
	return $content;
}


/*
 *	Output an unsubscribe fieldset form	
 */
function bew_unsubscribe_form(){
	
	global $current_user;
	get_currentuserinfo();	
	$user_id = $current_user->ID;
	
	// Start output string
	$output = "
		<fieldset>
			<legend>Gestion du compte</legend>
			<form name=\"bew_unsubscribe_form\" method=\"POST\" action=\"/desinscription\">  
	";
	
	$telephone = get_user_meta($user_id, 'adu_user_mobile', true);
	$already_unsubscribed = get_user_meta($user_id, 'unsubscribed_sms', true);
	
	
	if( !empty($telephone)&&(!$already_unsubscribed) ){
		
		$output .= " 
			<input type=\"checkbox\" name=\"unsubscribe_from_sms\" class=\"button-primary\" unchecked >
			<label for=\"unsubscribe_from_sms\">Se d&#233;sinscrire des Alertes SMS</label>
			<br/>
		";
	}
	
	// HANDLE NEWSLETTER
	/*
	
	<input type=\"checkbox\" name=\"unsubscribe_from_newsletter\" class=\"button-primary\" unchecked >
				<label for=\"unsubscribe_from_newsletter\">Se d&#233;sinscrire de la Newsletter</label>
				<br/>
	
	
	*/
				
				
	$output .= "			
			<input type=\"checkbox\" name=\"unsubscribe_from_site\" class=\"button-primary\" unchecked >
			<label for=\"unsubscribe_from_site\">
				<span >Se d&#233;sinscrire du site Montevideo. Attention, cette action est irr&#233;versible.
				</span>
			</label>
			<br/>
			<input type=\"hidden\" name=\"unsubscribe_form_action\" class=\"button-primary\" value=\"unsubscribe\" >
			<input type=\"submit\" id=\"profile_page_button\" name=\"submit\" class=\"button-primary\" value=\"Appliquer les modifications\" onclick=\"confirmation()\">  
    		</form>  			
		</fieldset>
	";
	
	return $output;
}

/*
 *	Retrieve paiements in user profile page
 */
function bew_retrieve_user_paiements(){
	
	global $current_user, $wpdb;
	$table_logs = get_option('bew_payment_logs_table_name');  
    $table_products = get_option('bew_payment_products_table_name');  
    $table_users = $wpdb->prefix."usermeta";
    get_currentuserinfo();
	// var used to know if there is any petite annonce or not
	$isThereContent = false;
	
	// fieldset open tag
	$fieldsetOpener = "
		<fieldset>
			<legend>Mes achats</legend>";
	// user content table header
	$userContent .="
		<table id=\"userProductsTable\">
    	<tr id='userProductTableHeader'>
			<th>Date de l'achat</th>
			<th>Produit</td>
			<th>Montant</td>
			<th>N&#186; transaction :</th>
			<th>N&#186; client :</th>
		</tr>";
	// query user's data
	$myrows = $wpdb->get_results( "SELECT 
    	a.payment_date ,
    	a.atos_customer_id ,
    	a.atos_transaction_id ,
    	a.amount,
    	b.name
    	FROM $table_logs a, $table_products b  
    	WHERE a.product_id = b.id AND a.user_id = '".$current_user->ID."'
   		ORDER BY a.payment_date DESC" );
    foreach($myrows as $log){
    	$isThereContent = true;
    	$userContent .= "<tr>
    						<td>".$log->payment_date."</td>
    						<td>".$log->name."</td>
    						<td>".$log->amount."&#128;</td>
    						<td>".$log->atos_customer_id."</td>
    						<td>".$log->atos_transaction_id."</td>
    					 </tr>";
    }
	// close html table
	$userContent .="</table>";
	// close fieldset HTML
	$fieldsetClosure .= "</fieldset>";

	// handle the case where there is no paiementsfor this user
	if( $isThereContent ){
		return $fieldsetOpener.$userContent.$fieldsetClosure;
	}else{
		return $fieldsetOpener."Vous n'avez aucun paiement enregistr&#233;.".$fieldsetClosure;
	}
	 
	

}

/*
 *	Retrieve petites annonces in user profile page
 */
function bew_retrieve_user_annonces(){

	global $current_user;	
	get_currentuserinfo();
	
	// INITS
	
	// Url to "modifier petite annonce" page
	$urlModifierAnnonce = get_ID_by_slug('modifier-une-petite-annonce');
	$urlEffacerAnnonce = get_ID_by_slug('supprimer-une-petite-annonce');

	// var used to know if there is any petite annonce or not
	$isThereContent = false;
	// output string : fieldset header
	$fieldsetOpener = "
		<fieldset>
		<legend>Mes Petites annonces</legend>";
	// start string content
	$userContent = "
			<table id=\"userProductsTable\">
    		<!-- table header -->
    		<tr id='userProductTableHeader'>
				<th>Statut</th>
				<th>Titre de l'annonce</th>
				<th colspan='2' style='width: 50%;'></th>";
	// column for archive button
//	$userContent .= "<th></th>";
				
	// close row		
	$userContent .= "</tr>";
	
	// RETRIEVE PETITES ANNONCES
	
	query_posts( array ( 'post_type' => 'petite_annonce', 'author' => $current_user->ID, 'post_status' => array('publish', 'pending', 'draft') ) );
	// for each petite annonce
	while ( have_posts() ) : the_post();
		// There is some content :
		$isThereContent = true;
		// Retrieve post fields
		$p_title = get_the_title();
		$p_id = get_the_ID();
		$p_content = get_the_content();
		$custom_fields = get_post_custom();
		// Display a line in the table
		$userContent .="
			<tr>
				<td>".translate_post_status(get_post_status())."</td>
				<td>".$p_title."</td>
				<td>
					<form name=\"EditPetiteAnnonce\" action=\"$urlModifierAnnonce\" method=\"POST\">
    					<input type=\"hidden\" name=\"edit_annonce_id\" value=\"".$p_id."\" >
    					<input type=\"hidden\" name=\"edit_annonce_user_id\" value=".$current_user->ID." >
    					<input type=\"hidden\" name=\"edit_annonce_title\" value=\"".$p_title."\" >
    					<input type=\"hidden\" name=\"edit_annonce_price\" value=".$custom_fields['annonce_prix']['0']." >
    					<input type=\"hidden\" name=\"edit_annonce_content\" value=\"".$p_content."\" >
					<input type=\"submit\" name=\"submit\" class=\"button-primary\" value=\"Modifier\" style=\"height:31px\">
					</form>
				</td>";
		
		if( (get_post_status($p_id) == 'publish')||(get_post_status($p_id) == 'pending')){
			// display button
			$userContent .= "<td>
					<form name=\"ArchivePetiteAnnonce\" action=\"$urlEffacerAnnonce\" method=\"POST\">
    					<input type=\"hidden\" name=\"delete_annonce_id\" value=\"".$p_id."\" >
    					<input type=\"hidden\" name=\"delete_annonce_user_id\" value=".$current_user->ID." >
    					<input type=\"hidden\" name=\"delete_annonce_title\" value=\"".$p_title."\" >
    					<input type=\"submit\" style=\"height:31px\" name=\"submit\" class=\"button-primary profil-archive-petite-annonce\" id=\"".$p_id."\" value=\"Supprimer l'annonce\">
					</form>
				</td>";
				
		} // end of if on post's state
		
		
		
		// close the row
		$userContent .= "</tr>";
	endwhile;
	// reset wp query
	wp_reset_query();
	// close output string with appropriate html items
	$userContent .="</table>";
	// close fieldset HTML
	$fieldsetClosure .= "</fieldset>";
	
	// handle the case where there is no petite annonce for this user
	if( $isThereContent ){
		return $fieldsetOpener.$userContent.$fieldsetClosure;
	}else{
		return $fieldsetOpener."Vous n'avez aucune petite annonce enregistr&#233;e.".$fieldsetClosure;
	}
}


	

// DEPRECIATED : FILTER POST CONTENT IN CASE THE USER HAS NO RIGHT TO SEE IT. SEE LOOP.PHP 

//add_filter('the_content', 'bew_um_content_acl', 999, 1);
/*function bew_um_content_acl($content){

	global $post;
	global $current_user;
	
	$adu = new adu_groups();
	if(!isset($post->ID)) return $content;
	$groups = get_post_meta($post->ID, '_adu_acl_groups', true);
	if(in_array('_adu_acl_public_access', $groups)) return $content;	
	if(in_array('all_users', $groups) && $current_user->ID > 0) return $content;
	$user_groups = get_usermeta($current_user->ID, 'adu_groups');
	$ng = true;
	foreach($groups as $g){
		if(in_array($g, $user_groups)) return $content;
		if(array_key_exists($g, $adu->groups)) $ng = false;	
	}
	if($ng) return $content;
	/// Contenu protégé, affichage de la raison
	$content = '<p class="adu_private_content">';
	$content .= apply_filters('bew_user_private_message', __('This content is private.')).'<br/>';
	$upgrade_url = apply_filters('bew_user_upgrade_url', '#');
	$content .= "<a  href='$upgrade_url'>".apply_filters('bew_user_upgrade_message', __('Request access').' &raquo;').'</a>';
	$content .= '</p>';
	
	//return $content;
}*/










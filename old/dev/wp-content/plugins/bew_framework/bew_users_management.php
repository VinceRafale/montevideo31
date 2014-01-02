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


/// activate quicktags for frontend users mgmt

add_filter('the_content', 'bew_frontend_um_the_content', 99, 1);
	
	
function bew_frontend_um_the_content($content){



	
	if(stristr($content, '[bew-register]'))
		$content = str_replace('[bew-register]', new bew_frontend_um_register(), $content);
	if(stristr($content, '[bew-login]') )
		$content = str_replace('[bew-login]', new bew_frontend_um_login(), $content);
	if(stristr($content, '[bew-profile]') )
		$content = str_replace('[bew-profile]', new bew_frontend_um_profile(), $content);
	
	return $content;
}



add_filter('the_content', 'bew_um_content_acl', 999, 1);
	
	
function bew_um_content_acl($content){

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
	
	return $content;
	
	
	
		
	
}










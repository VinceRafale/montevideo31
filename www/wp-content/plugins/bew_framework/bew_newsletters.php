<?php 

/*
Plugin Name: BEW Framework - Groups Newsletter
Gestion des newsletters Email et SMS

Version: 0.1
*/


////// INCLUDES



		require_once(dirname(realpath(__FILE__)).'/bew_newsletters/custom_post_taxonomy.php');
		require_once(dirname(realpath(__FILE__)).'/bew_newsletters/newsletter_cron.php');
		require_once(dirname(realpath(__FILE__)).'/bew_newsletters/groups_admin_filters.php');
		require_once(dirname(realpath(__FILE__)).'/bew_newsletters/setup_newsletter.php');
		

////// LANGUAGE

		$plugin_dir = basename(dirname(__FILE__));
		load_plugin_textdomain( 'bew', 'wp-content/plugins/' . $plugin_dir, $plugin_dir  ); 
				

	   
	   
//////// ajoute deux boutons dans l'écran de gestion des groupes, dans la colonne "Actions" ///////////////////////
       
       add_filter('adu_group_actions', 'adn_group_email_users', 20, 2);
       add_filter('adu_group_actions', 'adn_group_sms_users', 30, 2);
       
       
       function adn_group_email_users($html, $group_id){
       
               $html.="<div class='adu_group_action'><a class='button' href='/wp-admin/users.php?page=adn_newsletters&group_id=$group_id'>".__('Newsletter Email').'</a></div>';
       
               return $html;
       
       }
       
       function adn_group_sms_users($html, $group_id){
       
               $html.="<div class='adu_group_action'><a class='button' href='/wp-admin/users.php?page=adn_newsletters&group_id=$group_id'>".__('Newsletter SMS').'</a></div>';
       
               return $html;
       
       }
	   
	   // ajoute le menu newsletter dans l'admin
       add_action('admin_menu', 'adn_admin_menu', 300);
       
	   function adn_admin_menu() {
            add_submenu_page( 'users.php', __('Newsletters et SMS'), __('Newsletters et SMS'), 'edit_users', 'adn_newsletters', 'adu_manage_users_groups');
       }
       
       function adn_newsletter_admin() {
            include(dirname(realpath(__FILE__)).'/admin_pages/new/newsletter.php');
       }

	   function adn_sms_admin() {
	   		include(dirname(realpath(__FILE__)).'/admin_pages/new/newsletter.php');
	   }
	   
	   
	   
	   
	   
	   
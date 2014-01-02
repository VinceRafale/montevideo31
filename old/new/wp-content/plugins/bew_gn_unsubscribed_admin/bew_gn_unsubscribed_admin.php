<?php
/*
Plugin Name:Bew : add a submenu for unsubscribed users 
Plugin URI: http://bew
Description: Unsubscribed users page plugin for montevideo
Version: 1.0
Author: Bewculture
Author URI: http://bew
*/



add_action('admin_menu', 'bew_gn_register_my_custom_submenu_page');

function bew_gn_register_my_custom_submenu_page() {
	add_submenu_page( 'users.php', 'Utilisateurs désinscrits', 'Utilisateurs désinscrits', 'manage_options', 'utilisateurs-desinscrits', 'bew_gn_my_custom_submenu_page_callback' ); 
}

function bew_gn_my_custom_submenu_page_callback() {
	include('bew_gn_unsubscribed_home.php');
}


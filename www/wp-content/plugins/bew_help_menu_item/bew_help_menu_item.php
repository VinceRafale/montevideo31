<?php
/*
Plugin Name:Bew Help Menu Item
Plugin URI: bewculture.com
Description: Menu item with online doc
Version: 1.0
Author: Bewculture
Author URI: http://bew
*/


add_action('admin_menu', 'bew_help_admin_menu' );  

function bew_help_home(){
	include('bew_help_home.php');

}

function bew_help_admin_menu(){
	
	$icon = plugin_dir_url(__FILE__)."/icon_help.png";
	
	$bew_help_page = add_menu_page( 
	'Documentation', 
	'Documentation', 
	'edit_posts',//'activate_plugins', 	
	'bew_help_menu', 
	'bew_help_home', 
	$icon );
	
	
}





<?php
/*
Plugin Name:Bew Mimi Newsletter
Plugin URI: http://bew
Description: Mad Mimi Newsletter plugin for montevideo
Version: 1.0
Author: Bewculture
Author URI: http://bew
*/



add_action('admin_menu', 'bew_mimi_admin_menu' );  

function bew_mimi_home(){
	// instanciate the model class
	include('bew_mimi_home.php');
	
}


function bew_mimi_styles(){
	
	wp_enqueue_style('bewpayment_style', home_url() . '/wp-content/plugins/bew_mimi_newsletter/includes/bewpayment_style.css');




}


function bew_mimi_admin_menu(){
	
	$icon = plugin_dir_url(__FILE__)."icon_mimi.png";
	
	$bew_payment_page = add_menu_page( 
	'Newsletter', 
	'Newsletter', 
	'moderate_comments',//'activate_plugins', 	
	'bew_mimi_menu', 
	'bew_mimi_home', 
	$icon, 
	12);
	
	
	
	
	
}





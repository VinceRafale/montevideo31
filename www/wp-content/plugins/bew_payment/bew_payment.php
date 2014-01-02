<?php
/*
Plugin Name:Bew Payment plugin
Plugin URI: http://bew
Description: Payment plugin for montevideo
Version: 1.0
Author: Bewculture
Author URI: http://bew
*/


define('bp_enum_value_subscription', 'souscription');
define('bp_enum_value_donation', 'donation');
define('bp_enum_value_classic', 'classique');
define('bp_enum_value_subscribed', 'souscrit');
define('bp_enum_value_donated', 'donnŽ');
//define('bp_enum_value_classic', 'classique');



add_action('admin_menu', 'bew_payment_admin_menu' );  

function bew_payment_home(){
	// instanciate the model class
	include('includes/bewpayment_db_management.php');
	$model = new bew_payment_model();
	// Homepage of the plugin
	include('includes/bewpayment_home.php');

}

function bew_payment_styles(){
	
	wp_enqueue_style('bewpayment_style', home_url() . '/wp-content/plugins/bew_payment/includes/bewpayment_style.css');




}

function bew_payment_scripts(){

	wp_enqueue_script('jquery.pajinate.min', home_url() . '/wp-content/plugins/bew_payment/includes/jquery.pajinate.min.js', array('jquery') );
	
	wp_enqueue_script('jquery.printElement.min', home_url() . '/wp-content/plugins/bew_payment/includes/jquery.printElement.min.js', array('jquery') );

	wp_enqueue_script('jquery.jqprint-0.3', home_url() . '/wp-content/plugins/bew_payment/includes/jquery.jqprint-0.3.js', array('jquery') );
	
	wp_enqueue_script('jquery.tools.min', home_url() . '/wp-content/plugins/bew_payment/includes/jquery.tools.min.js', array('jquery') );


}


function bew_payment_admin_menu(){
	
	$icon = plugin_dir_url(__FILE__)."/images/icon.png";
	
	$bew_payment_page = add_menu_page( 
	'Produits', 
	'Produits', 
	'moderate_comments',//'activate_plugins', 	
	'bew_payment_menu', 
	'bew_payment_home', 
	$icon, 
	4 );
	
	add_action( "admin_print_styles-$bew_payment_page", 'bew_payment_styles' );
	add_action( "admin_print_scripts-$bew_payment_page", 'bew_payment_scripts' );

	
	
	
	
}





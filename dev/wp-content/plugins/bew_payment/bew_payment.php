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




function bew_payment_admin_menu(){
	add_menu_page( 
	'Paiements', 
	'Paiements', 
	'read', 	
	'bew_payment_menu', 
	'bew_payment_home', 
	null, 
	4 );
	
}





<?php

/*
Plugin Name: Petites annonces
Plugin URI: http://desertours.com
Description: Gestion des petites annonces
Author: Desertours
Version: 1
Author URI: admin@desertours.com 
*/





require_once( dirname(realpath( __FILE__ )).'/dst_petites_annonces/dstpa_custom_post_taxonomy.php' ); 
require_once( dirname(realpath( __FILE__ )).'/dst_petites_annonces/dstpa_view_params.php' );
require_once( dirname(realpath( __FILE__ )).'/dst_petites_annonces/dstpa_view_contact.php' );
require_once( dirname(realpath( __FILE__ )).'/dst_petites_annonces/dstpa_controller_contact.php' ); 
require_once( dirname(realpath( __FILE__ )).'/dst_petites_annonces/dstpa_controller_black_list.php' );
require_once( dirname(realpath( __FILE__ )).'/dst_petites_annonces/dstpa_backend_logic.php' ); 
require_once( dirname(realpath( __FILE__ )).'/dst_petites_annonces/dstpa_controller_moderated_messages.php' ); 
require_once( dirname(realpath( __FILE__ )).'/dst_petites_annonces/dstpa_model_moderated_messages.php' ); 
require_once( dirname(realpath( __FILE__ )).'/dst_petites_annonces/dstpa_view_moderated_messages.php' ); 


add_action( 'init', 'dstpa_load_plugin_textdomain' );


function dstpa_load_plugin_textdomain(){ 

	load_plugin_textdomain( 'dstpa', false, dirname(realpath( __FILE__ )).'/dst_petites_annonces/language/' );

}



if(isset($_GET['dstpa_ajax']) && !empty($_GET['dstpa_ajax']))
	add_action('admin_init', 'dstpa_ajax_action');
	

function dstpa_ajax_action(){

	if(isset($_GET['delete_dstpa']) && !empty($_GET['delete_dstpa'])){
	
		$model = new dstpa_model_moderated_messages();
		//$view = new dstpa_view_contact(); 
		
		$confirm_delete = $model->delete_moderated_message( $_GET['delete_dstpa'] ); 
		
		echo "<div class='undo unspam' style='padding:1em 4px'>
				<div class='spam-undo-inside'>".__('The message has been deleted.', 'dstpa')."</div>
			</div>"; 
						
		die(); 
	
	}
	elseif(isset($_GET['approve_dstpa']) && !empty($_GET['approve_dstpa'])){
		
		$model = new dstpa_model_moderated_messages();
		$message = $model->select_moderated_message( $_GET['approve_dstpa'] );
		
		$args = unserialize($message[0]->meta_value);
		
		//envoi de l'email
		$contact = new dstpa_controller_contact();
		$confirm_send = $contact->send_email($args);
		
		//puis suppression du message
		$confirm_delete = $model->delete_moderated_message( $_GET['approve_dstpa'] );
		
		echo "<div class='undo unspam' style='padding:4px'>
				<div class='spam-undo-inside'>".__('The message has been sent.', 'dstpa')."</div>
			</div>";
			
		die();
	
	}

}
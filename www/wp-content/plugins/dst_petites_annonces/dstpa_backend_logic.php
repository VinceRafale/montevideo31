<?php


function dstpa_moderated_messages() {

	$controler = new dstpa_controller_moderated_messages();
		
	$controler->backend_logic();
		
	echo $controler->view->get_html();

	if( isset( $_GET['delete_dstpa'] ) && !empty( $_GET['delete_dstpa'] ) ) {
		
		$delete_pending_message = new dstpa_controller_moderated_messages( $_GET['delete_dstpa'] );
		
		$delete_pending_message->delete_message();
		
	}
		
	if( isset($_GET['approve_dstpa']) && !empty($_GET['approve_dstpa']) ) {
		
		$send_pending_message = new dstpa_controller_moderated_messages( $_GET['delete_dstpa'] );
		
		$send_pending_message->send_message();
	
	}
	
} 



function dstpa_moderated_messages_backoffice() {

	wp_add_dashboard_widget('moderated_messages', __('Personal columns pending messages', 'dstpa'), 'dstpa_moderated_messages');	
	
} 



add_action('wp_dashboard_setup', 'dstpa_moderated_messages_backoffice' );

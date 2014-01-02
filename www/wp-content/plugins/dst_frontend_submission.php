<?php

/*
Plugin Name: Submission article
Plugin URI: http://desertours.com
Description: For delete a new article in frontend
Author: Desertours
Version: 1
Author URI: admin@desertours.com 
*/


require_once( dirname(realpath( __FILE__ )).'/dst_frontend_submission/dstfp_controller_submission.php' );
require_once( dirname(realpath( __FILE__ )).'/dst_frontend_submission/dstfp_view_submission.php' ); 



add_filter('the_content', 'dst_frontend_submission', 99);


function dst_frontend_submission( $content ){ 

	$regex = '#{submission}(.*?){/submission}#s';
	
	$content = preg_replace_callback($regex, 'dst_callback_submission', $content);
	
	return $content;

}


function dst_callback_submission( $match ){ 

	//initialisation des arguments
	$controller = new dstfp_controller_submit();
	$controller->frontend_logic();
	
	echo $controller->view->gethtml();

}
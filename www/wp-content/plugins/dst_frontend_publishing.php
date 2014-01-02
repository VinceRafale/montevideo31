<?php

/*
Plugin Name: Publishing new article
Plugin URI: http://desertours.com
Description: For publishing a new article in frontend
Author: Desertours
Version: 1
Author URI: admin@desertours.com 
*/


require_once( dirname(realpath( __FILE__ )).'/dst_frontend_publishing/dstfp_controller_publish.php' );
require_once( dirname(realpath( __FILE__ )).'/dst_frontend_publishing/dstfp_view_publish.php' ); 



add_filter('the_content', 'dst_frontend_publishing', 99);


function dst_frontend_publishing( $content ){

	$regex = '#{publish}(.*?){/publish}#s';
	
	
	$content = preg_replace_callback($regex, 'dst_callback_publish', $content);
	
	$content .= "apres";
	
	return $content;

}


function dst_callback_publish( $match ){

	if($match[1] && !empty($match[1])){
		$args = htmlspecialchars_decode($match[1]);
		$args = html_entity_decode($args);
		
		//initialisation des arguments
		$controller = new dstfp_controller_publish( $args );
		$controller->frontend_logic();
	
		return $controller->view->gethtml();

	}else{
		return "nok";
	}
	
	
	
}
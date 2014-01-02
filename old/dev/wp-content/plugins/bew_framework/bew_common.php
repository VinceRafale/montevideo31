<?php

/*
Plugin Name: BEW Culture framework - Base
Plugin URI: BEW Culture website
Description: Diverses fonctionnalités ameliorees

Author: Adrien Menoret
Author URI: mailto: adrien.menoret@gmail.com 

Version: 0.2
*/


require_once(dirname(realpath(__FILE__)).'/bew_framework_common/widgets/pure_html.php');
require_once(dirname(realpath(__FILE__)).'/bew_framework_common/ie6_head.php');
require_once(dirname(realpath(__FILE__)).'/bew_framework_common/bew_messages.php');
require_once(dirname(realpath(__FILE__)).'/bew_framework_common/image_auto_downsize.php');
//require_once(dirname(realpath(__FILE__)).'/bew_framework_common/widgets/enhanced_rss.php');



add_action( 'init', 'bew_load_plugin_textdomain' );

function bew_load_plugin_textdomain() {
	load_plugin_textdomain( 'default', false, 'bew_framework/bew_framework_common/languages' );
}



function bew_redirect_js($url, $time = 3){

	$url = urldecode($url);
	
	$redirect = <<<EOD
	
	<span id="bew_cdown">
	  	$time
    </span>
	<script type="text/javascript">
	
		var bew_cd_time = $time;
	
		function bew_cdown(){
			
			bew_cd_time = bew_cd_time - 1;
			document.getElementById('bew_cdown').innerHTML = bew_cd_time;
			if(bew_cd_time < 1) document.location.href = '$url';
			else window.setTimeout('bew_cdown()', 1000);
		
		}
		
		window.setTimeout('bew_cdown()', 1000);
	
	</script>
	
EOD;
	
    return '<div class="bew_redirect_js">'.__('You will automatically be redirected in').$redirect.__('seconds').'<br /><div class="bew_redirect_js_continue"><a href="'.$url.'" class="button">'.__('Continue').' &raquo;</a><div class="clear"></div></div></div>';

}

?><?php

function dst_letter_wrap($text, $limit = 20, $points = true){
	
	$arr = explode(' ', $text);
	
	$nbr_letters = 0;
	
	foreach($arr as $word){
		
		$nbr_letters += strlen(strip_tags($word));
		if($nbr_letters < $limit) { $words[] = $word; } else {  break; }
		
	}
	
	$result = implode(' ', $words).($points ? ' ...' : '');
	
	return $result;
}


function dst_adjacent_links_letterwrap_activate($limit = 20){

	$GLOBALS['dst_adjacent_links_letter_wrap_limit'] = $limit;
	
	add_filter('the_title', 'dst_adjacent_links_letter_wrap', 99, 1);
	
}


function dst_adjacent_links_letterwrap_desactivate(){
	
	remove_filter('the_title', 'dst_adjacent_links_letter_wrap', 99, 1);
	
	unset($GLOBALS['dst_adjacent_links_letter_wrap_limit']);

}


function dst_adjacent_links_letter_wrap($title){ return dst_letter_wrap($title, $GLOBALS['dst_adjacent_links_letter_wrap_limit']); }


function ifisset(&$var, $default = false){
	if(isset($var)) return $var; else return $default;
}




if ( !function_exists('wp_generate_password') ) :
/**
 * Generates a random password drawn from the defined set of characters.
 *
 * @since 2.5
 *
 * @param int $length The length of password to generate
 * @param bool $special_chars Whether to include standard special characters. Default true.
 * @param bool $extra_special_chars Whether to include other special characters. Used when
 *   generating secret keys and salts. Default false.
 * @return string The random password
 **/
function wp_generate_password( $length = 10, $special_chars = true, $extra_special_chars = false ) {
	$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	

	$password = '';
	for ( $i = 0; $i < $length; $i++ ) {
		$password .= substr($chars, wp_rand(0, strlen($chars) - 1), 1);
	}

	// random_password filter was previously in random_password function which was deprecated
	return apply_filters('random_password', $password);
}
endif;


add_filter('the_content', 'dst_strip_tags', 0, 1);

function dst_strip_tags($content){ 

	$content = strip_tags($content, '<strong><b><h1><h2><h3><h4><h5><h6><em><a><ul><ol><li><img><table><th><tr><td><i><p><br><pre>');
	
	$content = str_replace('<p> </p>', '', $content);
	$content = str_replace('<p>  </p>', '', $content);
	$content = str_replace('<p></p>', '', $content);
	
	return $content;
	
}



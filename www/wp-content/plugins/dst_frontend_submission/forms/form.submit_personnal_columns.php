<?php


$form_config = Array(

	'email' => Array(
    	'type'           => 'inputText',
    	'displayname'    => __('Votre email', 'dstpa'),
		'prefix'    	 => '('.__('Requis', 'dstpa').')',
    	'validation'     => Array(
        						Array( 'type'    => 'wpRequiredEmail' ),
      						),
    ),
	
	'post_id' => Array(
   		'type'           => 'inputText',
    	'displayname'    => __('Num&eacute;ro de la petite annonce', 'dstpa'),
		'prefix'   		 => '('.__('Requis', 'dstpa').')',
    	'validation'     => Array(
								Array( 'type' => 'required' ),
      						),
    ),
);
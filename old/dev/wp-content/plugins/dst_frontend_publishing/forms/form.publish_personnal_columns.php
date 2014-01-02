<?php


$form_config = Array(

	'title' => Array(
   		'type'           => 'inputText',
    	'displayname'    => __('Titre', 'dstpa'),
		'prefix'   		 => '('.__('Requis', 'dstpa').')',
    	'validation'     => Array(
								Array( 'type' => 'required' ),
      						),
    ),
	
	'message' => Array(
      	'type'           => 'textarea',
		'prefix'    	 => '('.__('Requis').')',
		'postfix'    	 => __('ATTENTION : Ne mettez pas votre adresse email ou votre numero de t&eacute;l&eacute;phone. Dans le cas contra&icirc;re, nous ne pourrons pas &ecirc;tre tenus responsables du spam ou des appels ind&eacute;sirables que vous recevrez.'),
      	'displayname'    => __('Description'),
      	'validation'     => Array(
        						Array( 'type'    => 'required' ),
     						),
    ),
	
	'inputfile' => Array(
		'type'           => 'inputFile',
		'displayname'    => __('Ins&eacute;rez une image dans votre petite annonce', 'dstpa'),
	), 
	
	'taxonomy' => Array(
      	'type'           => 'inputMulticheckbox',
		'prefix'    	 => '('.__('En choisir une minimum', 'dstpa').')',
      	'displayname'    => __('Categories'),
		'values'		=> Array(),
    ),
	
	'name_author' => Array(
    	'type'           => 'inputText',
    	'displayname'    => __('Votre nom', 'dstpa'),
		'prefix'    	 => '('.__('Requis', 'dstpa').')',
    	'validation'     => Array(
        						Array( 'type'    => 'required' ),
      						),
    ),
	
	'phone' => Array(
    	'type'           => 'inputText',
    	'displayname'    => __('Votre num&eacute;ro de t&eacute;l&eacute;phone', 'dstpa'),
      	'postfix'        => ' '.__("Ne sera pas affich&eacute;", 'dstpa'),
    ),
	
	'email' => Array(
    	'type'           => 'inputText',
    	'displayname'    => __('Votre email', 'dstpa'),
		'prefix'    	 => '('.__('Requis', 'dstpa').')',
      	'postfix'        => ' '.__("Ne sera pas affich&eacute;. Les internautes pourront vous contacter via le formulaire de contact qui sera automatiquement ajout&eacute; &agrave; votre petite annonce.", 'dstpa'),
    	'validation'     => Array(
        						Array( 'type'    => 'wpRequiredEmail' ),
      						),
    ),
	
);
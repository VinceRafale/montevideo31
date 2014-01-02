<?php


$form_config = Array(

	'contact_name' => Array(
    'type'           => 'inputText',
    'displayname'    => __('Votre nom', 'dstpa'),
	'prefix'    => '('.__('Requis', 'dstpa').')',
    'validation'     => Array(
							Array( 'type' => 'required' ),
      					),
    ),
	
	'email' => Array(
    'type'           => 'inputText',
    'displayname'    => __('Votre email', 'dstpa'),
	'prefix'    => '('.__('Requis', 'dstpa').')',
    'postfix'        => ' '.__("sera envoy&eacute; &agrave; l'auteur.", 'dstpa'),
    'validation'     => Array(
        					Array( 'type'    => 'wpRequiredEmail' ),
      					),
    ),
	
	'phone' => Array(
    'type'           => 'inputText',
    'displayname'    => __('Votre num&eacute;ro de t&eacute;l&eacute;phone', 'dstpa'),
    'postfix'        => ' '.__("sera envoy&eacute; &agrave; l'auteur", 'dstpa')
    ),
	
	'message' => Array(
      'type'           => 'textarea',
	'prefix'    => '('.__('Requis', 'dstpa').')',
      'displayname'    => __('Entrez votre message', 'dstpa'),
      'validation'     => Array(
        Array( 'type'    => 'required' ),
      ),
    ),
	/*
	'copy' => Array(
    'type'           => 'inputCheckbox',
    'displayname'    =>  __('Receive a copy'),
    //'prefix'         => '...don\'t you?! (it\'s a <code>prefix</code>) ',
    'help'           => __('You will receive a copy of this message.'),

    ),
	*/
);
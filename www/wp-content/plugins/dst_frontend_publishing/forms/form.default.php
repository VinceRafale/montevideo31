<?php


$form_config = Array(

	'title' => Array(
   		'type'           => 'inputText',
    	'displayname'    => __('Title'),
		'prefix'   		 => '('.__('Required').')',
    	'validation'     => Array(
								Array( 'type' => 'required' ),
      						),
    ),
	
	'message' => Array(
      	'type'           => 'textarea',
		'prefix'    	 => '('.__('Required').')',
      	'displayname'    => __('Content'),
      	'validation'     => Array(
        						Array( 'type'    => 'required' ),
     						),
    ),
	
	'inputfile' => Array(
		'type'           => 'inputFile',
		'displayname'    => __('Image illustrating your subject'),
	), 
	
	'taxonomy' => Array(
      	'type'           => 'inputMulticheckbox',
		'prefix'    	 => '('.__('Please choose at least a category').')',
      	'displayname'    => __('Categories'),
		'values'		=> Array(),
    ),
	
	'author_name' => Array(
    	'type'           => 'inputText',
    	'displayname'    => __('Your name'),
		'prefix'    	 => '('.__('Required').')',
    	'validation'     => Array(
        						Array( 'type'    => 'required' ),
      						),
    ),
	
	'email' => Array(
    	'type'           => 'inputText',
    	'displayname'    => __('Your email address'),
		'prefix'    	 => '('.__('Required').')',
		'postfix'    	 => '('.__("Will not be displayed").')',
    	'validation'     => Array(
        						Array( 'type'    => 'wpRequiredEmail' ),
      						),
    ),
	
);
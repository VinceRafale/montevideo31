<?php

  $config = Array(

	'fs0' => Array(
      'type'   => 'fieldset',
      'legend' => __('Réinitialiser votre mot de passe'),
      'submit' => 1,  // include submit button in this fieldset
    ),

	'user_pass' => Array(
      'type'           => 'inputPassword',
      'displayname'    => __('Mot de passe'),
      'postfix'        => ' '.__("sera affiché sur le site"),
      
    ),
	
	'user_pass2' => Array(
      'type'           => 'inputPassword',
      'displayname'    => __('Confirmez le mot de passe'),
      'postfix'        => ' '.__("sera affiché sur le site"),
      'validation'     => Array(
        Array( 'type' => 'required',  'match_with' => 'user_pass' ),
      ),
    ),
    

	'change' => Array(
      'type'     => 'inputHidden',
      'value'    => '1'
      
    ),
	
	'redirect' => Array(
      'type'     => 'inputHidden',
      'value'    => isset($_REQUEST['redirect']) ? urlencode($_REQUEST['redirect']) : ""
      
    ),


  );

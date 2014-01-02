<?php

  $config = Array(

   /* 'fs0' => Array(
      'type'   => 'fieldset',
      'legend' => __('Name and login information'),
      'submit' => 1,  // include submit button in this fieldset
    ),
	*/
	
	'fs0' => Array(
      'type'   => 'fieldset',
      'legend' => __('Vos informations personnelles'),
      'submit' => 1,  // include submit button in this fieldset
    ),
	
   	
	'bew_user_first_name' => Array(
      'type'           => 'inputText',
      'displayname'    => __('Prénom'),
      'postfix'        => ' '.__("sera affiché sur le site"),
      'validation'     => Array(
        Array( 'type'    => 'required' ),
      ),
    ),
	
	'bew_user_last_name' => Array(
      'type'           => 'inputText',
      'displayname'    => __('Nom'),
      'postfix'        => ' '.__("sera affiché sur le site"),
      'validation'     => Array(
        Array( 'type'    => 'required' ),
      ),
    ),
	
	/*'new_user_email' => Array(
      'type'           => 'inputText',
      'displayname'    => __('Your email'),
      'postfix'        => ' '.__("won't be displayed. <br/>We do not sell our email adresses to any third party."),
      'validation'     => Array(
        Array( 'type'    => 'wpRequiredEmail' ),
      ),
    ),
	
	'new_user_pass' => Array(
      'type'           => 'inputPassword',
      'displayname'    => __('Change your password'),
      //'postfix'        => ' and this is how a <code>postfix</code> looks like!',
      
    ),
	
	'user_pass2' => Array(
      'type'           => 'inputPassword',
      'displayname'    => __('Confirm your new password'),
      //'postfix'        => ' and this is how a <code>postfix</code> looks like!',
      
    ),*/


	/*
    'bew_user_nickname' => Array(
       'type'           => 'inputText',
      'displayname'    => __('Votre pseudonyme'),
      'postfix'        => ' '.__("sera affiché dans vos commentaires"),

      'validation'     => Array(
        Array( 'type'    => 'required' ),
      ),
    ),
	*/
	/*
	'fs1' => Array(
      'type'   => 'fieldset',
      'legend' => __('Quelques mots sur vous'),
      'submit' => 1,  // include submit button in this fieldset
    ),
	
	'bew_user_description' => Array(
      'type'           => 'textArea',
      'displayname'    =>  __('Quelques mots sur vous'),
      //'prefix'         => '...don\'t you?! (it\'s a <code>prefix</code>) ',
      'help'           => __('Can be displayed if you want.'),
     
    ),
	*/
	
	
	

	
	
	
	
	'bew_user_adu_user_adresse' => Array(
       'type'           => 'inputText',
      'displayname'    => __('Adresse'),
      'postfix'        => ' '.__("ne sera pas affiché"),

    ),
	'bew_user_adu_user_code_postal' => Array(
       'type'           => 'inputText',
      'displayname'    => __('Code postal'),
      'postfix'        => ' '.__("ne sera pas affiché"),

    ),
	'bew_user_adu_user_ville' => Array(
       'type'           => 'inputText',
      'displayname'    => __('Ville'),
      'postfix'        => ' '.__("ne sera pas affiché"),

    ),
    /*
	'bew_user_adu_user_pays' => Array(
       'type'           => 'inputText',
      'displayname'    => __('Pays'),
      'postfix'        => ' '.__("ne sera pas affiché"),

    ),
	'bew_user_adu_user_telephone' => Array(
       'type'           => 'inputText',
      'displayname'    => __('Numéro de téléphone'),
      'postfix'        => ' '.__("ne sera pas affiché"),

    ),
	'bew_user_adu_user_fax' => Array(
       'type'           => 'inputText',
      'displayname'    => __('Fax'),
      'postfix'        => ' '.__("ne sera pas affiché"),

    ),
    */
    
	'bew_user_adu_user_mobile' => Array(
       'type'           => 'inputText',
      'displayname'    => __('Numéro de téléphone'),
      'postfix'        => ' '.__("ne sera pas affiché"),

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

<?php
  
  $conditionsGeneralesUrl = get_ID_by_slug('conditions-generales-dutilisation');

  $config = Array(

    /*'fs0' => Array(
      'type'   => 'fieldset',
      'legend' => 'inputText example',
      'submit' => 1,  // include submit button in this fieldset
    ),
	*/
	
   /* 'user_login' => Array(
      'type'           => 'inputText',
      'displayname'    => __('Choisissez un pseudonyme'),
      //'postfix'        => ' and this is how a <code>postfix</code> looks like!',
      'validation'     => Array(
        Array( 'type'    => 'required' ),
      ),
    ),
	*/
	
	
	'first_name' => Array(
      'type'           => 'inputText',
      'displayname'    => __('Prénom'),
      'postfix'        => ' '.__("sera affiché sur le site"),
      'validation'     => Array(
        Array( 'type'    => 'required' ),
      ),
    ),
	
	'last_name' => Array(
      'type'           => 'inputText',
      'displayname'    => __('Nom'),
      'postfix'        => ' '.__("sera affiché sur le site"),
      'validation'     => Array(
        Array( 'type'    => 'required' ),
      ),
    ),
	
	'fake_username' => Array(
      'type'           => 'inputText',
      'displayname'    => 'Nom affiché',
      'readonly'       => 1 ,
      'htmlid'         => 'fake_username',
    ),
	
	
	'user_email' => Array(
      'type'           => 'inputText',
      'displayname'    => 'Adresse Email',
      'postfix'        => ' '.__("ne sera pas affiché sur le site"),
      'validation'     => Array(
        Array( 'type'    => 'wpRequiredUniqueEmail' ),
      ),
    ),
	
	'user_pass' => Array(
      'type'           => 'inputPassword',
      'displayname'    => __('Mot de passe'),
      //'postfix'        => ' and this is how a <code>postfix</code> looks like!',
      'validation'     => Array(
        Array( 'type'    => 'required' ),
      ),
    ),
	
	'user_pass2' => Array(
      'type'           => 'inputPassword',
      'displayname'    => __('Confirmez le mot de passe'),
      //'postfix'        => ' and this is how a <code>postfix</code> looks like!',
      'validation'     => Array(
        Array( 'type'    => 'requiredmatch', 'match_with' => 'user_pass' ),
      ),
    ),

	/*
	'user_email' => Array(
      'type'           => 'inputText',
      'displayname'    => __('Email'),
      'postfix'        => ' '.__("ne sera pas affiché"),
      'validation'     => Array(
        Array( 'type'    => 'wprequireduniqueemail' ),
      ),
    ),
    */
    
	
	'bew_user_adu_user_mobile' => Array(
      'type'           => 'inputText',
      'displayname'    => __('Numéro de téléphone (optionnel)'),
      'postfix'        => ' '.__("ne sera pas affiché."),

    ),


    'acceptrules' => Array(
      'type'           => 'inputCheckbox',
      'displayname'    =>  __('Conditions générales'),
      //'prefix'         => '...don\'t you?! (it\'s a <code>prefix</code>) ',
      'postfix'        => ' '.__("Il est obligatoire d'accepter les <a href=".$conditionsGeneralesUrl." target='_blank' >Conditions G&#233;n&#233;rales d'utilisation</a> pour s'inscrire au site."),
    /*  'help'           => __('aide'), */
      'validation'     => Array(
        Array( 'type'    => 'required' ),
      ),
    ),
	
	'redirect' => Array(
      'type'     => 'inputHidden',
      'value'    => isset($_REQUEST['redirect']) ? urlencode($_REQUEST['redirect']) : ""
      
    ),


  );

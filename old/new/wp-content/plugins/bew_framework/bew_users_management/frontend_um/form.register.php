<?php
  
  $conditionsGeneralesUrl = get_ID_by_slug('conditions-generales-dutilisation');

  $config = Array(

    /*'fs0' => Array(
      'type'   => 'fieldset',
      'legend' => 'inputText example',
      'submit' => 1,  // include submit button in this fieldset
    ),
	*/
	
    'user_login' => Array(
      'type'           => 'inputText',
      'displayname'    => __('Choose a nickname'),
      //'postfix'        => ' and this is how a <code>postfix</code> looks like!',
      'validation'     => Array(
        Array( 'type'    => 'required' ),
      ),
    ),
	
	'first_name' => Array(
      'type'           => 'inputText',
      'displayname'    => __('Enter your first name'),
      'postfix'        => ' '.__("won't be displayed"),
      'validation'     => Array(
        Array( 'type'    => 'required' ),
      ),
    ),
	
	'last_name' => Array(
      'type'           => 'inputText',
      'displayname'    => __('Enter your last name'),
      'postfix'        => ' '.__("won't be displayed"),
      'validation'     => Array(
        Array( 'type'    => 'required' ),
      ),
    ),
	
	'user_email' => Array(
      'type'           => 'inputText',
      'displayname'    => __('Enter your email'),
      'postfix'        => ' '.__("won't be displayed. <br/>We do not sell our email adresses to any third party."),
      'validation'     => Array(
        Array( 'type'    => 'wpRequiredUniqueEmail' ),
      ),
    ),
	
	'user_pass' => Array(
      'type'           => 'inputPassword',
      'displayname'    => __('Enter your password'),
      //'postfix'        => ' and this is how a <code>postfix</code> looks like!',
      'validation'     => Array(
        Array( 'type'    => 'required' ),
      ),
    ),
	
	'user_pass2' => Array(
      'type'           => 'inputPassword',
      'displayname'    => __('Confirm your password'),
      //'postfix'        => ' and this is how a <code>postfix</code> looks like!',
      'validation'     => Array(
        Array( 'type'    => 'requiredmatch', 'match_with' => 'user_pass' ),
      ),
    ),

	'user_email' => Array(
      'type'           => 'inputText',
      'displayname'    => __('Enter your email'),
      'postfix'        => ' '.__("won't be displayed."),
      'validation'     => Array(
        Array( 'type'    => 'wprequireduniqueemail' ),
      ),
    ),
	
	'bew_user_adu_user_mobile' => Array(
      'type'           => 'inputText',
      'displayname'    => __('Enter your phone (optionnal)'),
      'postfix'        => ' '.__("won't be displayed."),

    ),


    'acceptrules' => Array(
      'type'           => 'inputCheckbox',
      'displayname'    =>  __('disclaimer'),
      //'prefix'         => '...don\'t you?! (it\'s a <code>prefix</code>) ',
      'postfix'        => ' '.__("Il est obligatoire d'accepter les <a href=".$conditionsGeneralesUrl." target='_blank' >Conditions G&#233;n&#233;rales d'utilisation</a> pour s'inscrire au site."),
      'help'           => __('aide'),
      'validation'     => Array(
        Array( 'type'    => 'required' ),
      ),
    ),
	
	'redirect' => Array(
      'type'     => 'inputHidden',
      'value'    => isset($_REQUEST['redirect']) ? urlencode($_REQUEST['redirect']) : ""
      
    ),


  );

<?php

  $config = Array(

    'fs0' => Array(
      'type'   => 'fieldset',
      'legend' => __('Name and login information'),
      'submit' => 1,  // include submit button in this fieldset
    ),
	
	
   	
	'bew_user_first_name' => Array(
      'type'           => 'inputText',
      'displayname'    => __('Enter your first name'),
      'postfix'        => ' '.__("won't be displayed"),
      'validation'     => Array(
        Array( 'type'    => 'required' ),
      ),
    ),
	
	'bew_user_last_name' => Array(
      'type'           => 'inputText',
      'displayname'    => __('Enter your last name'),
      'postfix'        => ' '.__("won't be displayed"),
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



    'bew_user_nickname' => Array(
       'type'           => 'inputText',
      'displayname'    => __('Your Nickname'),
      'postfix'        => ' '.__("Will be displayed with your comments and submitted articles."),

      'validation'     => Array(
        Array( 'type'    => 'required' ),
      ),
    ),
	
	'fs1' => Array(
      'type'   => 'fieldset',
      'legend' => __('More about you'),
      'submit' => 1,  // include submit button in this fieldset
    ),
	
	'bew_user_description' => Array(
      'type'           => 'textArea',
      'displayname'    =>  __('Some words about you'),
      //'prefix'         => '...don\'t you?! (it\'s a <code>prefix</code>) ',
      'help'           => __('Can be displayed if you want.'),
     
    ),
	
	'fs0' => Array(
      'type'   => 'fieldset',
      'legend' => __('Your personnal information'),
      'submit' => 1,  // include submit button in this fieldset
    ),
	
	'bew_user_adu_user_adresse' => Array(
       'type'           => 'inputText',
      'displayname'    => __('Your Address'),
      'postfix'        => ' '.__("Won't be displayed"),

    ),
	'bew_user_adu_user_code_postal' => Array(
       'type'           => 'inputText',
      'displayname'    => __('Your Postal Code'),
      'postfix'        => ' '.__("Won't be displayed"),

    ),
	'bew_user_adu_user_ville' => Array(
       'type'           => 'inputText',
      'displayname'    => __('Your Town'),
      'postfix'        => ' '.__("Won't be displayed"),

    ),
	'bew_user_adu_user_pays' => Array(
       'type'           => 'inputText',
      'displayname'    => __('Your Country'),
      'postfix'        => ' '.__("Won't be displayed"),

    ),
	'bew_user_adu_user_telephone' => Array(
       'type'           => 'inputText',
      'displayname'    => __('Your Phone number'),
      'postfix'        => ' '.__("Won't be displayed"),

    ),
	'bew_user_adu_user_fax' => Array(
       'type'           => 'inputText',
      'displayname'    => __('Your Fax number'),
      'postfix'        => ' '.__("Won't be displayed"),

    ),
	'bew_user_adu_user_mobile' => Array(
       'type'           => 'inputText',
      'displayname'    => __('Your Cellphone number'),
      'postfix'        => ' '.__("Won't be displayed"),

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

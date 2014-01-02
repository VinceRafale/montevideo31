<?php

  $config = Array(

    /*'fs0' => Array(
      'type'   => 'fieldset',
      'legend' => 'inputText example',
      'submit' => 1,  // include submit button in this fieldset
    ),
	*/
	

	
	'user_email' => Array(
      'type'           => 'inputText',
      'displayname'    => __('Entrer votre Email'),
      'validation'     => Array(
       Array( 'type'    => 'wpRequiredEmail' ),
      ),
    ),
	
	'user_password' => Array(
      'type'           => 'inputPassword',
      'displayname'    => __('Entrer votre mot de passe'),
      //'postfix'        => ' and this is how a <code>postfix</code> looks like!',
      'validation'     => Array(
        Array( 'type'    => 'required' ),
      ),
    ),
	
	'remember' => Array(
      'type'           => 'inputCheckbox',
      'displayname'    =>  __('Se rappeler de moi sur cet ordinateur'),
      //'prefix'         => '...don\'t you?! (it\'s a <code>prefix</code>) ',
      'postfix'           => __('Ne pas cocher cette case si vous êtes sur un ordinateur public.'),
   
    ),
	
	
	'redirect' => Array(
      'type'     => 'inputHidden',
      'value'    => isset($_REQUEST['redirect']) ? urlencode($_REQUEST['redirect']) : ""
      
    ),
	
	
	



    

  );

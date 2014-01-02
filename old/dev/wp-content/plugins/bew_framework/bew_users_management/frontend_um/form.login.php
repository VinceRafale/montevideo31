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
      'displayname'    => __('Enter your email'),
      'validation'     => Array(
       Array( 'type'    => 'wpRequiredEmail' ),
      ),
    ),
	
	'user_password' => Array(
      'type'           => 'inputPassword',
      'displayname'    => __('Enter your password'),
      //'postfix'        => ' and this is how a <code>postfix</code> looks like!',
      'validation'     => Array(
        Array( 'type'    => 'required' ),
      ),
    ),
	
	'remember' => Array(
      'type'           => 'inputCheckbox',
      'displayname'    =>  __('Remember me on this computer'),
      //'prefix'         => '...don\'t you?! (it\'s a <code>prefix</code>) ',
      'postfix'           => __('Do not check this if you are on a public computer.'),
   
    ),
	
	
	'redirect' => Array(
      'type'     => 'inputHidden',
      'value'    => isset($_REQUEST['redirect']) ? urlencode($_REQUEST['redirect']) : ""
      
    ),
	
	
	



    

  );

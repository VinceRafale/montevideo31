<?php 

  $form_config = Array(  //encore mieux dans un fichie distinct de type form.nom_du_formulaire.php

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
        Array( 'type'    => 'wpRequiredUniqueEmail' ), // pour verifier que c'est un email, et qu'il est pas déja présent dans les users du blog. Sinon, pour véirifer que c'est un email, utiliser 'wpRequiredEmail'
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
        Array( 'type'    => 'requiredMatch', 'match_with' => 'user_pass' ),
      ),
    ),



    'acceptrules' => Array(
      'type'           => 'inputCheckbox',
      'displayname'    =>  __('disclaimer'),
      //'prefix'         => '...don\'t you?! (it\'s a <code>prefix</code>) ',
      'help'           => __('You have to accept the site rules to continue'),
      'validation'     => Array(
        Array( 'type'    => 'required' ),
      ),
    ),
	
	'redirect' => Array(
      'type'     => 'inputHidden',
      'value'    => isset($_REQUEST['redirect']) ? urlencode($_REQUEST['redirect']) : ""
      
    ),


  );
  
  

  
  			require_once('chemin_vers_le_fichier_form_classes.php');
  
  			$form = new clonefish( 'nom_du_formulaire', $_SERVER['REQUEST_URI'].'#content', 'POST' );	
			$form->submit = __('Register');
			$form->addelements( $form_config, $_POST, get_magic_quotes_gpc() );
			$form->js = 0;
			
			
			if ( count( $_POST ) && $form->validate() )
			{
			
				// Form is valid
				// ....
				
			} else {
			
				//display form
							
				echo $form->gethtml();
				
			}
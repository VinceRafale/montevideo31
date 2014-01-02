<?php

class wpRequiredEmailValidation extends validation {

  // -------------------------------------------------------------------------
  function getJSCode( ) {

    $code = '';
    $fieldvalue = $this->getJSField( $this->element ) . '.value';

    $code .= 
      'errors.addIf( \'' . $this->element->_gethtmlid() . '\', ' . $fieldvalue . '.match(/[\s]*/m) != 
        '. $fieldvalue . ', "' . 
        $this->_jsescape( sprintf(
          $this->selecthelp( $this->element, CF_STR_REQUIRED_TEXT ),
          $this->element->getDisplayName()
        ) ) .
      '" );' . "\n"
      ;

    return $this->injectDependencyJS( $code );

  }

  // -------------------------------------------------------------------------
  function isValid() {

    $results = Array();

    if ( $this->checkDependencyPHP() ) {

    if ( !strlen( trim( $this->element->getvalue( 0 ) ) ) ) {
        
      $message = 
        sprintf(
          $this->selecthelp( $this->element, CF_STR_REQUIRED_TEXT ),
          $this->element->getdisplayname()
        );
      $results[] = $message;
      $this->element->addmessage( $message );
      
      }elseif( ! is_email( trim($this->element->getvalue(0)) ) )
	  {
	  
	  	$message = 
        sprintf(
          $this->selecthelp( $this->element, __('Adresse email invalide ( format:  exemple@email.com )')),
          $this->element->getdisplayname()
        );
      $results[] = $message;
      $this->element->addmessage( $message );
	  
	  
	  }

    }

    return $results;

  }

} 

?>
<?php

class inputcheckboxRequired extends validation {

  // -------------------------------------------------------------------------
  function getJSCode( ) {

    $code = '';

    $code .= 
      'errors.addIf( \'' . $this->element->_gethtmlid() . '\', ' . 
        $this->getJSField( $this->element ) .
        '.checked, "' . 
        $this->_jsescape( sprintf( 
          $this->selecthelp( $this->element, CF_STR_REQUIRED_CHECKBOX ), 
          $this->element->getDisplayName() 
        ) ) . '" );' . "\n"
      ;

    return $this->injectDependencyJS( $code );

  }

  // -------------------------------------------------------------------------
  function isValid() {

    $results = Array();

    if ( $this->checkDependencyPHP() ) {
    
    if ( $this->element->getvalue( 0 ) != $this->element->onvalue ) {
      $message =
        sprintf(
          $this->selecthelp( $this->element, CF_STR_REQUIRED_CHECKBOX ),
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
<?php

class inputRadioRequired extends validation {

  // -------------------------------------------------------------------------
  function getJSCode() {

    $code = '';

    $code .=
      'errors.addIf( \'' . $this->element->_gethtmlid() . '\', ' . 
        'clonefishGetFieldValue( ' . 
          '"' . $this->form->name . '", ' . 
          '"' . $this->element->name. '", ' .
          '"' . $this->element->type . '"' . 
        '), "' . 
        $this->_jsescape( sprintf( 
          $this->selecthelp( $this->element, CF_STR_REQUIRED_RADIO ), 
          $this->element->getDisplayName() 
        ) ) . 
      '" );'."\n"
      ;

    return $this->injectDependencyJS( $code );

  }

  // -------------------------------------------------------------------------
  function isValid() {

    $results = Array();

    if ( $this->checkDependencyPHP() ) {

    if ( !isset( $this->element->values[ $this->element->getvalue( 0 ) ] ) ) {
      $message = 
        sprintf(
          $this->selecthelp( $this->element, CF_STR_REQUIRED_RADIO ),
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
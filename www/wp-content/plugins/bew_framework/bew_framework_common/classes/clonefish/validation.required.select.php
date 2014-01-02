<?php

class selectRequired extends validation {

  var $form;

  // -------------------------------------------------------------------------
  function selectRequired( $settings, &$element, &$form ) {

    // call parent constructor
    $parent_class_name = get_parent_class( $this );
    $this->$parent_class_name( $settings, $element );
    
    $this->form = &$form;
    
  }

  // -------------------------------------------------------------------------
  function getJSCode( ) {

    $code = '';

    $fieldname = $this->getJSField( $this->element );

    $code .= 
      'errors.addIf( \'' . $this->element->_gethtmlid() . '\', ' .
        '( clonefishGetFieldValue( ' . 
          '"' . $this->form->name . '", ' . 
          '"' . $this->element->name. '", ' .
          '"' . $this->element->type . '"' . 
        ') ) !== false, "' . 
        $this->_jsescape( sprintf( 
          $this->selecthelp( $this->element, CF_STR_REQUIRED_SELECT ), 
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

    $found     = false;
    $thisvalue = $this->element->getvalue( 0 );

    switch ( is_array( $thisvalue ) ) {

      // ARRAY VALUES OF THIS ELEMENT - FOR A MULTIPLE SELECT
      case true:

        foreach ( $thisvalue as $value ) {
          $found =
            $found ||
            isset( $this->element->values[ $value ] );
        }

        break;

      // SINGLE VALUE FOR A PLAIN SELECT
      default:
        $found = isset( $this->element->values[ $thisvalue ] );
        break;

    }

    if ( !$found ) {

      $message = 
        sprintf(
          $this->selecthelp( $this->element, CF_STR_REQUIRED_SELECT ),
          $this->element->getdisplayname()
        );
      $results[] = $message;
      $this->element->addmessage( $message );
      }

    }

    return $results;

  }

  // -------------------------------------------------------------------------
  function getJSField( &$element ) {
    return $this->form->getJSname() . '["' . $element->getrealname() . '"]';
  }

} 

?>
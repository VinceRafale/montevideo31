<?php

class select extends Element {

  var $value  = Array();
  var $values = Array();
  var $_valueIsArray = false;

  // --------------------------------------------------------------------------
  function select( $name, $configvalues ) {

    $this->name           = $name;

    foreach ( $configvalues as $key => $value ) 
      if ( $key != 'value' ) 
        $this->$key = $value;

    if ( !is_array( $this->values ) )
      die( sprintf( CF_ERR_VALUE_ARRAY_REQUIRED, $this->name ) );

    if ( isset( $configvalues['value'] ) ) 
      $this->setvalue( $configvalues['value'], 0 );

  }

  // --------------------------------------------------------------------------
  function setValue( $value, $magic_quotes_gpc ) {

    $value = $this->_prepare_input( $value, $magic_quotes_gpc );
    $this->value = Array();  

    if ( is_array( $value ) ) {

      foreach ( $value as $singlevalue )
        if ( array_key_exists( $singlevalue, $this->values ) )
          $this->value[] = $singlevalue;

      $this->_valueIsArray = true;

    }
    else {

      $this->_valueIsArray = false;
      if ( array_key_exists( $value, $this->values ) ) 
        $this->value[] = $value;
      else
        return false;
    
    }

    return true;

  }

  // -------------------------------------------------------------------------
  function getValue( $magic_quotes_gpc ) {

    switch ( count( $this->value ) ) {
      case 0: return null; break;
      case 1:
        if ( !$this->_valueIsArray ) {
          reset( $this->value );
          return $this->_prepare_output( current( $this->value ), $magic_quotes_gpc );
        }
        break;
    }

    return $this->_prepare_output( $this->value, $magic_quotes_gpc );

  }

  // --------------------------------------------------------------------------
  function getHTML() {

    $options = '';

    foreach ( $this->values as $key => $value ) 
      $options .= 
        '<option value="' . htmlspecialchars( $key ) . '">' . 
        htmlspecialchars( $value ) . 
        '</option>' . "\n";

    foreach ( $this->value as $value )
      if ( isset( $this->values[ $value ] ) )
        $options = str_replace(
          '<option value="' . htmlspecialchars( $value ) . '"',
          '<option selected="selected" value="' . htmlspecialchars( $value ) . '"',
          $options
        );

    return 
      '<select ' . $this->html .  
        ' id="' . $this->_gethtmlid() . '"' .
        ' name="' . $this->name . '">' . "\n" .
      $options .
      '</select>';

  }

  // -------------------------------------------------------------------------
  function getValueArray( $magic_quotes_gpc ) {

    if ( is_array( $this->value ) )
      return $this->_prepare_output( $this->value, $magic_quotes_gpc );
    else
      return $this->_prepare_output( Array( $this->value, $magic_quotes_gpc ) );
  
  }

}

?>
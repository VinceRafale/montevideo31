<?php

class inputCheckbox extends Element {

  var $onvalue  = 1;
  var $offvalue = 0;
  var $value;

  // -------------------------------------------------------------------------
  function inputCheckbox( $name, $configvalues ) {

    $this->name = $name;
    foreach ( $configvalues as $key => $value )
      $this->$key = $value;

  }

  // -------------------------------------------------------------------------
  function getHTML() {

    return
      '<input ' .
        'type="checkbox" ' .
        'name="' . $this->name . '" ' .
        'id="' . $this->_gethtmlid() . '" ' .
        'value="' . htmlspecialchars( $this->onvalue ) . '"' .
        (
          $this->getvalue( 0 ) == $this->onvalue ? ' checked="checked" '
          :
            ''
        ).
        ' ' . $this->html .
      ' />' . "\n";

  }                   

  // -------------------------------------------------------------------------
  function setValue( $value, $magic_quotes_gpc ) {

    // if there is no 'onvalue' defined, browsers send 'on' as default
    // value

    if (
         ( $this->_prepare_input( $value, $magic_quotes_gpc ) == $this->onvalue )
         ||
         ( $value === 'on' ) 
       ) {
      $this->value = $this->onvalue;
    }
    else {
      $this->value = $this->offvalue;
    }

    return true;

  }

  // -------------------------------------------------------------------------
  function getValue( $magic_quotes_gpc ) {

    if ( $this->value == $this->onvalue ) 
      $value = $this->onvalue;
    else
      $value = $this->offvalue;

    return $this->_prepare_output( $value, $magic_quotes_gpc );

  }

}

?>
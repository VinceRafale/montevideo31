<?php

class requiredValidation extends validation {

  // -------------------------------------------------------------------------
  function getJSField( $element ) {
    return $element->getname() . '.value';
  }

} 

?>
<?php

class inputPassword extends Element {

  // -------------------------------------------------------------------------
  function getHTML() {
    return 
      '<input ' .
        'type="password" ' .
        'name="' . $this->name . '" ' .
        'id="' . $this->_gethtmlid() . '" ' .
        'value="' . htmlspecialchars( $this->value ) . '" ' . 
        $this->html . 
      ' />';
  }

}

?>
<?php

class inputHidden extends Element {

  // -------------------------------------------------------------------------
  function getHTML() {
    return 
      '<input ' .
        'type="hidden" ' .
        'id="' . $this->_gethtmlid() . '" ' .
        'name="' . $this->name . '" ' .
        'value="' . htmlspecialchars( $this->value ) . '" ' . 
        $this->html . 
      ' />' . "\n";
  }

  function getHTMLRow() {
    return $this->getHTML();
  }

}

?>
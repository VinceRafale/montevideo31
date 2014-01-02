<?php

class textarea extends Element {

  // -------------------------------------------------------------------------
  function getHTML() {
    return 
      '<textarea ' . 
          'id="' . $this->_gethtmlid() . '" ' . 
          'name="' . $this->name . '" ' .
          $this->html .'>' .
        htmlspecialchars( $this->value ) . 
      '</textarea>';
  }

}

?>
<?php

class inputText extends Element {

  // -------------------------------------------------------------------------   
  function getHTML() {
     return 
       '<input ' .
         $this->html . ' ' .
         'type="text" ' .
         'name="' . $this->name . '" ' .
         'id="' . $this->_gethtmlid() . '" ' .
         'value="' . htmlspecialchars( $this->value ) . '" ' . 
       ' />';
   }

}

?>
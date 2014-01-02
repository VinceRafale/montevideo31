<?php

class inputMulticheckbox extends Element {

  var $onvalue  = 1;
  var $offvalue = 0;
  var $value;

  // -------------------------------------------------------------------------

  // -------------------------------------------------------------------------
  function getHTML() {
  
  	$i = 0;
	
	$html = '<div class="checkbox-options">';

    foreach($this->values as $key => $label){
			
			$i ++;
			
			$current_value = isset($_POST[$this->name]) ?  : htmlspecialchars( $this->offvalue );
			
			$el_id = $this->_gethtmlid().'-'. $i;
			
			$html.=  '<label for="'.$el_id.'"><input ' .
				'type="checkbox" ' .
				'name="' . $this->name . '[]" ' .
				'id="' . $el_id . '" ' .
				'value="' . htmlspecialchars( str_replace(' ', '-', $label) ) . '"' .
				(
				 (  isset($_POST[$this->name]) && isset($_POST[$this->name][$key])  ) ? ' checked="checked" '
				  :
					''
				).
				' ' . $this->html .
			  ' />' 
			  .htmlspecialchars($label)
			  .'</label>'			  
			  . "\n";
	}
	
	
	$html  .= '</div>';
	
	return $html;
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
<?php

class fieldset extends Element {
 
  var $legend;
  var $layout = "<fieldset %html% id=\"%id%\">\n<%legendtag%>%legend%</%legendtag%>\n%prefix%%content%%postfix%\n</fieldset>\n";
  var $legendtag = 'legend';
  var $submit = false;
  var $value = false; 
    // $value: counter for elements included in fieldset.
    // when false, every field will be included after the fieldset
    // element until the last element.
    // if set to a number, the given number of elements will be 
    // included in the fieldset.
  var $html;
  var $readonly = 1;
  
  // --------------------------------------------------------------------------
  function getHTMLRow() {

    $out = $this->layout;

    $out = str_replace('%id%',      $this->_gethtmlid(), $out );
    $out = str_replace('%html%',    $this->html, $out );
    
    if ( strlen( $this->legend ) ) {
      $out = str_replace('%legend%',  $this->legend, $out );
      $out = str_replace('%legendtag%', $this->legendtag, $out );
    }
    else {
      $out = str_replace('%legend%', '', $out );
      $out = str_replace('<%legendtag%>', '', $out );
      $out = str_replace('</%legendtag%>', '', $out );
    }
    
    $out = str_replace('%content%', '%s', $out );
    $out = str_replace('%prefix%',  $this->prefix, $out );
    $out = str_replace('%postfix%', $this->postfix, $out );

    return $out;

  }

}

?>
<?php

class element {

   var $displayname;
   var $errormessages = Array();
   var $name;
   var $value;
   var $rowlayout; // to override form row layout!
   var $validation = Array();
   var $html;
   var $prefix;
   var $postfix;
   var $htmlid;
   var $help;
   var $display = true;
   var $readonly = false;

   var $messagecontainerlayout = "%s<br />";
   var $messagelayout          = "%s<br />\n";

   var $validating = false;  // flag used to avoid recursive validation loops
   var $validated  = false;
   var $validationarray = Array();
   var $valid = null;

  // -------------------------------------------------------------------------
  function element( $name, $configvalues ) {

    $this->name           = $name;
    foreach ( $configvalues as $key => $value ) 
      $this->$key = $value;

  }
  
  // -------------------------------------------------------------------------
  function getValidationSettings() {

    if ( is_array( $this->validation ) )
      return $this->validation;
    else
      die( sprintf( CF_ERR_CONFIG_VALIDATION_IS_NOT_AN_ARRAY_OF_ARRAYS, $this->name ) ); 

  }

  // -------------------------------------------------------------------------
  function setValidator( $array ) {
    $this->validators = $array();
  }

  // -------------------------------------------------------------------------
  function getHTMLRow( $layout, $errorstyle, $showerroricon, $erroricon ) {

    $errormessages = '';

    if ( count( $errormessages ) ) {

      foreach ( $this->errormessages as $message ) 
        $errormessages .= sprintf( $this->messagelayout, $message );

      $errormessages = sprintf( 
        $this->messagecontainerlayout, $errormessages 
      );

    } 

    $replace = Array(
      '%displayname%' => $this->displayname,
      '%errorstyle%' => ( count( $this->errormessages ) ? $errorstyle : '' ),
      '%erroricon%' => ( $showerroricon && count( $this->errormessages ) ? $erroricon : '' ),
      '%prefix%' => $this->prefix,
      '%element%' => $this->gethtml(),
      '%message%' => $errormessages,
      '%postfix%' => $this->postfix,
      '%errordiv%' => 
        $this->form->jshtml == true ? 
        strtr( 
          $this->form->layouts[ $layout ]['errordiv'],
          Array( '%divid%' => 'cf_error' . $this->_gethtmlid() )
        ) : '',
      '%id%' => $this->_gethtmlid(),
    );

    $layoutused = $this->form->layouts[ $layout ]['element'];
    if ( $this->rowlayout ) 
      $layoutused = $this->rowlayout;
    if ( strtolower( $this->type ) == 'inputradio' )
      $layoutused = preg_replace('/<label.*>(.*)<\/label>/Uims', '\\1', $layoutused );

    if ( $this->display ) {
      $out = strtr( 
        $layoutused,
        $replace
      );
     
      // remove unnecessary label tag pairs
      if ( $this->form->layoutcleanup )
        $out = preg_replace('/<label[^>]*>\s*<\/label>/Uims', '', $out );

    }
    else
      $out = '';

    return $out;

  }

  // -------------------------------------------------------------------------
  function getHTML() {
  }

  // -------------------------------------------------------------------------
  function getType() {
    return trim( get_class( $this ) );
  }

  // -------------------------------------------------------------------------
  function getHelp() {
    return $this->help;
  }

  // -------------------------------------------------------------------------
  function getName() {

    // getname() is used by the clonefish class to find the
    // appropriate index in the incoming value array - that's why
    // we don't need the trailing [] in the name (an element
    // with a name like 'categories[]' will become an
    // array when the form is submitted: $_POST['categories']

    if ( substr( $this->name, strlen( $this->name ) - 2 , 2 ) == '[]' ) {
      return substr( $this->name, 0, strlen( $this->name ) - 2 );
    }
    else
      return $this->name;
  }

  // -------------------------------------------------------------------------
  function getRealName() {
    return $this->name;
  }
  
  // -------------------------------------------------------------------------
  function getDisplayName() {
    return $this->displayname;
  }

  // -------------------------------------------------------------------------
  function getValue( $magic_quotes_gpc ) {

    return $this->_prepare_output( $this->value, $magic_quotes_gpc );

  }

  // -------------------------------------------------------------------------
  function setValue( $value, $slashes_added ) {

    // if the second parameter is true, we have to strip the slashes

    if ( $slashes_added )
      $value = $this->_prepare_input( $value, $slashes_added );

    $this->value = $value; 

    return true;

  }

  // -------------------------------------------------------------------------
  function addMessage( $message ) {

    if ( !in_array( $message, $this->errormessages ) ) 
      $this->errormessages[] = $message;

  }

  // -------------------------------------------------------------------------
  function isEmpty() {
    
    if ( is_string( $this->value ) ) 
      return strlen( $this->value ) == 0;
    
    if ( is_array( $this->value ) ) 
      return count( $this->value ) == 0;

    // returns empty value to force
    // reimplementing in child classes
    // for other element types 
    return true;

  }

  // -------------------------------------------------------------------------
  function getMessages() {
    return $this->errormessages;
  }

  // -------------------------------------------------------------------------
  function _getHTMLId() {
    if ( strlen( $this->htmlid ) )
      return $this->htmlid;
    else
      return $this->name;
  }

  // -------------------------------------------------------------------------
  function _prepare_output( $array, $magic_quotes_gpc ) {

    if ( !$magic_quotes_gpc ) 
      // no need to add slashes for output
      return $array;

    if ( is_array( $array ) ) {
      foreach ( $array as $key => $value )
        if ( !is_array( $value ) )
          $array[ $key ] = addslashes( $value );
        else
          $array[ $key ] = $this->_prepare_output( $value, $magic_quotes_gpc );
      }
    else
      $array = addslashes( $array );

    return $array;

  }

  // -------------------------------------------------------------------------
  function _prepare_input( $array, $magic_quotes_gpc ) {

    if ( !$magic_quotes_gpc )
      // no need to strip slashes for input
      return $array;
    
    if ( is_array( $array ) ) {
      foreach ( $array as $key => $value )
        if ( !is_array( $value ) ) 
          $array[ $key ] = stripslashes( $value );
        else
          $array[ $key ] = $this->_prepare_input( $value, $magic_quotes_gpc );
      }
    else
      $array = stripslashes( $array );

    return $array;

  }

  // -------------------------------------------------------------------------
  function getScripts() {
     
    return '';
   
  }

  // -------------------------------------------------------------------------
  function getValidation( $type ) {

    foreach ( $this->validation as $validation )
      if ( $validation['type'] == $type )
        return $validation;

    return false;

  }

}

?>
<?php




class requiredMatchValidation extends validation {



  // -------------------------------------------------------------------------
 
  
  
   function isValid() {

    $results = Array();

    if ( $this->checkDependencyPHP() ) {

    if ( !strlen( trim( $this->element->getvalue( 0 ) ) ) ) {
        
      $message = 
        sprintf(
          $this->selecthelp( $this->element, CF_STR_REQUIRED_TEXT ),
          $this->element->getdisplayname()
        );
      $results[] = $message;
      $this->element->addmessage( $message );
      
      }elseif(  $_POST[$this->settings['match_with']] != $this->element->getvalue( 0 ) )
	  {
	  
	  	$message = 
        sprintf(
          $this->selecthelp( $this->element, __('The field \'%s\' does not match.') ),
          $this->element->getdisplayname()
        );
      $results[] = $message;
      $this->element->addmessage( $message );
	  
	  
	  }

    }

    return $results;

  }

} 

?>
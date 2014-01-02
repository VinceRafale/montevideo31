<?php


include_once('validation.wpRequiredEmail.php');

class wpRequiredUniqueEmailValidation extends wpRequiredEmailValidation {



  // -------------------------------------------------------------------------
  function isValid() {

    $results = parent::isValid();
	
	if( email_exists( trim($this->element->getvalue(0)) ) )
	
	  {
	  
	  	$message = 
        sprintf(
          $this->selecthelp( $this->element, __('This email is already taken.')." <a href='/login?lostpassword'>".__('Lost your password ?')."</a>" ),
          $this->element->getdisplayname()
        );
      $results[] = $message;
      $this->element->addmessage( $message );
	  
	  
	  }

    

    return $results;

  }

} 

?>
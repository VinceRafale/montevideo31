<?php


include_once('validation.wprequiredemail.php');

class wpRequiredUniqueEmailValidation extends wpRequiredEmailValidation {



  // -------------------------------------------------------------------------
  function isValid() {

    $results = parent::isValid();
	
	if( email_exists( trim($this->element->getvalue(0)) ) )
	
	  {
	  
	  	$message = 
        sprintf(
          $this->selecthelp( $this->element, "Cet email est d&#233;j&#224; utilis&#233; <a href='/login?lostpassword'>Mot de passe perdu ?</a>" ),
          $this->element->getdisplayname()
        );
      $results[] = $message;
      $this->element->addmessage( $message );
	  
	  
	  }

    

    return $results;

  }

} 

?>
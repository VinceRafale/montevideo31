<?php

define('UNITTESTING', true );
include_once( 'PHPUnit/Framework.php' );

include( '../clonefish.php' );
include( '../messages_en.php' );

class validatorTests extends PHPUnit_Framework_TestCase {
  protected $cf;
  
  protected function setUp() {

    $this->cf = new clonefish( 'test', 'validatorTests.php', 'POST');
    $this->cf->codepage = 'utf-8';
    
  }
  protected function tearDown() {
    
    $this->cf = null;

  }

}

?>
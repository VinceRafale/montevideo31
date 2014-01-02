<?php

define('UNITTESTING', true );
include_once( 'PHPUnit/Framework.php' );

include( '../clonefish.php' );
include( '../messages_en.php' );

class elementTests extends PHPUnit_Framework_TestCase {
  protected $cf;
  
  protected function setUp() {

    $this->cf = new clonefish( 'test', 'validatorTests.php', 'POST');
    $this->cf->codepage = 'utf-8';
    
  }
  protected function tearDown() {
    
    $this->cf = null;
    
  }
  
  public function commonInputProvider() {
    
    $notquoted = array(
      array('\'', false),
      array('"', false),
      array('\\', false),
      array("\0", false),
      array("\r\n", false),
    );
    
    $quoted = array();
    foreach ( $notquoted as $v ) {
      $quoted[] = array(
        addslashes( $v[0] ),
        true
      );
    }
    
    return array_merge( $notquoted, $quoted );
  }
  
}

?>
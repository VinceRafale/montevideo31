<?php
include('elementTests.php');
class elementInputRadio extends elementTests {

  /**
   * @dataProvider commonInputProvider
   *
   */
  public function testCommonInput( $value, $quote ) {
    
    $config = array(
      'test' => array(
        'type'        => 'inputRadio',
        'displayname' => 'inputradio',
        'values' => array(
          '\''   => '\'',
          '"'    => '"',
          '\\'   => '\\',
          "\0"   => "\0",
          "\r\n" => "\r\n",
        ),
      ),
    );
    
    $this->cf->addElements( $config, array( 'test' => $value ), $quote );
    $this->assertTrue( $this->cf->getValue('test', $quote ) === $value, 
      'expected: '. $value .' got: '. $this->cf->getValue('test', $quote ) );
    
  }
  
  public function testPrimitives( $quotes = false ) {
    
    $config = array(
      'test' => array(
        'type'        => 'inputRadio',
        'displayname' => 'inputradio',
        'values'      => array(
          'valid'          => 'valid',
          'thistooisvalid' => 'valid',
        ),
      ),
    );
    
    $this->cf->addElements( $config, array( 'test' => 'notvalid' ), $quotes );
    $this->assertFalse( $this->cf->getValue('test', $quotes ) === 'notvalid', 
      'inputradio invalid element shows up in getValue()' );
    $this->cf->removeElement('test');
    
    $this->cf->addElements( $config, array( 'test' => 'valid' ), $quotes );
    $this->assertTrue( $this->cf->getValue('test', $quotes ) === 'valid', 
      'inputradio good behaviour test failed');
    $this->cf->removeElement('test');
    
    if ( !$quotes )
      $this->testPrimitives( true );
  }
}
?>
<?php
include('elementTests.php');
class elementInputHidden extends elementTests {

  /**
   * @dataProvider commonInputProvider
   *
   */
  public function testCommonInput( $value, $quotes ) {
    
    $config = array(
      'test' => array(
        'type'        => 'inputHidden',
        'displayname' => 'hidden input',
        'value'       => $quotes ? stripslashes( $value ) : $value,
      ),
    );
    
    $this->cf->addElements( $config, array(), $quotes );
    $this->assertTrue( $this->cf->getValue('test', $quotes ) === $value, 
      'inputHidden getValue() differs from the input value, expected: '. $value .
      'got: '. $this->cf->getValue('test', $quotes ) );
    
  }
  
  public function testPrimitives( $quotes = false ) {

    $config = array(
      'test' => array(
        'type'        => 'inputHidden',
        'displayname' => 'hidden input',
        'value'       => 'valid',
        'readonly'    => false,
      ),
    );
    
    $this->cf->addElements( $config, array(), $quotes );
    $this->assertTrue( $this->cf->getValue('test', $quotes) === 'valid', 
      'inputHidden good behaviour validation failed' );
    $this->cf->removeElement('test');
    
    $config['test']['readonly'] = true;
    $this->cf->addElements( $config, array( 'test' => 'changed' ), $quotes );
    $this->assertFalse( $this->cf->getValue('test', $quotes) === 'changed', 
      'inputHidden readonly test failed' );
    $this->cf->removeElement('test');
    
    if ( !$quotes )
      $this->testPrimitives( true );
  }
}
?>
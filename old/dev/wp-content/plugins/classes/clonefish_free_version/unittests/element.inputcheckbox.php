<?php
include('elementTests.php');
class elementInputCheckbox extends elementTests {

  /**
   * @dataProvider commonInputProvider
   *
   */
  public function testCommonInput( $value, $quote ) {
    
    $config = array(
      'test' => array(
        'type'        => 'inputCheckbox',
        'displayname' => 'inputcheckbox',
        'onvalue'     => $quote ? stripslashes( $value ) : $value,
        'offvalue'    => 'n',
      ),
    );

    $this->cf->addElements( $config, array( 'test' => $value ), $quote );
    $this->assertTrue( $this->cf->getValue('test', $quote ) === $value, 
      'expected: '. $value .' got: '. $this->cf->getValue('test', $quote ) );
      
  }
  
  public function testPrimitives( $quotes = false ) {
    
    $config = array(
      'test' => array(
        'type'        => 'inputCheckbox',
        'displayname' => 'inputcheckbox',
        'onvalue'     => 'defined',
        'offvalue'    => 'whateva',
        'value'       => 'whateva',
      ),
    );
    
    $this->cf->addElements( $config, array( 'test' => 'defined' ), $quotes );
    $this->assertTrue( $this->cf->getValue('test', $quotes ) === 'defined',
      'inputCheckbox good behaviour validation failed' );
    $this->cf->removeElement('test');
    
    $this->cf->addElements( $config, array( 'test' => 'randomgarbage' ), $quotes );
    $this->assertFalse( $this->cf->getValue('test', $quotes ) === 'randomgarbage', 
      'inputCheckbox invalid value shows up in getValue()' );
    $this->cf->removeElement('test');
    
    $this->cf->addElements( $config, array( 'test' => '' ), $quotes );
    $this->assertTrue( $this->cf->getValue('test', $quotes ) === 'whateva', 
      'inputCheckbox empty value should mean offvalue' );
    $this->cf->removeElement('test');

    $this->cf->addElements( $config, array( 'test' => 'on' ), $quotes );
    $this->assertFalse( $this->cf->getValue('test', $quotes ) === 'on', 
      'inputCheckbox onvalue defined, "on" shouldnt be valid' );
    $this->cf->removeElement('test');

    unset( $config['test']['onvalue'] );
    $this->cf->addElements( $config, array( 'test' => 'on' ), $quotes );
    $this->assertTrue( $this->cf->getValue('test', $quotes ) == true,
      'inputCheckbox onvalue not defined, "on" should be valid got: '. $this->cf->getValue('test', $quotes ) );
    $this->cf->removeElement('test');
    
    if ( !$quotes )
      $this->testPrimitives( true );
  }
}
?>
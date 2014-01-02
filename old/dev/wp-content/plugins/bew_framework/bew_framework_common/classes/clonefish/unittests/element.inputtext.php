<?php
include('elementTests.php');
class elementInputText extends elementTests {

  /**
   * @dataProvider commonInputProvider
   *
   */
  public function testCommonInput( $value, $quote ) {
    
    $config = array(
      'test' => array(
        'type'        => 'inputText',
        'displayname' => 'inputtext validation hai',
      ),
    );
    
    $this->cf->addElements( $config, array( 'test' => $value ), $quote );
    $this->assertTrue( $this->cf->getValue('test', $quote ) === $value );
    
  }
  
  public function testPrimitives( $quotes = false ) {
    
    $config = array(
      'test' => array(
        'type'        => 'inputText',
        'displayname' => 'inputtext validation hai',
        'readonly'    => true,
        'value'       => 'presetvalue',
      )
    );
    
    $this->cf->addElements( $config, array( 'test' => 'notpresetvalue' ), $quotes );
    $this->assertTrue( $this->cf->getValue('test', $quotes ) === 'presetvalue' );
    $this->cf->removeElement('test');
    
    $config = array(
    
      'test["000"]' => array(
        'type'        => 'inputText',
        'displayname' => 'inputtext validation hai',
        'value'       => 'presetvalue',
      ),
      
      'test["001"]' => array(
        'type'        => 'inputText',
        'displayname' => 'inputtext validation2 hai',
        'value'       => 'presetvalue2',
      ),
      
    );
    
    $this->cf->addElements( $config, array(), $quotes );
    $this->assertTrue( $this->cf->getValue('test["000"]', $quotes ) === 'presetvalue' );
    $this->assertTrue( $this->cf->getValue('test["001"]', $quotes ) === 'presetvalue2' );
    $this->cf->removeElement('test["000"]');
    $this->cf->removeElement('test["001"]');
    
    if ( !$quotes )
      $this->testPrimitives( true );
    
  }
}
?>
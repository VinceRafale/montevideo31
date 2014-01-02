<?php
include('elementTests.php');
class elementTextarea extends elementTests {

  /**
   * @dataProvider commonInputProvider
   *
   */
  public function testCommonInput( $value, $quote ) {
    
    $config = array(
      'test' => array(
        'type'        => 'textarea',
        'displayname' => 'textarea validation hai',
      ),
    );
    
    $this->cf->addElements( $config, array( 'test' => $value ), $quote );
    $this->assertTrue( $this->cf->getValue('test', $quote ) === $value, 
      'expected: '. $value .' got: '. $this->cf->getValue('test', $quote ) );
    
  }
  
}
?>
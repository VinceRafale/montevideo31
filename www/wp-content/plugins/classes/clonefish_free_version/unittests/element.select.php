<?php
include('elementTests.php');
class elementSelect extends elementTests {

  /**
   * @dataProvider commonInputProvider
   *
   */
  public function testCommonInput( $value, $quote ) {
    
    $config = array(
      'test' => array(
        'type'        => 'select',
        'displayname' => 'select',
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
      'multiple[]' => array(
        'type'        => 'select',
        'displayname' => 'multiselect',
        'values'      => array(
          0 => 'elso',
          1 => 'masodik',
          2 => 'harmadik',
        ),
        'value'       => '0',
        'readonly'    => false,
      ),
    );
    
    $this->cf->addElements( $config, array( 'multiple' => array( 0, 1, 2, 3 ) ), $quotes );
    $val = $this->cf->getElementByName('multiple[]')->getValueArray( $quotes );
    $this->assertFalse( @$val[3] == 3, 'select extra value shows up' );
    $this->cf->removeElement('multiple[]');
    
    $config['multiple[]']['readonly'] = true;
    $this->cf->addElements( $config, array( 'multiple' => array( 0, 1, 2 ) ), $quotes );
    $val = $this->cf->getElementByName('multiple[]')->getValueArray( $quotes );
    $this->assertFalse( @$val[1] == 1, 
      'select readonly atribute disregarded, got: '. print_r( $val, true) );
    $this->cf->removeElement('multiple[]');
    
    if ( !$quotes )
      $this->testPrimitives( true );
  }
}
?>
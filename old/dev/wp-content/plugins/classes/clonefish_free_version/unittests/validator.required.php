<?php
include('validatorTests.php');
class validatorRequired extends validatorTests {

  // --------------------------------------------------------------------------
  public function requiredProvider() {
    
    $req = array( 
      'reqvalid' => array(
        'type'        => 'inputText',
        'displayname' => 'reqvalidation',
        'validation'  => array(
          array(
            'type' => 'required',
          ),
        ),
      ),
    );
    
    $notquoted = array(
    
      array(
        $req,
        array('reqvalid' => "\0"),
        false
      ),
      
      array(
        $req,
        array('reqvalid' => ""),
        false
      ),
      
    );
    
    $quoted = array();
    foreach ( $notquoted as $v ) {
      $quoted[] = array(
        $req,
        array('reqvalid' => addslashes( $v[1]['reqvalid'] ) ),
        true
      );
    }
    
    return array_merge( $notquoted, $quoted );
  }
  /**
   * @dataProvider requiredProvider
   *
   */
  public function testRequired( $config, $values, $quotes ) {

    $this->cf->addElements( $config, $values, $quotes );
    $this->assertFalse( $this->cf->validate(), 'req validation succeeded with: '.
    $values['reqvalid'] . ', quote status: ' . (int)$quotes );
    
  }
}
?>
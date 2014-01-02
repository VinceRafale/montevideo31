<?php


class dstfp_view_submit {

	function __construct(){
	
		$this->errors = array();
	
	}

	function gethtml(){
		
		ob_start();
		
		?>
        
        <div class='dst-form'> 
            <div id='respond'>
                <?php 
                
                foreach( $this->errors as $key => $value){
                
                    echo "<div class='errors'><div class='error'>".$value."</div></div>";
                
                }
                
                ?>
                <p class="content_message"><?php echo $this->content; ?></p>
                
            </div>
        </div>
        
		<?php
		
		return ob_get_clean();
	
	}

}
<?php



class dstpa_view_contact {

	public $post_id;
	public $title;
	public $errors;
	public $content;
	
	function __construct(){
	
		$this->title = __("Contacter l'auteur", 'dstpa');
		$this->errors = array();
	
	
	}

	function display_html(){
		
		ob_start();
		
		?>
        
        		<div class='dst-form'> 
                    <div id='respond'>
                        <h3 id='reply-title'><?php echo $this->title;  ?></h3>
                        <p><?php printf(__("Contacter l'auteur &agrave; propos de l'annonce n&deg; %d", 'dstpa'), $this->post_id ); ?></p>
            			<?php 
						
						foreach( $this->errors as $key => $value){
						
							echo "<div class='errors'><div class='error'>".$value."</div></div>";
						
						}
						
						?>
                        <p class="content_message"><?php echo $this->content; ?></p>
                    	
                    </div>
                </div>   
        
        
        
        <?php
	
	}

}
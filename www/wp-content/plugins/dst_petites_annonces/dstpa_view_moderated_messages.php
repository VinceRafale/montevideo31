<?php


class dstpa_view_moderated_messages {
	
	public $messages;
	public $errors;
	
	function __construct(){
	
		$this->messages = array();
		$this->errors = array();
	
	}
	
	
	function js(){
	
		ob_start();
		
		?>
        
        <script type="text/javascript">
		
		jQuery(document).ready(function(){
		
			jQuery('.dstpa_ajax_action').click(function(){
				
				jQuery('.dstpa_ajax_action').parent().parent().parent().parent().parent().addClass('dstpa_action');
				
				url = jQuery(this).attr('href') + '&dstpa_ajax=1';
			
				jQuery(this).parent().parent().parent().parent().parent().html("<img src='../wp-content/plugins/dst_plugins/dst_petites_annonces/images/ajax-loader.gif' class='dstpa_autoload'>").load( url );
					
				jQuery('.dstpa_ajax_action').parent().parent().parent().parent().parent().removeClass('dstpa_action');
					
				return false;
				
			});
			
		});
		
		</script>
        
        
        <?php
		
		return ob_get_clean();
	
	}
	
	
	function get_html(){
	
	ob_start();
	
	echo $this->js();
	
	?>
    <style type="text/css">
	
	.dstpa_autoload {
		margin: 5px 0 5px 10px;
	}
	
	#moderated_messages .inside {
		margin-top:0;
	}
	
	.inside #the-comment-list .dstpa_action {
		padding: 0;
		font-size:11px;
	}
	
	.inside #the-comment-list .dstpa_action .spam-undo-inside{
		margin: 1px 8px 1px 8px;
	}
	
	#the-comment-list .comment-item {
		padding: 5px 10px;
	}
	
	</style>
    
	<div class="list:comment dstpa" id="the-comment-list">
    	<?php
		
		if(is_array($this->errors) && sizeof($this->errors) > 0){
		
			foreach($this->errors as $v){
			
				echo "<p style='color: ff0000; font-weight: bold;'>".$v."</p>";
			
			}
		
		}
		
		?>
		
		
        <?php 
		
		foreach($this->messages as $v){
		
			$this->display_message_row($v); 
			
		}
		
		?>
        	
     	
    </div>        
    <?php
	
	return ob_get_clean();
	
	}
	
	function display_message_row( $message ){
	
	
		$message_meta_value = unserialize($message->meta_value);
		
		
		?>
        
        <div class="comment even thread-even depth-1 comment-item unapproved" id="comment-42">
			<div class="comment-item">
                <div class="dashboard-comment-wrap">
                    <h4 class="comment-meta">
                    
                        <?php _e('By ', 'dstpa'); ?> 
                        <cite class="comment-author">
                            <?php echo $message_meta_value['contact_name']; ?> | <?php echo $message_meta_value['email']; ?>
                        </cite>
                        <span class="approve" style="font-family:'Lucida Grande',Verdana,Arial,'Bitstream Vera Sans',sans-serif; font-size:10px; font-style:italic;">[<?php _e('Pending', 'dstpa'); ?>]</span>	
                    </h4>
                    
                    <blockquote><p>
                        <?php echo $message_meta_value['message']; ?>
                    </p></blockquote>
                    
                    <p class="row-actions">
                        <span class="approve">
                        <a class="dstpa_ajax_action" title="<?php _e('Approve this message', 'dstpa'); ?>" class="dst_petites_annonces" href="/wp-admin/?approve_dstpa=<?php echo $message->meta_id; ?>"><?php _e('Approve', 'dstpa'); ?></a>  
                        </span>
                        
                        <span class="trash"> | 
                        <a class="dstpa_ajax_action" title="<?php _e('Delete this message', 'dstpa'); ?>" class="dst_petites_annonces" href="/wp-admin/?delete_dstpa=<?php echo $message->meta_id; ?>" name="delete"><?php _e('Delete', 'dstpa'); ?></a> 
                        </span>
                    </p>
                </div> 
			</div>
      	</div>
        
		<?php 
	}

}
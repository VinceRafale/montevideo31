<?php

	if(!function_exists('bew_admin_message')){

function bew_admin_message($text, $class){

	session_start();
	$_SESSION['bew_admin_messages'][$text] = $class;
	
	bew_message($text, $class);
	

	
	

}

function bew_render_admin_messages(){

	session_start();
	
	if(is_array($_SESSION['bew_admin_messages']))
	
	foreach($_SESSION['bew_admin_messages'] as $t => $c)
	
	echo "<div class='$c'><p>".__($t)."</p></div>";
	
	unset($_SESSION['bew_admin_messages']);
	unset($_SESSION['bew_messages']);

}

add_action('admin_notices', 'bew_render_admin_messages');

function bew_message($text, $class){

	session_start();
	$_SESSION['bew_messages'][$text] = $class;
	

	
	

}

function bew_render_messages(){

	session_start();
	
	if(!is_array($_SESSION['bew_messages'])) return;
	
	$url = $_SERVER['REQUEST_URI'];
	
	$onclick = ' jQuery(this).parent().slideUp("fast"); return false; ';
	
	$a = "<a href='$url' class='bew_message_close' onclick='$onclick'>".__('Close')."</a>";
	
	?>

    
    
    <?php
	
	echo "<div id='bew_messages'>";
	
	foreach($_SESSION['bew_messages'] as $t => $c) echo "<div class='$c'><span class='icon png24'>&nbsp;</span>".__($t)." &nbsp;&nbsp;$a</div>";
	
	echo "</div>";
	
	?>
    
    	<script type="text/javascript">
			
			

			  
		   	jQuery('#bew_messages div a.bew_message_close').click(function(){
			
			
				jQuery('#bew_messages').slideUp('fast');
		   
		   
		   	});
		
    
    	</script>
        
        
    <?php 
	
	unset($_SESSION['bew_messages']);
}

add_action('bew_messages', 'bew_render_messages');


}
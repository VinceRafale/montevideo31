<?php


function dstpa_setting_number_messages() {
 	
	if (get_option('dstpa_setting_number')) {
	
	 	$number = get_option('dstpa_setting_number'); 
		$time = get_option('dstpa_setting_time'); 
		
		$time = ($time / 60) / 60;
	
	}
	else {
	
		$number = 20;
		$time = 24;
		
	}

	ob_start; ?>
	
    <div class='icon32' id='icon-edit'><br></div>
		<div class='wrap'>
			<h2><?php _e('Params', 'dstpa'); ?></h2>
			<form action='edit.php?post_type=petite_annonce&page=params-page' method='post' name='form1'>
			<p>
				<?php _e("Indicate the number of messages allowed to post ", 'dstpa'); ?>
                <input name='dstpa_setting_number' id='dstpa_number_authorised_messages' type='text' value="<?php echo $number; ?>" size="3" />
                
                <?php _e(" during an interval of ", 'dstpa'); ?>
                <input name='dstpa_setting_time' id='dstpa_number_authorised_messages' type='text' value="<?php echo $time; ?>" size="3" />
                
                <?php _e(" hours."); ?>
            </p>
			<p class='submit'>
				<input type='submit' value="<?php _e('Save modifications', 'dstpa'); ?>" class='button-primary' name='Submit'>
			</p>
			
			</form>
		</div>
    
    <?php

} 
  

if( isset($_POST['dstpa_setting_number']) && !empty($_POST['dstpa_setting_number']) && isset($_POST['dstpa_setting_time']) && !empty($_POST['dstpa_setting_number']) ) dstpa_insert_number_messages( $_POST['dstpa_setting_number'], $_POST['dstpa_setting_time'] );

function dstpa_insert_number_messages( $number, $time ) {
 
	$time = ($time * 60) * 60;
	
	if(get_option('dstpa_setting_number')) {
	
		update_option( 'dstpa_setting_number', (int)$number );
		update_option( 'dstpa_setting_time', $time );
		
	}
	else {
	
		add_option( 'dstpa_setting_number', (int)$number, $deprecated = '', $autoload = 'yes' );
		add_option( 'dstpa_setting_time', $time, $deprecated = '', $autoload = 'yes' );
	
	}

}


add_action('admin_menu', 'dstpa_add_submenu_params');

function dstpa_add_submenu_params(){
	
	add_submenu_page('edit.php?post_type=petite_annonce', __('Params', 'dstpa'), __('Params', 'dstpa'), 'manage_options', 'params-page', 'dstpa_setting_number_messages');

	
}
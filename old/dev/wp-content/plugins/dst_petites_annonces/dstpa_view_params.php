<?php


function dstpa_setting_number_messages() {
 	
	if (get_option('dstpa_setting_number')) 
		$number = get_option('dstpa_setting_number'); 
	else
		$number = 20;
		
	if (get_option('dstpa_setting_time')) {
		$time = get_option('dstpa_setting_time');
		$time = ($time / 60) / 60;
	}
	else
	 	$time = 24;
		
	if (get_option('dstpa_from_email'))
		$email = get_option('dstpa_from_email');
	else 
		$email = get_option('admin_email');
		
	if (get_option('dstpa_from_alert_email'))
		$alert_email = get_option('dstpa_from_alert_email');
	else 
		$alert_email = get_option('admin_email');
		
	ob_start; ?>
	
    <div class='icon32' id='icon-edit'><br></div>
		<div class='wrap'>
			<h2><?php _e('Param&ecirc;tres'); ?></h2>
			<form action='edit.php?post_type=petite_annonce&page=params-page' method='post' name='form1'>
			<p>
				<?php _e("Indicate the number of messages allowed to post ", 'dstpa'); ?>
                <input name='dstpa_setting_number' id='dstpa_number_authorised_messages' type='text' value="<?php echo $number; ?>" size="3" />
                
                <?php _e(" during an interval of  ", 'dstpa') ?>
                <input name='dstpa_setting_time' id='dstpa_number_authorised_messages' type='text' value="<?php echo $time; ?>" size="3" />
                
                <?php _e(" hours."); ?>
            </p>
            <p>
            	<?php _e("Indicate the email from contact for the messages", 'dstpa') ?>
                <input name='dstpa_from_email' id='dstpa_from_email' type='text' value="<?php echo $email; ?>" />
            </p>
             <p>
            	<?php _e("Email alert for the new pending personnals columns notification", 'dstpa') ?>
                <input name='dstpa_from_alert_email' id='dstpa_from_alert_email' type='text' value="<?php echo $alert_email; ?>" />
            </p>
			<p class='submit'>
				<input type='submit' value="<?php _e('Save modifications', 'dstpa') ?>" class='button-primary' name='Submit'>
			</p>
			
			</form>
		</div>
    
    <?php

} 


if( isset($_POST['dstpa_setting_number']) && !empty($_POST['dstpa_setting_number']) && isset($_POST['dstpa_setting_time']) && !empty($_POST['dstpa_setting_number']) ) dstpa_insert_number_messages( $_POST['dstpa_setting_number'], $_POST['dstpa_setting_time'], $_POST['dstpa_from_email'], $_POST['dstpa_from_alert_email'] );

function dstpa_insert_number_messages( $number, $time, $email, $alert_email ) { 

	$time = ($time * 60) * 60;
	
	if(get_option('dstpa_setting_number')) {
	
		update_option( 'dstpa_setting_number', (int)$number );
		update_option( 'dstpa_setting_time', $time );
		update_option( 'dstpa_from_email', $email );
		update_option( 'dstpa_from_alert_email', $alert_email );
		
	}
	else {
	
		add_option( 'dstpa_setting_number', (int)$number, $deprecated = '', $autoload = 'yes' );
		add_option( 'dstpa_setting_time', $time, $deprecated = '', $autoload = 'yes' );
		add_option( 'dstpa_from_email', $email );
		add_option( 'dstpa_from_alert_email', $alert_email );
	
	}

}


add_action('admin_menu', 'dstpa_add_submenu_params');

function dstpa_add_submenu_params(){
	
	add_submenu_page('edit.php?post_type=petite_annonce', __('Param&egrave;tres', 'params'), __('Param&egrave;tres'), 'manage_options', 'params-page', 'dstpa_setting_number_messages');

	
}
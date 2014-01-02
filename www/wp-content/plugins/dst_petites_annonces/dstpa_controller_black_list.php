<?php


add_action('admin_menu', 'dstpa_add_submenu_black_list');
	
function dstpa_add_submenu_black_list(){
	
	add_submenu_page('edit.php?post_type=petite_annonce', __('Forbidden words', 'dstpa'), __('Forbidden words', 'dstpa'), 'manage_options', 'blacklist-page', 'dstpa_setting_black_list');
	
}

function dstpa_setting_black_list(){

	if (get_option('dstpa_setting_blacklist')) 
		$blacklist = get_option('dstpa_setting_blacklist'); 
		
	else
		$blacklist = '';
		
	ob_start; ?>
	
    <div class='icon32' id='icon-edit'><br></div>
		<div class='wrap'>
			<h2><?php _e('Forbidden words', 'dstpa'); ?></h2>
			<form action='edit.php?post_type=petite_annonce&page=blacklist-page' method='post' name='form1'>
            
			<p><?php _e("Forbidden words in contact message (separate with space)", 'dstpa'); ?></p>
            <textarea name='dstpa_setting_blacklist' id='dstpa_blacklist' rows="8" cols="100%"><?php echo $blacklist; ?></textarea>
           
			<p class='submit'>
				<input type='submit' value="<?php _e('Save modifications', 'dstpa'); ?>" class='button-primary' name='Submit'>
			</p>
			
			</form>
		</div>
    
    <?php
	
}


if( isset($_POST['dstpa_setting_blacklist']) && !empty($_POST['dstpa_setting_blacklist']) ) dstpa_insert_black_list( $_POST['dstpa_setting_blacklist'] );

function dstpa_insert_black_list( $blacklist ) {

	if(get_option('dstpa_setting_blacklist'))
		update_option( 'dstpa_setting_blacklist', $blacklist );  
	
	else 
	
		add_option( 'dstpa_setting_blacklist', $blacklist, $deprecated = '', $autoload = 'yes' );

}
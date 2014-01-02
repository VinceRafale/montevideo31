<?php 
ini_set("display_errors",1);
/*
Plugin Name: BEW Framework - Mailer
Gestion des mailer Email et SMS

Version: 0.1
*/


	////// INCLUDES


		require_once(dirname(realpath(__FILE__)).'/bew_mailer/class.bew_mailer.php');
		require_once(dirname(realpath(__FILE__)).'/bew_mailer/cron.php');
	
	
	
	
	
	////// ACTIVATION HOOK
		
		
		global $bew_mailer_db_version;
		$bew_mailer_db_version = "1.0";
		
	
		global $bew_mailer_table ; 
		$bew_mailer_table = "bew_mailer_queue";

		function bew_mailer_install(){
				   global $wpdb;
				   global $bew_mailer_db_version;
				   global $bew_mailer_table ;
		            $table_name = $wpdb->prefix .  $bew_mailer_table ;
				   if($wpdb->get_var("show tables like '$table_name'") != $table_name) {
					  
					  $sql = "CREATE TABLE " . $table_name . " (
					  
								`ID` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
								`date_entered` BIGINT NOT NULL ,
								`user_id` VARCHAR( 10 ) NOT NULL ,
								`user_emails` VARCHAR( 255 ) NOT NULL ,
								`subject` VARCHAR( 255 ) NOT NULL ,
								`body_text` TEXT NOT NULL ,
								`body_html` TEXT NOT NULL ,
								`send_on` BIGINT NOT NULL ,
								`send_attempts` VARCHAR( 10 ) NOT NULL ,
								`additionnal_headers` TEXT NOT NULL ,
								`meta` TEXT NOT NULL ,
								`post_id` VARCHAR( 10 ) NOT NULL ,
								`errors` TEXT NOT NULL 
								
						);";
						
				
					  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
					  dbDelta($sql);
					 
					  add_option("bew_mailer_db_version", $bew_mailer_db_version);
				
				   }
		}
		register_activation_hook(__FILE__, 'bew_mailer_install');
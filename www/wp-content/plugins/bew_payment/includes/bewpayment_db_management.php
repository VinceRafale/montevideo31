<?php

class bew_payment_model{
	
	function bew_payment_model(){
		global $wpdb;
		
		$table = $wpdb->prefix."bew_payment_products";
		
		// (re)write the table name in wp options database
		update_option('bew_payment_products_table_name', $table); 
		
		// if the database doesn't exists, create it
		if($wpdb->get_var("show tables like '$table'") != $table) {
			$structure = "CREATE TABLE  $table (
			 	`id` BIGINT( 11 ) NOT NULL AUTO_INCREMENT ,
				`name` VARCHAR(100) CHARACTER SET utf8 NOT NULL ,
				`type` ENUM(  '".bp_enum_value_subscription."', '".bp_enum_value_donation."', '".bp_enum_value_classic."' ) NOT NULL,
				`price` INT NOT NULL,
				`cerfa` TINYINT(1) NOT NULL,
				`group_id` VARCHAR(100) CHARACTER SET utf8 NOT NULL ,
				`archive` TINYINT(1) NOT NULL,
				`tickets` INT NOT NULL,
				PRIMARY KEY (`id`)
				);";
			$wpdb->query($structure);
		}		
		
		$table = $wpdb->prefix."bew_payment_logs";
		
		// (re)write the table name in wp options database
		update_option('bew_payment_logs_table_name', $table); 
		
		// if the database doesn't exists, create it
		if($wpdb->get_var("show tables like '$table'") != $table) {
			$structure = "CREATE TABLE  $table (
			
				  `id` bigint(11) NOT NULL auto_increment,
				  `user_id` bigint(11) NOT NULL,
				  `product_id` bigint(11) NOT NULL,
				  `amount` int(11) NOT NULL,
				  `payment_date` datetime NOT NULL,
				  `atos_customer_id` varchar(20) NOT NULL,
				  `atos_transaction_id` varchar(20) NOT NULL,
				  PRIMARY KEY (`id`)
				);";
			$wpdb->query($structure);
		}	
		
		$table = $wpdb->prefix."bew_payment_reservations";
		update_option('bew_payment_reservations', $table); 
		
		// (re)write the table name in wp options database
		update_option('bew_payment_reservation_table_name', $table); 
		
		// if the database doesn't exists, create it
		if($wpdb->get_var("show tables like '$table'") != $table) {
			$structure = "CREATE TABLE  $table (
			
				  `id` bigint(11) NOT NULL auto_increment,
				  `product_id` bigint(11) NOT NULL,
				  `tickets` int(11) NOT NULL,
				  `name` varchar(80) NOT NULL,
				  `email` varchar(80) NOT NULL,
				  
				  PRIMARY KEY (`id`)
				);";
			$wpdb->query($structure);
		}	
		
		
				
		
		
		
		
		
	}
	

}


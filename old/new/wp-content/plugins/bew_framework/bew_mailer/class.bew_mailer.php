<?php

if(!class_exists('BEW_Mailer')){

	class BEW_Mailer{
	
		private $data;
		
		function insert_email($args){
		
			$defaults = array(
			
				"date_entered" => time(),
				"user_id" => 0,		
				"user_emails" => false,
				"subject" => false,
				"body_text" => '',
				"body_html" => '',
				"send_on" => time(),
				"additionnal_headers" => '',
				"meta" => false,
				"post_id" => false,
				"errors" => '',
				
			);
			
			$this->data = wp_parse_args($args, $defaults);
			
			global $bew_mailer_table ;
			global $wpdb ;
            return  $wpdb->insert( $wpdb->prefix.$bew_mailer_table, $this->data );

			
					
		}// end insert mail
		
			
	}// end class
	
}// end if

?>
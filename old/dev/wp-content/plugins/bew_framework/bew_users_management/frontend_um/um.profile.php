<?php
//do_action('profile_personal_options', $profileuser);
	


	class bew_frontend_um_profile extends bew_frontend_um
	{
	
	
		public $form;
		
		
		function __construct(){
			
			parent::__construct();
			
			if(!$this->logged_in){
			
				$this->disable_form = true;
			
			 	return $this->errors[] = __('<strong>ERROR:</strong>You must be logged in to access this page. ');
			}
			
			require_once( ABSPATH . WPINC . '/registration.php');
			
			$this->load_form_classes();
			
			include( 'form.profile.php' );
			
			if(!isset($_POST['change'])) $config = $this->load_user_info($config);
			
			$this->form = new clonefish( 'input', $_SERVER['REQUEST_URI'].'#content', 'POST' );	
			
			$this->form->submit = __('Save');
			
			$this->form->addelements( $config, $_POST, get_magic_quotes_gpc() );
			$this->form->js = 0;
			
	
			
			
					
		}
	
		function validate(){
		
			
			
			if ( isset( $_POST['change'] ) && $this->form->validate() )
			{
				
				
				
					if($this->save_user_meta()) 					
					
						$this->messages[] = __('Your profile has successfully been modified.');
					
					else $this->errors[] = __('Error saving your profile, please contact website administration.');
					
		
					
				
	
				
			}
			
			
		
		}
		
		function save_user_meta(){
		
			global $current_user;
		
			$success = true;
		
			foreach ($_POST as $k => $v){
			
				
					if(!stristr($k, 'bew_user_')) continue;
					
					update_user_meta($current_user->ID, str_replace('bew_user_', '', $k), strip_tags($v));
			
			
			}
			
			return $success;
		
		}
		
		function load_user_info($config){
		
			global $current_user;
		
			foreach($config as $k => $v){
			
			
				if($k == 'new_user_email') $config[$k]['value'] = $current_user->user_email;
				{
			
					$value = get_user_meta($current_user->ID, str_replace('bew_user_', '', $k), true);
							
					if($value) $config[$k]['value'] = $value;
				
				}
			
			}
			
			return $config;
		
				
		
		
		}
		
		function render_core(){
		
		
		

			if(!$this->disable_form) $this->html .= $this->form->gethtml();
				
		
		
		
		}
	
	}
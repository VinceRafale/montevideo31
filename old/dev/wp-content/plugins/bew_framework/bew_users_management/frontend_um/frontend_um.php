<?php


	include(dirname(realpath(__FILE__)).'/um.register.php');
	include(dirname(realpath(__FILE__)).'/um.login.php');
	include(dirname(realpath(__FILE__)).'/um.profile.php');
	
	
	
	
	//// Abstraction class for user management.
	
	class bew_frontend_um {
	
			
		public $logged_in;
		public $messages;
		public $errors;
		public $html;
		public $validate;
		public $disable_form;
		
		function __construct(){
		
		
			
		
			global $current_user;
			$this->logged_in = $current_user->ID > 0 ? true : false;		
			
		
		}
		
		function load_form_classes(){
		
			require_once(dirname(realpath(__FILE__)).'/../../bew_framework_common/classes/form_classes.php'); 
		
		
		}
		
		function __toString(){
		
		
		
			
			$this->validate();
			
			$this->render_messages();
			
			$this->render_core();
			
			$this->render_footer();
			
			return "<div class='um_form'>$this->html</div>";		
		
		
		}
		

		
		//function for all validations needed before displaying messages, errors or component.
		
		public function validate(){
		
			/// overriden by descendents
		
		}		
		
		//function for rendering messages and errors. 
		
		public function render_messages(){
		
			
			
			if(is_array($this->errors)){
			
				
			
				$this->html.= "<div class='errors'>";
				
				foreach ($this->errors as $e) $this->html.= "<div class='error' >$e</div>";
				
				$this->html.= "</div>";
			}
			
			

			
			if(is_array($this->messages)){
			
				$this->html.= "<div class='messages'>";
				
				foreach ($this->messages as $m) $this->html.= "<div class='message' >$m</div>";
				
				$this->html.= "</div>";
			}
		}
		
		
		///main function for rendering the core of the component
		
		public function render_core(){
		
			/// overriden by descendents
		
		}
		
		public function render_footer(){
		
			/// overriden by descendents
		
		}
		
		
		
		
		
		public function already_logged_in(){
		
		
		
			$this->messages[] = __('You are already logged in.').' '.$this->logout_link(__('Log out'));
			
			$this->disable_form = true;
			
			return "";
						
		}
		
		public function logout_link($text = "", $url = "",  $attributes = "", $redirect = ""){
		
			if(empty($redirect)) $redirect = '/'.$_SERVER['REQUEST_URI'];
		
			if(empty($url)) $url = wp_logout_url( $redirect );	
			
			if(empty($text)) $text = __('Log out');
			
			return "<a href='$url' $attributes >$text</a>";
		
		
		}
		
		
		
		
	
		
			
	
	}
	
	

	
	
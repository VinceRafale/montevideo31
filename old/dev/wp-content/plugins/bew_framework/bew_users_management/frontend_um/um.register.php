<?php


	


	class bew_frontend_um_register extends bew_frontend_um
	{
	
	
		public $form;
		
		
		function __construct(){
			
			parent::__construct();
			
			require_once( ABSPATH . WPINC . '/registration.php');
			
			$this->load_form_classes();
			
			include( 'form.register.php' );
			
			$this->form = new clonefish( 'input', $_SERVER['REQUEST_URI'].'#content', 'POST' );	
			$this->form->submit = __('Register');
			$this->form->addelements( $config, $_POST, get_magic_quotes_gpc() );
			$this->form->js = 0;
			
	
			
			
					
		}
	
		function validate(){
		
			if($this->logged_in) return $this->already_logged_in();
			
			if ( count( $_POST ) && $this->form->validate() )
			{
				
				$user = $this->register_wp_user($_POST['user_login'], $_POST['user_email'], $_POST['user_pass']);
			
				if(is_wp_error($user)){
				
					if(!is_array($this->errors)) $this->errors = array();
					$this->errors = array_merge($this->errors, $user->get_error_messages());
				
				} else {
				
					$this->messages[] = '<strong>'.__('You have successfully registered on').' '.get_bloginfo('name').'. '.__('You will receive a confirmation email.').'</strong>'.(isset($_REQUEST['redirect']) && !empty($_REQUEST['redirect']) ? bew_redirect_js($_REQUEST['redirect']) : "");
					
					bew_message('Registration successful.', 'message');
					
					$this->disable_form = true;
					
				
				}
				
			}
			
			
		
		}
		
		
		function save_user_meta($user_id){

			$success = true;
		
			foreach ($_POST as $k => $v){
			
				
					if(!stristr($k, 'bew_user_')) continue;
					
					update_user_meta($user_id, str_replace('bew_user_', '', $k), strip_tags($v));
			
			
			}
			
			return $success;
		
		}
		
		
		function register_wp_user($user_login, $user_email, $user_pass){
		
				$errors = new WP_Error();

				$sanitized_user_login = sanitize_user( $user_login );
				$user_email = apply_filters( 'user_registration_email', $user_email );
			
				// Check the username
				if ( $sanitized_user_login == '' ) {
					$errors->add( 'empty_username', __( '<strong>ERROR</strong>: Please enter a username.' ) );
				} elseif ( ! validate_username( $user_login ) ) {
					$errors->add( 'invalid_username', __( '<strong>ERROR</strong>: This username is invalid because it uses illegal characters. Please enter a valid username.' ) );
					$sanitized_user_login = '';
				} elseif ( username_exists( $sanitized_user_login ) ) {
					$errors->add( 'username_exists', __( '<strong>ERROR</strong>: This username is already registered, please choose another one.' ) );
				}
			
				// Check the e-mail address
				if ( $user_email == '' ) {
					$errors->add( 'empty_email', __( '<strong>ERROR</strong>: Please type your e-mail address.' ) );
				} elseif ( ! is_email( $user_email ) ) {
					$errors->add( 'invalid_email', __( '<strong>ERROR</strong>: The email address isn&#8217;t correct.' ) );
					$user_email = '';
				} elseif ( email_exists( $user_email ) ) {
					$errors->add( 'email_exists', __( '<strong>ERROR</strong>: This email is already registered, please choose another one.' ) );
				}
			
				do_action( 'register_post', $sanitized_user_login, $user_email, $errors );
			
				$errors = apply_filters( 'registration_errors', $errors, $sanitized_user_login, $user_email );
				
			
				if ( $errors->get_error_code() )
					return $errors;
			
				
				$user_id = wp_create_user( $sanitized_user_login, $user_pass, $user_email );
				if ( ! $user_id ) {
					$errors->add( 'registerfail', sprintf( __( '<strong>ERROR</strong>: Couldn&#8217;t register you... please contact the <a href="mailto:%s">webmaster</a> !' ), get_option( 'admin_email' ) ) );
					return $errors;
				}
			
				update_user_option( $user_id, 'default_password_nag', true, true ); //Set up the Password change nag.
			
				wp_new_user_notification( $user_id, $user_pass );
				
				$this->save_user_meta($user_id);
				
				return $user_id;
		
		}
		
		function render_core(){
		
		
		

			if(!$this->disable_form) $this->html .= $this->form->gethtml();
				
		
		
		
		}
	
	}
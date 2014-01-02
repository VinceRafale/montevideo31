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
			
			if( isset($_SESSION['alert_sms']) ){
				$this->form->setValue('bew_user_adu_user_mobile',substr_replace( $_SESSION['alert_sms'], "0", 0, 2 ));
			}
			
			$this->form->js = 0;
					
		}
		
		
		function validate(){
		
			if($this->logged_in) return $this->already_logged_in();
			
			if ( count( $_POST ) && $this->form->validate() ){
				
				// pseudo = firstname.lastname
				$bew_user_login = ucfirst( strtolower($_POST['first_name']) ) . "." . ucfirst( strtolower($_POST['last_name']) );
				$user = $this->register_wp_user($bew_user_login, $_POST['user_email'], $_POST['user_pass']);
				//$user = $this->register_wp_user($_POST['user_login'], $_POST['user_email'], $_POST['user_pass']);
			
				if(is_wp_error($user)){
				
					if(!is_array($this->errors)) $this->errors = array();
					$this->errors = array_merge($this->errors, $user->get_error_messages());
				
				} else {
						
		    		// add user to basic group
					update_user_meta( $user, 'adu_groups', array( 'all_users' ));
					
					// test if $_SESSION['product_id'] exists in the case a user not registered try to buy product
					if( isset( $_SESSION['product_id'] ) ){
					
						// user comes from product page, change the header
						$bp_creds = array();
						$bp_creds['user_login'] = $_POST['user_login'];
						$bp_creds['user_password'] = $_POST['user_pass'];
						$bp_creds['remember'] = false;
						$bp_user = wp_signon( $bp_creds, false );
						if ( is_wp_error($bp_user) ){
							echo $bp_user->get_error_message();
		    			}else{
		    				/*$bew_p_temp = get_page_by_path('bp_page_moyen_paiement');
		    				$bew_p_redirect = "";
		    				if ($bew_p_temp) {
		    					$bew_p_redirect = get_permalink( $bew_p_temp->ID );
		    				}
		    				*/
		    				$bew_p_redirect = get_ID_by_slug('bp_page_moyen_paiement');
		    				$this->messages[] = '<strong>Vous allez &#234;tre redirig&#233; vers la page paiement.'.bew_redirect_js( $bew_p_redirect , 3).'</strong>';
						}
		    			
					}elseif( isset($_SESSION['alert_sms']) ){
						
						// user comes from product page, change the header
						$bp_creds = array();
						$bp_creds['user_login'] = $_POST['user_login'];
						$bp_creds['user_password'] = $_POST['user_pass'];
						$bp_creds['remember'] = false;
						$bp_user = wp_signon( $bp_creds, false );
						if ( is_wp_error($bp_user) ){
							echo $bp_user->get_error_message();
		    			}else{
		    				$bew_p_redirect = get_ID_by_slug('inscription-aux-alertes-sms');
		    				$this->messages[] = '<strong>Vous allez &#234;tre redirig&#233; vers l\'enregistrement de votre numéro aux Alertes SMS. '.bew_redirect_js( $bew_p_redirect , 3).'</strong>';
						}
					}else{
						// user comes from any page, normal header
						$this->messages[] = '<strong>'.__('Vous êtes désormais enregistré sur le site').' '.get_bloginfo('name').'. '.__('Vous allez recevoir un email de confirmation.').'</strong>'.(isset($_REQUEST['redirect']) && !empty($_REQUEST['redirect']) ? bew_redirect_js($_REQUEST['redirect']) : "");
						
						
						//bew_message('Registration successful.', 'message');
						$this->disable_form = true;
					}
					
				
				}
				
			}
			
			
		
		}
		
		
		function save_user_meta($user_id){

			$success = true;
		
			foreach ($_POST as $k => $v){
			
					
					if(stristr($k, 'first_name')){
						update_user_meta($user_id, 'first_name', strip_tags($v));
					}
					
					if(stristr($k, 'last_name')){
						update_user_meta($user_id, 'last_name', strip_tags($v));
					}
					
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
					$errors->add( 'empty_username', '<strong>Erreur</strong>: Merci de renter un pseudonyme.' );
				} elseif ( ! validate_username( $user_login ) ) {
					$errors->add( 'invalid_username', '<strong>Erreur</strong>: Ce pseudonyme comporte des caractères non autorisés.' );
					$sanitized_user_login = '';
				} elseif ( username_exists( $sanitized_user_login ) ) {
					$errors->add( 'username_exists', '<strong>Erreur</strong>: Ce pseudonyme est déjà pris, merci d\'en choisir un autre.' );
				}
			
				// Check the e-mail address
				if ( $user_email == '' ) {
					$errors->add( 'empty_email', '<strong>Erreur</strong>: Merci de rentrer votre adresse email.' );
				} elseif ( ! is_email( $user_email ) ) {
					$errors->add( 'invalid_email', '<strong>Erreur</strong>: L\'adresse email est incorrecte.' );
					$user_email = '';
				} elseif ( email_exists( $user_email ) ) {
					$errors->add( 'email_exists', '<strong>Erreur</strong>: Cet email est déjà utilisé par un compte.' );
				}
			
				do_action( 'register_post', $sanitized_user_login, $user_email, $errors );
				$errors = apply_filters( 'registration_errors', $errors, $sanitized_user_login, $user_email );
				if ( $errors->get_error_code() )
					return $errors;
				$user_id = wp_create_user( $sanitized_user_login, $user_pass, $user_email );
				if ( ! $user_id ) {
					$errors->add( 'registerfail', sprintf( __( '<strong>Erreur</strong>: Problème technique, merci de contacter l\'<a href="mailto:%s">administrateur du site.</a> !' ), get_option( 'admin_email' ) ) );
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
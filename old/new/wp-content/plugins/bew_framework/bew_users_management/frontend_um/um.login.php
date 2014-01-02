<?php


	


	class bew_frontend_um_login extends bew_frontend_um
	{
	
	
		public $form;
		
		
		function __construct(){
			
			parent::__construct();
			require_once( ABSPATH . WPINC . '/registration.php');
			$this->load_form_classes();
			include( 'form.login.php' );
			$this->form = new clonefish( 'input', $_SERVER['REQUEST_URI'].'', 'POST' );		
			$this->form->addelements( $config, $_POST, get_magic_quotes_gpc() );
			$this->form->js = 0;		
		}
	
		function validate(){
		
			if($this->logged_in) return $this->already_logged_in();
			
			if ( count( $_POST ) && $this->form->validate() ){
			
					$secure_cookie = '';
					$interim_login = isset($_REQUEST['interim-login']);
					// If the user wants ssl but the session is not ssl, force a secure cookie.
					if ( !empty($_POST['log']) && !force_ssl_admin() ) {
						$user_name = sanitize_user($_POST['log']);
						if ( $user = get_userdatabylogin($user_name) ) {
							if ( get_user_option('use_ssl', $user->ID) ) {
								$secure_cookie = true;
								force_ssl_admin(true);
							}
						}
					}
					$reauth = empty($_REQUEST['reauth']) ? false : true;
					// If the user was redirected to a secure login form from a non-secure admin page, and secure login is required but secure admin is not, then don't use a secure
					// cookie and redirect back to the referring non-secure admin page.  This allows logins to always be POSTed over SSL while allowing the user to choose visiting
					// the admin via http or https.
					if ( !$secure_cookie && is_ssl() && force_ssl_login() && !force_ssl_admin() && ( 0 !== strpos($redirect_to, 'https') ) && ( 0 === strpos($redirect_to, 'http') ) )
						$secure_cookie = false;
					if(!is_email($_POST['user_email'])) return $this->errors[] = __('Your email address is not valid. (Model: john@example.com)');
					$user_info = get_user_by_email($_POST['user_email']);
					
					
					/*
		 			 *	CHECK IS THE USER HAS UNSUBSCRIBED
		 			 */
					$isUserUnsubscribed = get_user_meta($user_info->ID, 'unsubscribed_site');
					if( $isUserUnsubscribed ){
						// User has unsubscribed
						$this->errors[] = sprintf(__('<strong>Erreur :</strong> Vous vous &#234;tes d&#233;sinscrit du site.'));
						return;
					}else{
						// User hasn't unsubscribed
						if(isset($user_info->user_login)){
						
							$cred['user_login'] = $user_info->user_login;
							$cred['user_password'] = $_POST['user_password'];
							$cred['remember'] = $_POST['remember'];
							$user = wp_signon($cred, $secure_cookie);
						
							if ( !is_wp_error($user) && !$reauth) {
								// test if the user need to be redirect to product admin page
								if( isset( $_SESSION['product_id'] ) ){
									$bew_p_temp = get_ID_by_slug('bp_page_moyen_paiement');
					    			$this->messages[] = '<strong>Vous allez &#234;tre redirig&#233; vers la page paiement.'.bew_redirect_js( $bew_p_temp , 3).'</strong>';
									return;
									
								}elseif( isset($_SESSION['alert_sms']) ){
									// check if the user need tobe redirect to alert url subcription
									$bew_p_temp = get_ID_by_slug('inscription-aux-alertes-sms');
					    			$this->messages[] = '<strong>Vous allez &#234;tre redirig&#233; vers la page de sauvegarde de votre numéro aux Alertes SMS.'.bew_redirect_js( $bew_p_temp , 3).'</strong>';
									return;

									
								}else{
									// normal redirection
									$this->messages[] = '<strong>'.__('You have successfully logged in on').' '.get_bloginfo('name').'.</strong>'.(isset($_REQUEST['redirect']) && !empty($_REQUEST['redirect']) ? bew_redirect_js($_REQUEST['redirect']) : bew_redirect_js(get_bloginfo('url')));
									bew_message('You are now logged in.', 'message');
									$this->disable_form = true;
									return;
								}
							}
						}

					
					
					}
					
					
				$this->errors[] = sprintf(__('<strong>ERROR</strong>: Incorrect email and/or password. <a href="%s" title="Password Lost and Found">Lost your password</a>?'), site_url('wp-login.php?action=lostpassword', 'login'));
			
			
			}
		
		}
		
		function render_core(){
		
			if(!$this->disable_form) $this->html .= $this->form->gethtml();
		
		}
	
	}
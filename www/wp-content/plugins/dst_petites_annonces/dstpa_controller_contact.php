<?php



class dstpa_controller_contact { 

	public $post_id;
	public $form;
	private $view;
	
	
	function __construct($id){ 
	
		$this->post_id = $id;
		
		$this->view = new dstpa_view_contact($id);
		
		$this->view->post_id = $this->post_id;
		
		
		
	}//end __construct
	
	
	function frontend_logic(){
	
		require_once( dirname(realpath( __FILE__ )).'/../classes/form_classes.php' );
		
		include('forms/form.contact_author.php');
					
		$this->form = new clonefish( 'contactform', $_SERVER['REQUEST_URI'].'#respond', 'POST' );	
		$this->form->submit = __('Envoyer le message', 'dstpa');
		
		$this->form->layouts = $dst_form_custom_layouts;
		$this->form->messagecontainerlayout = $dst_messagecontainerlayout;
		$this->form->messagelayout = $dst_messagelayout;
		$this->form->messagepostfix = $dst_messagepostfix;
		$this->form->errorstyle = $dst_errorstyle;
		$this->form->formopenlayout = $dst_formopenlayout;
		$this->form->formcloselayout = $dst_formcloselayout;
		
		$this->form->layout = 'rowbyrow';  
		$this->form->addelements( $form_config, $_POST, get_magic_quotes_gpc() );
		$this->form->js = 0;
	
		if ( count( $_POST ) && $this->form->validate() ) {
			
			if(! $this->quota_exceeded() && ! $this->contain_forbidden_words()){
				
				foreach($_POST as $k => $v){
					
					$args[$k] = $v;
				
				}
				$this->send_email( $args );
				
			}
			
		} else {
		
			$this->display_form();
		
		}	
	
	}
	
	function display_view(){
	
		$this->view->display_html();
	
	}//end display_view
	
	
	function quota_exceeded(){
	

		
		//récupération des informations en option pour le transient
		$transient_number = get_option('dstpa_setting_number');
		$transient_time = get_option('dstpa_setting_time');
		
		//récupération de l'IP du contact
		$contact_ip = $_SERVER['REMOTE_ADDR'];
		
		//création de la clé du transient avec l'IP du contact
		$transient_key = 'dstpa_send_message_contact_'.$contact_ip;
		
		//Récupération du nombre de messages envoyés durant une période de x heures
		if(!current_user_can('manage_options')) $transient = get_transient($transient_key);
		
		// récupération de la valeur existante
		if($transient) {
		
			$result = $transient;
		
		}
		else {
		
			$result = 0;
		
		}
		
		// incrémentation +1
		$result += 1;
			
		if( $result >= $transient_number ) {
		
			$time = ($transient_time / 60) / 60;
				
			$this->view->errors[] = sprintf(__("Vous ne pouvez pas envoyer plus de %d messages en %d heures. Meric de r&eacute;essayer dans %d heures"), $transient_number, $time, $time, 'dstpa');
			
			return true;
		}
		
		//enregistrement du transient
		
		if(!current_user_can('manage_options'))
			set_transient($transient_key, $result, $transient_time);
			
			
		return false;
	
	}//end quota_exceeded
		
		
			
	function contain_forbidden_words(){
	
		//conversion des éléments envoyés en tableau associatif
		$args = wp_parse_args( $_POST, array() );
		
		//récupération de la black list (mots interdits)
		$blacklist = get_option( 'dstpa_setting_blacklist' );
		$blacklist = str_replace(',', ' ', $blacklist);
		$blacklist = str_replace(';', ' ', $blacklist);
		$blacklist = explode(' ', $blacklist);
		
		foreach($args as $value){
		
			$words = explode(' ', $value);
			
			foreach($words as $val){
				
				if(in_array($val, $blacklist)){
				
					$this->save_moderated_message( $this->post_id );
					$this->send_alert_message();
				
					return true;
					
				}
			
			}
		
		}
		
		return false;
	
	}//end contain_forbidden_words	
	
	
	function save_moderated_message( $post_id ){
	
		$_POST['post_id'] = $post_id;
		
		add_post_meta($post_id, 'dstpa_moderated_message', $_POST);
	
		$this->view->content = __("Votre message a bien &eacute;t&eacute; envoy&eacute; &agrave; l'auteur de la petite annonce.", 'dstpa');
		
	}
	
	
	function send_email( $args ){
	
		global $wp_query;
		
		if( $args['post_id'] && !empty($args['post_id']) ) $post_id = $args['post_id']; else $post_id = $wp_query->post->ID;
		
		$tel='';		
		
		if( isset($args['tel']) && !empty($args['tel']))
			$tel = "Tel : ".$args['tel']."\r\n";
	
	
		$email_contact = get_post_meta( $post_id, 'dstfp_author_email', true );
		$subject = get_bloginfo('name');
		
		//composition du message
		$message = "R&eacute;ponse pour votre message n°".$post_id."\r\n\r\nNom  : ".$args['contact_name']."\r\n".$tel."Email : ".$args['email']."\r\n\r\n".stripslashes($args['message']);
		$headers = 'From: "'.get_bloginfo('name').'" <'.get_option('dstpa_from_email')."> \r\n";
		$headers .= "Reply-To: ".$email_contact."\r\n";
		
		$args['copy'] = 1;
		
		if($args['copy'])
			$headers .= "Cci:".get_option('dstpa_from_email').", watch@4l-trophy.com \n";
	
	
		if( wp_mail( $email_contact, $subject, $message, $headers) )
			$this->view->content = __("Votre message a bien &eacute;t&eacute; envoy&eacute; &agrave; l'auteur de la petite annonce.", 'dstpa');
		else
			$this->view->errors[] = __("Il ya eu un probl&egrave;me lors de l'envoi de votre mail. Merci de r&eacute;essayer ou de nous contacter.", 'dstpa');
	}//end send_email
	
	
	
	function send_alert_message(){
	
		$email_contact = get_option('dstpa_from_alert_email');
		$subject = sprintf(__('Message pour petite annonce', 'dstpa'), $post_id);
		
		//composition du message
		$message = __("A message has been taken in pending. You can see it here to validate or delete it : ")."\r\n".get_bloginfo('url')."/wp-admin/";
		$headers = 'From: "'.get_bloginfo('name').'" <'.get_option('dstpa_from_email')."> \r\n";	
		$headers .= "Reply-To: ".$email_contact."\r\n";
		$headers .= "Cci:".get_option('dstpa_from_email')."\n";
		
		wp_mail( $email_contact, $subject, $message, $headers );
	
	}
	
				
			
	function display_form(){
		
		$this->view->content = $this->form->gethtml();
						
	}//end display_form
	
}//end class 
<?php


class dstfp_controller_publish {

	public $view; 
	public $terms; 
	public $form_config; 
	public $post_id;

	//récupération des arguments et mise en tableau
	function parse_args( $args ){
	
		$default = array(
			'post_status' => 'pending',
			'champs' => -1,
			'exclude' => false,
			'include' => false,
			'taxonomy' => 'category',
			'user_id' => 'auto',
			'post_type' => 'post',
			'restrict_access' => 0,
			'formfile' => 'default'
		);
		
		$args = wp_parse_args( $args, $default );
		
		foreach($args as $k => $v) $this->$k = $v;
		//var_dump($this);
	}
	
	//initialisation et création des paramêtres
	function __construct( $args = false ){
	
		$args = $this->parse_args( $args );

		$this->view = new dstfp_view_publish();
		var_dump($this);
	}
	
	
	//ordre des actions
	function frontend_logic(){
		
		require_once( dirname(realpath( __FILE__ )).'/../classes/form_classes.php' );
		include('forms/form.'.$this->formfile.'.php');
		
		$this->form_config = $form_config;
		
		$this->terms = $this->select_terms();
		
		if(is_array($this->terms)){
		
			$this->create_checkboxes();
			
		}
		
		//création du formulaire
		$this->form = new clonefish( 'publishform', $_SERVER['REQUEST_URI'].'#respond', 'POST' );
		
		
		
		$this->form->submit = __('Publier', 'dstfp');
		
		$this->form->layouts = $dst_form_custom_layouts;
		$this->form->messagecontainerlayout = $dst_messagecontainerlayout;
		$this->form->messagelayout = $dst_messagelayout;
		$this->form->messagepostfix = $dst_messagepostfix;
		$this->form->errorstyle = $dst_errorstyle;
		$this->form->formopenlayout = $dst_formopenlayout;
		$this->form->formcloselayout = $dst_formcloselayout;
		
		$this->form->layout = 'rowbyrow'; 
		
		global $current_user;
		
		if($current_user->ID > 0 && $this->user_id == 'auto') {
		
			$this->form_config['author_name']['isdisabled'] = true;
			$this->form_config['author_name']['value'] = $current_user->display_name;
			unset($this->form_config['email']);
		
		}
		
		$this->form->addelements( $this->form_config, $_POST, get_magic_quotes_gpc() );
		//var_dump($this->form_config);
		
		$this->form->js = 0;
		
		
		
		//si envoi formulaire 
		if ( count( $_POST ) && $this->form->validate() ) {
		
			$this->publish($_POST);
			
		}
		else {
		
			if($this->restrict_access){
			
				if( !current_user_can('edit_posts') ) $this->view->content = __('Sorry, you must be authentified.', 'dstfp');
				else $this->view->content = $this->form->gethtml();
				
			}
			else {
				
				//envoi du formulaire à la vue
				
				$this->view->content = $this->form->gethtml();
					
			}
		
		}
	
	}
	
	//enregistre
	function publish( $args ){
	
		global $wpdb;
		
		$results = array();
		
		foreach($args['taxonomy'] as $taxo) {
		
			$q = $wpdb->prepare(    "SELECT term_id FROM $wpdb->terms WHERE slug = '".$taxo."'"   );
			$results[$taxo] = $wpdb->get_results($q);
		
		}
		
		//var_dump($results);
		if(is_array($results) && sizeof($results) > 0) {
	
			//mise en array les id des catégories concernées
			$cat_id = array();
			foreach($_POST['taxonomy'] as $k => $v) $cat_id[] = $v;
			
			//gestion des commentaires
			if($this->comments) $comments_status = 'open'; else $comments_status = 'closed';
			if($this->ping) $ping_status = 'open'; else $ping_status = 'closed';
			
					
			if($this->user_id == 'auto' && get_current_user_id() > 0) 
				$user_ID = get_current_user_id(); 
			else $user_ID = 1;
		
			
			$defaults = array(
				'post_status' => $this->post_status, 
				'post_type' => $this->post_type,
				'post_author' => $user_ID,
				'post_parent' => 0,
				'post_title' => $_POST['title'],
				'post_content' => $_POST['message'],
				'post_author' => $this->user_id,
				'comment_status' => $comments_status,
				'ping_status' => $ping_status,
				'post_category' => $cat_id
			);
			//var_dump($defaults);
			$this->post_id = wp_insert_post( $defaults );
			
			//si on récupère un id pour le post alors l'enregistrement s'est bien déroulé -> on poursuit avec les postmeta
			if($this->post_id) {
			
				foreach ($this->terms as $k => $taxo){
				
					$terms = $append = '';
					wp_set_post_terms( $this->post_id, $cat_id, $this->taxonomy, $append );
					
				}
				
				if(isset($_POST['author_name'])) add_post_meta($this->post_id, 'dstfp_author_name', $_POST['author_name']);
				//add_post_meta($this->post_id, 'dstfp_author_tel', $_POST['phone']);
				if(isset($_POST['email'])) add_post_meta($this->post_id, 'dstfp_author_email', $_POST['email']);
				
				//si pièce jointe, enregistrement comme image à la une
				if( is_array($_FILES) ){
				
					//fichiers joints
					require_once( ABSPATH.'wp-admin/includes/media.php' );
					require_once( ABSPATH.'wp-admin/includes/file.php' );
					require_once( ABSPATH.'wp-admin/includes/post.php' );
					require_once( ABSPATH.'wp-admin/includes/image.php' );
					
					//récupération de l'id de l'image
					$thumbnail_id = media_handle_upload( 'inputfile', $this->post_id );
					
					//ajout du post meta
					add_post_meta($this->post_id, '_thumbnail_id', $thumbnail_id);
				
				}
				
				$this->send_alert();
				
				$this->view->content = sprintf( __('Your message has been submited for approval.'), $this->post_id);
				
			}
			else $this->view->errors[] = sprintf( __('Your message could not be saved. Try again or contact us.') );
			
		}
		else $this->view->errors[] = sprintf( __('Your message could not be saved. Try again or contact us.'));
	
	}
	
	function select_terms(){
	
		$taxonomies = explode(',', $this->taxonomy);
		
		$selected_terms = array();
		
		foreach($taxonomies as $val){
		
			$args = array(
				'hide_empty' => 0,
				'taxonomy'           => $val
			);
			
			$terms = get_categories( $args );
			
			$selected_terms = array_merge($selected_terms, $terms);
		}
		
		return $selected_terms;
		
	}
	
	function create_checkboxes(){
	
		if(strlen($this->exclude) > 0) $exclude = explode(',', $this->exclude);
		if(strlen($this->include) > 0) $include = explode(',', $this->include);
		
		
		
		foreach ($this->terms as $k => $taxo){
		
			$add = false;
			
			if(is_array($include)){
			
				foreach($exclude as $val) if($this->terms[$k]->slug == $val) $add = true;
				
			} else $add = true;
			
			if(is_array($exclude)){
				foreach($exclude as $val)	if($this->terms[$k]->slug == $val) $add = false;
			}		
					
			if($add) $this->form_config['taxonomy']['values'][$taxo->term_id] = $taxo->name;
		
		}
		
	}
	
	function upload_thumbnail(){
	
		//var_dump($_FILES);
		//inclusion des fichiers
		require_once( ABSPATH.'wp-admin/includes/media.php' );
		require_once( ABSPATH.'wp-admin/includes/file.php' );
		
		$thumbnail_id = media_handle_upload( 0, $this->post_id, $overrides = $_FILES[0] );
		
		var_dump($thumbnail_id);
	
	}
	
	function send_alert(){
	
		$email_contact = get_option('dstpa_from_alert_email');
		$subject = sprintf(__('Nouvelle Petite Annonce en attente', 'dstpa'), $post_id);
		
		//composition du message
		$message = __("A new Personnal Column is pending. You can see it here : ")."\r\n".get_bloginfo('url')."/wp-admin/edit.php?post_type=petite_annonce";
		$headers = 'From: "'.get_bloginfo('name').'" <'.get_option('dstpa_from_email')."> \r\n";	
	
		wp_mail( $email_contact, $subject, $message, $headers );
	
	}

}//end class
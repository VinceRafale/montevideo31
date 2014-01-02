<?php



class dstfp_controller_submit {

	public $email;
	public $post_id;
	
	function __construct(){
	
		$this->view = new dstfp_view_submit();
	
	}
	
	function frontend_logic(){
	
		require_once( dirname(realpath( __FILE__ )).'/../classes/form_classes.php' );
		include('forms/form.submit_personnal_columns.php');
		
		$this->form_config = $form_config;
		$this->form = new clonefish( 'publishform', $_SERVER['REQUEST_URI'].'#respond', 'POST' );
		$this->form->submit = __('Supprimer la petite annonce', 'dstfp');
		
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
		
			//initialisation des variables
			$this->post_id = $_POST['post_id'];
			$this->email = $_POST['email'];
			
			$deleted = $this->delete_pa();
			
			if( $deleted ) $this->view->content = sprintf( __('Your personnal column has been deleted.'), $post_id, 'dstfp' );
			
			else $this->view->content = sprintf( __('Your email or the personnal column number is invalid. Verfied it.'), $post_id, 'dstfp' );
		
		}else{
		
			$this->view->content = $this->form->gethtml();
			
		}
	
	}//end frontend_logic;
	
	function delete_pa(){
	
		$validated = wp_delete_post( $this->post_id, $force_delete = false );
		
		if( $validated && !empty($validated) ) return true; else return false;
	
	}

}//end class
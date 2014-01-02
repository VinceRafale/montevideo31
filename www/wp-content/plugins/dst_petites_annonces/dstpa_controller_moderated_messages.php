<?php


class dstpa_controller_moderated_messages {

	public $moderated_messages;
	public $messages;
	public $view;
	public $model;
	public $post_meta_id;
	
	
	function __construct( $post_meta_id ){
	
		$this->post_meta_id = $post_meta_id;
		
	}
	
	

	function backend_logic(){
	
		//on récupère les id de tous les posts
		$this->model = new dstpa_model_moderated_messages();
		$this->view = new dstpa_view_moderated_messages();
		
		//on récupère les messages modérés de chaque post petite annonce
		$messages = $this->model->select_moderated_messages();
		
		if($messages){
			
			//on envoie les messages dans la vue pour affichage à l'utilisateur
			$this->view->messages = $messages;
		
		}else{
		
			$this->view->errors[] = __('There is no pending messages.', 'dstpa');
		
		}
				
	}
	
	function delete_message(){
	
		$this->model = new dstpa_model_moderated_messages();
		//$confirm_delete = $this->model->delete_moderated_message( $this->post_meta_id );
		$confirm_delete = true;
		if($confirm_delete)
			$this->view->errors[] = __('The pending messages has been deleted.', 'dstpa');
		else
			$this->view->errors[] = __('Impossible to delete this pending message.', 'dstpa');
	
	}
	
	function send_message(){
	
		$this->contact = new dstpa_controller_contact();
		//$confirm_send = $this->contact->send_email( $_POST );
		
		$this->model = new dstpa_model_moderated_messages();
		$message = $this->model->select_moderated_message($this->post_meta_id);
		//var_dump($message);
		$confirm_delete = $this->model->delete_moderated_message( $this->post_meta_id );
	
	}

}
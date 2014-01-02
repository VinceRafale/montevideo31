<?php


class dstpa_model_moderated_messages {
	
	public $petites_annonces_ids;
	public $moderated_messages;

	function select_post_id(){
	
		$args = array(
			'post_type' => 'petite_annonce',
			'post_status' => 'publish'
		);
		
		$q = new WP_Query($args);
		
		if($q->have_posts()) : while( $q->have_posts() ) : $q->the_post();
		
			$this->petites_annonces_ids[] = get_the_ID();
		
		endwhile;
		endif;
		
		//var_dump($this->post_moderated_id);
		return $this->petites_annonces_ids;
	
	}
	
	function select_moderated_messages(){
	
		global $wpdb;
		
		$q = $wpdb->prepare(    "SELECT * FROM $wpdb->postmeta WHERE meta_key = 'dstpa_moderated_message'"   );
		
		$results = $wpdb->get_results($q);
	
		if(is_array($results) && sizeof($results) > 0) return $results; else return false;
	
	}
	
	
	function delete_moderated_message( $post_meta_id ){
	
		global $wpdb;
		
		$q = "DELETE FROM $wpdb->postmeta WHERE meta_id = '$post_meta_id'";
	
		$results = $wpdb->query($q);
		
		return true;
	
	}
	
	function select_moderated_message($post_meta_id){
	
		global $wpdb;
		
		$q = $wpdb->prepare( "SELECT * FROM $wpdb->postmeta WHERE meta_key = 'dstpa_moderated_message' AND meta_id = '$post_meta_id'" );
		
		$results = $wpdb->get_results($q);
		
		if(is_array($results) && sizeof($results) > 0) return $results; else return false;
	
	}

}
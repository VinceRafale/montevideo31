<?php



	
	
		global $adu_fields, $adu_fields_listview, $adu_fields_listview_more, $additionnal_fields_mail;
		
		$adu_fields = array(
		
				
				
				'user_adresse' => __('Adresse'),
				'user_code_postal' => __('Code Postal'),
				'user_ville' => __('Ville'),
				'user_pays' => __('Pays'),
				'user_telephone' => __('T&eacute;l&eacute;phone'),
				'user_fax' => __('Fax'),
				'user_mobile' => __('Mobile'),
				
		);
		
		$adu_fields_listview = array(
		
				
				
				
				'user_telephone' => __('Tel.'),
				'user_mobile' => __('Mobile'),
				
				
		);
		
		$adu_fields_listview_more = array(
		
				
				
				'user_fax' => __('Fax'),
				'user_adresse' => __('Adresse'),
				'user_code_postal' => __('Code Postal'),
				'user_ville' => __('Ville'),
				'user_pays' => __('Pays'),
				
				
		);
	

	add_action('user_profile_update_errors', 'adu_on_user_update', 200, 3 );


	function adu_on_user_update(&$errors, $update, &$user){
	
	
		global $adu_fields;
	
		/*var_dump($errors);
		echo '<br/><br/>';
		var_dump($update);echo '<br/><br/>';
		
		var_dump($user);
		*/
		
		$uid = $user->ID;
		
		foreach ($adu_fields as $id=>$label){
		
		
			if(isset($_POST['adu_'.$id])) update_usermeta($uid, 'adu_'.$id, trim($_POST['adu_'.$id]));
			
			
		
		
		}
		
		
		
		if(is_array($_POST['adu_groups'])){
		
			update_usermeta($uid, 'adu_groups', $_POST['adu_groups']);
		
		
		
		}
		
		
		//die();
	
	
	}
<?php


	function bew_users_acl_metabox(){
	
		global $post;
		
		$adu = new adu_groups();
		$post_groups = get_post_meta($post->ID, '_adu_acl_groups', true);
		$groups = array('_adu_acl_public_access' => 
						array( 'name' => __('Everyone')));
		$groups = array_merge($groups, $adu->groups);
		foreach ($groups as $id => $options){
			if(
				(is_array($post_groups) && 
				in_array($id, $post_groups)) || (!is_array($post_groups) 
				&& $id == '_adu_acl_public_access')) {
					$checked = " checked='checked' ";
				}else{
					$checked = "";
				}
				
				$groups_html.= "<label for='group_$id' >
					<input type='checkbox' value='$id' id='group_$id' name='adu_acl[]' $checked $disabled/> ";
				$groups_html.= $options['name'].'</label><br />';
		
		}
		$groups_html .= "<input type='hidden' name='adu_save_acl_post_id' value='$post->ID' />";
		echo $groups_html;
	}	
	
	function bew_users_acl_metaboxes(){

		add_meta_box( 'user_groups_acl', 'Groupes d\'utilisateurs autorisés', 'bew_users_acl_metabox', 'post', 'normal');
		remove_meta_box('trackbacksdiv', 'post', 'normal');
	}
	
	add_action('admin_menu', 'bew_users_acl_metaboxes');
	
	
	if((isset($_REQUEST['adu_save_acl_post_id']) && !empty($_REQUEST['adu_save_acl_post_id']))  ) add_action('init', 'bew_users_save_acl_metaboxes');
	
	function bew_users_save_acl_metaboxes(){
	
		$adu = new adu_groups();
		if(empty($_REQUEST['adu_acl']) || !array_key_exists($_REQUEST['adu_acl'], $adu->groups))
			$opt = array('_adu_acl_public_access');
		else $opt = $_REQUEST['adu_acl'];
		update_post_meta(strval($_REQUEST['adu_save_acl_post_id']), '_adu_acl_groups', $_REQUEST['adu_acl']);
	}
	
	
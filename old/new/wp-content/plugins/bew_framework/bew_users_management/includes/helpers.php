<?php
// Helper class to manipulate groups.
// All function needed to build the newsletter module are inside, just instantiate a new adu_groups() object.
class adu_groups{

	public $groups;
	// PHP 4 class initialization
	function  adu_groups(){
		$this->groups = get_option('adu_groups');		
	}
	
	// return true if group exists, by group id or group name
	function group_exists($group_id_or_name){
		if( isset($this->groups[$group_id_or_name])) 
			return true;
		foreach($this->groups as $group){ 
			if($group_id_or_name == $group['name']) 
				return true; 
		}
		return false;
	}
	
	// GN
	// Add a user (given by ID) to a group (given by ID)
	function add_user_to_group( $user_id, $group_id ){
		
		// retrieve user's groups
		$user_groups = get_usermeta( $user_id, 'adu_groups' );
		// check if the user isn't already in the group
		if( is_array($user_groups) && in_array($group_id, $user_groups) ){
			//echo "already in group";
		}else{
			//echo "user not in the group";
			$new_group = array( $group_id );
			// merge the existing group array with the new group
			$user_groups = array_merge( (array)$user_groups, (array)$new_group );
			//var_dump($user_groups);
			// update user meta
			update_usermeta($user_id, 'adu_groups', $user_groups);
		}
		
	}
	
	// create a new group. Optional $group_id provided, otherwise automatically created
	function create($group_name, $group_id = false){
		
		if($group_id){
			if($this->group_exists($group_id)) 
				return new WP_Error('adu_group_id_already_exists', __('Il y a déja un groupe avec cet identifiant.')); 
		}else{
			$group_id = sanitize_title($group_name).'_'.uniqid();
		}
		
		if($this->group_exists($group_name)) 
			return new WP_Error('adu_group_name_already_exists', __('Il y a déja un groupe portant ce nom.')); 
		$this->groups[$group_id] = array('name' => $group_name);
		return $this->save_groups();
	}
	
	
	// save the groups array containing all groups configuration and name
	function save_groups(){
		update_option('adu_groups', $this->groups);
		if(get_option('adu_groups') === $this->groups) 
			return true;
		else 
			return new WP_Error('adu_groups_save_failed', __('User Groups save failed.'));		
	}

	
	// returns the number of users in a group
	function size_of_group($group_id){
			$users = new WP_User_Search(false, false, false, $group_id) ;
			$result = $users->total_users_for_query;
			if($result > 0) 
				return $result; 
			else 
				return 0;
	}
	
	// returns an array with IDs of users in a group
	function get_users_in_group($group_id,$withMail = false){
			$users = new WP_User_Search(false, false, false, $group_id,$withMail) ;
			$result = $users->get_results();
			if(is_array($result)) 
				return $result; 
			else 
				return false;
	}
	
	// rename a group (group id is not changed)
	function rename($group_id, $new_name){
		$this->groups[$group_id]['name'] = $new_name;
		return $this->save_groups();
	}
	
	// deletes a group and removes all relations with users
	function delete($group_id){
		$users = $this->get_users_in_group($group_id);
		foreach($users as $id){
			$groups = get_usermeta($id, 'adu_groups');
			unset($groups[array_keys($groups, $group_id)]);
			update_usermeta($id, 'adu_groups', $groups);
		}
		unset($this->groups[$group_id]);		
		return $this->save_groups();		
	}
	
	
	// returns the name of a group
	function group_name($group_id){
		if(isset($this->groups[$group_id])) 
			return $this->groups[$group_id]['name'];
	}
		
	
}

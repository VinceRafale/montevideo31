<?php

// modifications for the users.php?edit_user=X admin screen


add_action('user_edit_form_tag', 'adu_edit_user_ob_start');

function adu_edit_user_ob_start(){
	ob_start();
}



add_action('edit_user_profile', 'adu_edit_user_groups', 1, 1);

function adu_edit_user_groups($profileuser){

	if(!current_user_can('edit_user', $user_id) || !current_user_can('edit_users')) 
		return;
	$adu = new adu_groups();
	
	$user_groups = get_usermeta($profileuser->ID, 'adu_groups');
	print_r($user_groups);
	$form = ob_get_clean();
	$groups_html = '<tr><th>'.__('Appartient au(x) groupe(s)').'</th><td><div style="padding:5px; font-weight:bold; border:1px solid #DDDDDD; background:#EEEEEE"';
	foreach ($adu->groups as $id => $options){
		if((is_array($user_groups) && in_array($id, $user_groups)) || $id == 'all_users') 
			$checked = " checked='checked' "; 
		else 
			$checked = "";
		if($id == 'all_users') 
			$disabled = " disabled='disabled' "; 
		else 
			$disabled = "";
		if($id == 'all_users') 
			$groups_html.="<input type='hidden' name='adu_groups[]' value='$id'/>";
		$groups_html.= "
			<label for='group_$id' >
		 	<input type='checkbox' value='$id' id='group_$id' name='adu_groups[]' $checked $disabled/> ";	
		$groups_html.= $options['name'].'</label><br />';
	}
	$groups_html.= '</td></tr>';
	$form = str_replace('<th><label for="first_name">',$groups_html.'<th><label for="first_name">', $form);
	
	$coords = '<h3>'.__('Coordonnées').'</h3>';
	$coords.= '<table class="form-table">';
	global $adu_fields;
	foreach ($adu_fields as $id => $label){
		$value = get_usermeta($profileuser->ID, 'adu_'.$id);
		$coords .= "<tr>
						<th><label for='adu_$id'>$label</label></th>
						<td><input type='text' class='regular-text' value='$value' id='adu_$id' name='adu_$id'></td>
					</tr>";
	}
	$coords .= '</table>';
	$form.= $coords;
	echo $form;
	adu_form_style();
}



function adu_form_style(){

	?>
	<style type="text/css">
		#profile-page form table:first-child{display:none}
	</style>
    
    <script type="text/javascript">
	
		jQuery('#profile-page table.form-table').first().hide();
		jQuery('#profile-page h3').first().hide();
	
	</script>
    <?php
}
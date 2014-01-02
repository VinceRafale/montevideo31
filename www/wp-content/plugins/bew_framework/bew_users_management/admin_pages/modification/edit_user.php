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
	$form = ob_get_clean();
	$groups_html = '<tr><th>'.__('Appartient au(x) groupe(s)').'</th><td><div style="padding:5px; font-weight:bold; border:1px solid #DDDDDD; background:#EEEEEE">';
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
	
	$coords = '<h3>'.__('Coordonnées').'</h3> ';
	$coords.= '<table class="form-table">';
	
	
	$value = get_user_meta($profileuser->ID, 'adu_user_adresse', true);
	$coords .="
	<tr>
		<th><label for='adu_user_adresse'>Adresse : </label></th>
		<td><input type='text' class='regular-text' value='$value' id='adu_user_adresse' name='adu_user_adresse'></td>
	</tr>";
	$value = get_user_meta($profileuser->ID, 'adu_user_code_postal', true);
	$coords .="
	<tr>
		<th><label for='adu_user_code_postal'>Code Postal : </label></th>
		<td><input type='text' class='regular-text' value='$value' id='adu_user_code_postal' name='adu_user_code_postal'></td>
	</tr>";
	$value = get_user_meta($profileuser->ID, 'adu_user_ville', true);
	$coords .="
	<tr>
		<th><label for='adu_user_ville'>Ville : </label></th>
		<td><input type='text' class='regular-text' value='$value' id='adu_user_ville' name='adu_user_ville'></td>
	</tr>";
	$value = get_user_meta($profileuser->ID, 'adu_user_mobile', true);
	if( $value != "" ){
		$value = substr_replace( $value, "0", 0, 2 );
	}
	$coords .="
	<tr>
		<th><label for='adu_user_mobile'>Téléphone : </label></th>
		<td><input type='text' class='regular-text' value='$value' id='adu_user_mobile' name='adu_user_mobile'></td>
	</tr>";
	/*
	foreach ($adu_fields as $id => $label){
		$value = get_usermeta($profileuser->ID, 'bew_user_adu_'.$id);
		
		// on n'arrive pas à remplir les inputs avec les bonnes valeurs parce que l'on écrit dans les valeurs classiques de wordpress
		// alors que partout ailleurs sur le site on utilise les bew_user_adu.. fields.
		// voir um.profile et form.profile pour le chargement de $config
		$coords .= "<tr>
						<th><label for='adu_$id'>$label</label></th>
						<td><input type='text' class='regular-text' value='$value' id='adu_$id' name='adu_$id'></td>
					</tr>";
	}
	*/
	
	$coords .= '</table>';
	$form.= $coords;
	echo $form;
	adu_form_style();
}

function adu_form_style(){
	?>
	<style type="text/css">
		#profile-page form table:first-child{display:none}; 
	</style>
    
    <script type="text/javascript">
	jQuery(document).ready(function() {

		jQuery('#profile-page table.form-table').first().hide();
		jQuery('#profile-page h3').first().hide();
		jQuery('#profile-page table.form-table input:text[name="url"]').parent().parent().hide();  
		jQuery('#profile-page table.form-table input:text[name="aim"]').parent().parent().hide();  
		jQuery('#profile-page table.form-table input:text[name="yim"]').parent().parent().hide();  
		jQuery('#profile-page table.form-table input:text[name="jabber"]').parent().parent().hide();  
		jQuery('#profile-page table.form-table textarea[name="description"]').parent().parent().hide(); 
		
		jQuery('#submit').click(function(){
		
		
			var phone = jQuery('#adu_user_mobile').val();
			
			if( phone != "" ){
				var prefix = phone.substr(0,2);
				var formatted_prefix = "33";
				var replace = phone.substr(1,9);
				
				if(/^[0-9]{10}$/.test(phone) && (prefix==="06"||prefix==="07") ){
					jQuery('#adu_user_mobile').val(formatted_prefix+replace);
				}else{
					alert("Le num\351ro de t\351l\351phone est invalide.");
					jQuery('#adu_user_mobile').val("");
					return false;
				}
			}else{
				jQuery('#adu_user_mobile').val("");
			}
			
		});
		
				
		
		 
	});
	</script>
    <?php
}

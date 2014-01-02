<?php



// modification for the users.php admin screen


	if(stristr($_SERVER['REQUEST_URI'], '/wp-admin/users.php')) 
	{
	
	add_action('admin_head', 'candid_ob_start');
	add_action('admin_head', 'candidatures_col_header');
	add_action('admin_footer', 'candid_ob_replace');
	add_filter('manage_users_custom_column', 'candidatures_col', 99, 3);
	
	}
	
	


function candidatures_col_header(){

		$columns = get_column_headers('users');
		

		
		$email_lbl = $columns['email'];
		$role_lbl = $columns['role'];
		
		unset($columns['name']);
		unset($columns['posts']);
		unset($columns['email']);
		unset($columns['role']);
		
		$columns['name_ext'] = __('Nom et coordonnées');
		$columns['email'] = $email_lbl;
		$columns['groups'] = __('Groupe(s)');
		$columns['payments'] = __('Cotisation / Dons');
		$columns['role'] = $role_lbl;
		
		
		register_column_headers('users', $columns);


}
 
function candidatures_col($html="", $column_name, $userid){
	
	global $adu_fields, $adu_fields_listview, $adu_fields_listview_more;
	
	
		$user_object = new WP_User( $userid );
		$user_object = sanitize_user_object($user_object, 'display');
	    
		$return_url = str_replace(' ', '', $_SERVER['REQUEST_URI']).'#user-'.$userid;
		
		$return_url_encoded = urlencode($return_url);
		
		$adu = new adu_groups();
		
		
		switch ($column_name) {
			
			
	
			
			case 'payments':
				
				break;
				
			case 'groups':
			
			
				$user_groups = get_usermeta($userid, 'adu_groups');
				
			
				
				if(is_array($user_groups)){
				
					$virgule = "";
				
					foreach ($user_groups as $group_id){
						$html .= "$virgule<a href='/wp-admin/users.php?&adu_group=$group_id'>".$adu->group_name($group_id)."</a>";
						
						$virgule = ", ";
					}
				
				}
				
				break;
				
			
			case 'name_ext':
				
				
				$html.= "<strong>$user_object->first_name $user_object->last_name</strong>";
				
				$uid = uniqid('div_',false);
				
				$afficher = __('Coordon&eacute;es compl&egrave;tes');
				$masquer = __('Masquer');
				
				$more = "var more_container = document.getElementById('more_$uid');
						var more_link = document.getElementById('more_link_$uid');
						if(more_container.style.visibility == 'hidden'){
							
							more_container.style.visibility = 'visible';
							more_container.style.position = 'static';
							more_link.innerHTML = '$masquer';
						
						} else {
						
							more_container.style.visibility = 'hidden';
							more_container.style.position = 'absolute';
							more_link.innerHTML = '$afficher';
						
						}
				
				 ";

				
				
				$html.= "<div id='more_$uid' style='visibility:hidden; position:absolute; padding:3px; border:1px solid #DFDFDF; margin-top:4px'>";
				
				foreach($adu_fields_listview as $key => $trad){
					
					$html .= "<em>".$trad."</em> : ".get_usermeta( $userid, 'adu_'.$key)."<br />";
				}
				
				
				
				
				
				foreach($adu_fields_listview_more as $key => $trad){
					
					$html .= "<em>".$trad."</em> : ".get_usermeta( $userid, 'adu_'.$key)."<br />";
				}
				
				
				$html.='</div>';
				
				$html .= '<a href="#NULL" id="more_link_'.$uid.'" style="font-weight:normal; margin-bottom:6px; margin-top:2px; display:block"  onClick="'.$more. '" >'.$afficher."</a>";
				
				break;
				
				
			default:
				$html .= "";
			
		} 
		

return $html;
}


function candid_ob_start(){




	ob_start();



}

function candid_ob_replace(){


	$output_original = ob_get_contents();
	$output = $output_original;
	
	$adu = new adu_groups();
	
	
	$heading = "<h2>".__('Users');
	
	if ( current_user_can( 'create_users' ) ) 
		$heading.= '<a href="user-new.php" class="button add-new-h2">'.esc_html_x('Add New', 'user').'</a>';
	
	
	
	if ( isset($_GET['usersearch']) && $_GET['usersearch'] )
		$heading.= sprintf('<span class="subtitle">' . __('Search results for &#8220;%s&#8221;') . '</span>', esc_html( $_GET['usersearch'] ) );
		
	//if( isset($_GET['adu_group']) && $_GET['adu_group'])
	//	$heading.= __('dans le groupe'). '<strong>'.esc_html($_GET['adu_group']).'</strong>';
		
    
	$heading .= '</h2>';
	
	$groups = get_option('adu_groups');
	
	$groups_menu = '<ul class="subsubsub">';
	
	
				
	foreach ($groups as $group_id => $options){
	
		$group_name = $options['name'];
		
		$user_c = $adu->size_of_group($group_id);
	
		if(isset($_GET['adu_group']) && $_GET['adu_group'] == $group_id){
		
			$groups_menu .= "<li class='adu_group_actions' ><a href='/wp-admin/users.php?adu_group=$group_id'><strong> $group_name <span class='count'>($user_c)&nbsp; | </span></strong></a></li>";
			
		
		
		}else{
		
			$groups_menu .= "<li class='adu_group_actions' ><a href='/wp-admin/users.php?adu_group=$group_id'> $group_name <span class='count'>($user_c)&nbsp; | </span></a></li>";
			
		}
	
	
	//<ul class='adu_group_actions'><li>".apply_filters('adu_group_actions', '&nbsp;', $group_id)."</li></ul>
		
	}
	

	$groups_menu .= '</ul>';
	
	$pat = "<h2>(.*)<form class=\"search-form\"";
	$rep = '<form id="list-filter">'.$heading.$groups_menu.'</form></div><form class="search-form"';
	$output = ereg_replace($pat, $rep, $output);
	
	
	
	ob_end_clean();
	echo($output);
	adu_users_css_js();

}



function adu_users_css_js(){

?>

	<style type="text/css">
	
		li.adu_group_actions ul{
		
		display:none; 
		position:absolute; 
		float:left; 
		margin:5px 0 0 -6px;  
		z-index:9999;
		
		border-bottom:1px solid #cccccc;
		border-left:1px solid #DFDFDF;
		border-right:1px solid #DFDFDF;
		
		}
		
		li.adu_group_actions ul li{
		
			padding:5px;
			display:block;
			float:left
		
		}
		
		
		
li.adu_group_actions{position:relative; float:left; display:block; 




}
		
		li.adu_group_actions_disabled:hover, li.adu_group_actions_hover_disabled{background:#fff; 
		border-top:1px solid #DFDFDF;
		border-left:1px solid #DFDFDF;
		border-right:1px solid #DFDFDF;
		margin-right:-1px;
		
		}
		li.adu_group_actions:hover ul.adu_group_actions{display:block; float:left; background:#fff;
		
			
			
		}
	
	
	</style>
    
    <script type="text/javascript">
	

	
	
	</script>
				
		
        
        
        
<?php

}
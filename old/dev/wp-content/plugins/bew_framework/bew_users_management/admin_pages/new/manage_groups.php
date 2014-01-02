<?php



// new admin screen for managing groups.



?>

<div class="wrap">


<div class="icon32" id="icon-users"><br></div>

<h2><?php _e("Groupes d'utilisateurs");  ?></h2><br>





<?php 


$adu_groups = get_option('adu_groups');

$display_group_list = true;

$adu = new adu_groups();


switch($_REQUEST['action']){ 


	case 'new_group':
	
		
		
		$new_group = trim($_REQUEST['new_group']);
		
		
		
		if(empty($new_group)){
		
			$errors = new WP_Error('adu_missing_new_group_name', __('Veuillez indiquer le nom du nouveau groupe.'));
			
					
		}else{
		
		
			$result = $adu->create($_REQUEST['new_group']);
		
			if(is_wp_error($result))   $errors = $result;
			
			else   $messages[] = "<strong>".__('Le nouveau groupe a bien été créé.')."</strong>";
		
				
			
		
		}
		
		
		
		break;
		
		
		case 'rename':
		
		
			if(isset($_GET['group_id']) && isset($adu_groups[$_GET['group_id']]) && $_GET['group_id'] != 'all_users'){
			
				if(isset($_GET['new_name'])){
				
					
					$result = $adu->rename($_GET['group_id'], $_GET['new_name']);
		
					if(is_wp_error($result))   $errors = $result;
					
					else   $messages[] = "<strong>".__('Le groupe a bien été renomé.')."</strong>";
		
				
				}else{
				
					$display_group_list = false;
				
					?>
                    
                    
                    	<form action="" method="get">

                        <label> <?php _e('Nouveau nom pour le groupe'); echo ' &quot;'.$adu->group_name($_GET['group_id'])."&quot;"; ?> : <input type="text" name="new_name" value="" />
                        <button type="submit" class="button add-new-h2" style="vertical-align:text-top" ><?php _e('Renommer'); ?></button>
                        <input type="hidden" name="page" value="users_groups"/>
                        <input type="hidden" name="action" value="rename"/>
                        <input type="hidden" name="group_id" value="<?php echo $_GET['group_id'] ?>"/>
                        
                        </label>
                        
                        </form>
                    
                    <?php
				
				
				
				}
			
			}
		
			
		
		break;
		
		
		case 'delete':
		
		
			if(isset($_GET['group_id']) && isset($adu_groups[$_GET['group_id']]) && $_GET['group_id'] != 'all_users'){
			
				if(isset($_GET['confirm'])){
				
					
					$result = $adu->delete($_GET['group_id']);
					
					
					if(is_wp_error($result))   $errors = $result;
					
					else   $messages[] = "<strong>".__('Le groupe a bien été supprimé.')."</strong>";
						
						
					
		
				
				}else{
				
					$display_group_list = false;
				
					?>
                    
                    
                    	<form action="" method="get">

                        <label> <?php _e('Voulez-vous vraiment supprimer le groupe'); echo ' &quot;'.$adu->group_name($_GET['group_id'])."&quot;"; ?> ?<br/> <?php _e('Attention, cette action est irréversible. Les utilisateurs de ce groupe ne seront PAS supprimés.'); ?> 
                        
                        <br /><br />

<button type="submit" class="button add-new-h2" style="vertical-align:text-top" ><?php _e('Confirmer la suppression'); ?></button>
                        <input type="hidden" name="page" value="users_groups"/>
                        <input type="hidden" name="confirm" value="1"/>
                        <input type="hidden" name="action" value="delete"/>
                        <input type="hidden" name="group_id" value="<?php echo $_GET['group_id'] ?>"/>
                        
                        </label>
                        
                        </form>
                    
                    <?php
				
				
				
				}
			
			}
		
		break;
		
		

?>

<?php }  //endswitch; ?>

<?php if ( isset($errors) && is_wp_error( $errors ) ) : ?>
	<div class="error">
		<ul>
		<?php
			foreach ( $errors->get_error_messages() as $err )
				echo "<li>$err</li>\n";
		?>
		</ul>
	</div>
<?php endif;

if ( ! empty($messages) ) : 
?>

	<div id="message" class="updated">
	<?php
    
		foreach ( $messages as $msg )
			echo "<p>$msg</p>";
			
		?>
        
        </div>
        
<?php endif; ?>



<?php if($display_group_list): ?>
<script type="text/javascript">
</script>
<form action="" method="post">

<label> <?php _e('Nouveau groupe : ')?><input type="text" name="new_group" value="" />
<button type="submit" class="button add-new-h2" style="vertical-align:text-top" ><?php _e('Ajouter'); ?></button>

<input type="hidden" name="action" value="new_group"/>

</label>

</form><br>

<table class="widefat fixed" cellspacing="0">
<thead>
<tr class="thead">
<?php print_column_headers('adu_users_groups') ?>
</tr>
</thead>

<tfoot>
<tr class="thead">
<?php print_column_headers('adu_users_groups', false) ?>

</tr>
</tfoot>

<tbody id="users" class="list:user user-list">
<?php
$style = '';


$adu_groups = get_option('adu_groups');

foreach ( $adu->groups as $group_id => $group_options){

	$user_count = $adu->size_of_group($group_id);	
	$style = ( ' class="alternate"' == $style ) ? '' : ' class="alternate"';
	echo "\n\t", adu_user_group_row( $group_id, $group_options['name'], $style, $user_count);
}
?>
</tbody>
</table>

<?php do_action('adu_group_javascript_header'); ?>


<?php endif; // $display_group_list ?>

</div>

<style type="text/css">

	td.column-actions div.adu_group_action{
	
		
		margin:5px 0px;
		height:20px;
	
	
	}
	
	td.column-users{vertical-align:top; padding:7px}
	
	td.column-actions{padding:0; line-height:0.5}
	
	th.column-group_name{vertical-align:top;}
	
	th.column-group_name div.row-actions{font-weight:normal; padding-top:5px;}


</style>

<?php



function adu_user_group_row( $id, $label, $style = '', $user_count = '0' ) {

	global $wp_roles;

	$current_user = wp_get_current_user();

	
	$r = "<tr id='user-$user_object->ID'$style>";
	
	$columns = get_column_headers('adu_users_groups');
	$hidden = get_hidden_columns('adu_users_groups');
	

	foreach ( $columns as $column_name => $column_display_name ) { 
	
		$class = "class=\"$column_name column-$column_name\"";

		$style = '';
		if ( in_array($column_name, $hidden) )
			$style = ' style="display:none;"';

		$attributes = "$class$style";

		switch ($column_name) {
			case 'group_name':
			
				$r .= "<th $attributes valign='middle'>$label";
				
				if($id != 'all_users')
				
					$r.="<div class='row-actions'>
					<span class='edit'><a href='/wp-admin/users.php?page=users_groups&action=rename&group_id=$id'>".__('Renommer')." </a> | </span>
					<span class='edit'><a href='/wp-admin/users.php?page=users_groups&action=delete&group_id=$id'>".__('Supprimer')." </a></span></div>";
				
				$r.="</th>";
				break;
				
			case 'users':
				$r .= "<td $attributes valign='middle'><a href='/wp-admin/users.php?adu_group=$id'>$user_count ".__('Users')."</a></td>";
				break;
			case 'actions':
				$r .= "<td $attributes valign='middle'>".apply_filters('adu_group_actions', '&nbsp;', $id).'</td>';
				break;
			default:
				$r .= "<td $attributes valign='middle'>";
				$r .= apply_filters('adu_users_group_custom_column', '', $column_name, $id);
				$r .= "</td>";
		}
	}
	$r .= '</tr>';

	return $r;
}



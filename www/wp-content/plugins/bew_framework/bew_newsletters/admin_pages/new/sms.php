<?php  
echo "<pre>";
var_dump($_POST);
echo "</pre>";
$display_group_list = true;

$adu = new adu_groups();


function adu_user_group_row( $id, $label, $style = '', $user_count = '0' ) {

	global $wp_roles;

	$current_user = wp_get_current_user();
    
	
	$r = "<tr id='user-$user_object->ID'$style>";
	
	$columns = get_column_headers('adu_users_groups');
	$hidden = get_hidden_columns('adu_users_groups');
	
    var_dump($columns);
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
    echo "ljfk".$r;
	return $r;
}

$oken_api = get_option("essemess_oken_api");

?>
<div class="wrap">


<div class="icon32" id="icon-users"><br></div>

<h2><?php _e("Gestion des envois de SMS "); bloginfo('name'); ?></h2><br>



<style type="text/css">
    <?php include_once 'style.css'; ?>
</style>
<script type="text/javascript">
jQuery(document).ready(function(){
    jQuery("#tablesorter-contacts").tablesorter(); 
    message_string = jQuery("#new_message_input").attr("value");
    jQuery("#counter").text(160-message_string.length);
   jQuery("#new_message_input").keyup(function(){
       message_string = jQuery("#new_message_input").attr("value");
       jQuery("#counter").text(160-message_string.length);
   }); 
   jQuery("#modify_token").click(function(){
       value = jQuery("#token_value span").text();
       jQuery("#tokenValue").hide();
       jQuery("#updateToken").show();
   })
});
</script>
<div>
<h2>Ajouter un message</h2>

<form method="post" action="">
<?php wp_nonce_field('update-options'); ?>
<div class="blockForm">
    <div class="intitule">Entrez le texte du message</div>
    <div class="input">
        <input name="message_content" type="text" id="new_message_input" class="fLeft"/>
    </div>
    <div id="counter">
        160
    </div>
</div>

<input type="hidden" name="action" value="update" />
<input type="hidden" name="page_options" value="hello_world_data" />

<p>
<input type="submit" value="<?php _e('Save Changes') ?>" />
</p>

</form>
</div>


<div>
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
	
	echo $user_count;
	$style = ( ' class="alternate"' == $style ) ? '' : ' class="alternate"';
	echo "\n\t", adu_user_group_row( $group_id, $group_options['name'], $style, $user_count);
}
?>
</tbody>
</table>
</div>


<div>
    <h2>Paramètres du plugin</h2>
    <div class="blockForm fLeft">
        <div class="intitule2 fLeft">Votre clé d'api Orange</div>
        <div class="input2 fLeft" id="token_value">
            
            <?php if($token_api==NULL){ ?>
                <form method="post" action="options.php">
                <?php wp_nonce_field('update-options'); ?>
                    <input type="text" name="update_token" />
                    <input type="submit" value="enregistrer" />
                </form>
            <?php    
            }else{?>
            <div id="tokenValue">
                <span><?= $token_api ?></span>
            
            <input type="button" value="modifier" id="modify_token" />
            </div>
            <div id="updateToken" style="display:none">
                <form method='post' action='options.php'>
                <?php wp_nonce_field('update-options'); ?>
                <input type='text' name='update_token' value='<?= $token_api ?>' />
                <input type='submit' value='enregistrer' />
                </form>
            </div>
            <?php } ?>
        </div>
    </div>
    <div class="blockForm fLeft">
        <div class="intitule2 fLeft">Choisissez le n° d'envoi</div>
        <div class="input2 fLeft">
            <select name="from">
            			<option value="20345">20345 (Orange France)</option>
            			<option value="38100">38100 (multi-opérateur France)</option>
            			<option value="967482">967482 (Orange GB)</option>
            			<option value="447797805210">447797805210 (international)</option>
            			</select>
        </div>
    </div>
</div>


</div>